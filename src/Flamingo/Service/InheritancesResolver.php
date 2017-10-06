<?php

namespace Flamingo\Service;

use Flamingo\Exception\CycleInheritancesException;
use Flamingo\Utility\ArrayUtility;

/**
 * Class InheritancesResolver
 * This file is part of the TYPO3 CMS project.
 * https://github.com/TYPO3/TYPO3.CMS/blob/master/typo3/sysext/form/Classes/Mvc/Configuration/InheritancesResolver.php
 *
 * @package Flamingo\Service
 */
class InheritancesResolver
{
    /**
     * The operator which is used to declare inheritances
     */
    const INHERITANCE_OPERATOR = '__inheritances';

    /**
     * The reference configuration is used to get untouched values which
     * can be merged into the touched configuration.
     *
     * @var array
     */
    protected $referenceConfiguration = [];

    /**
     * This stack is needed to find cyclically inheritances which are on
     * the same nesting level but which do not follow each other directly.
     *
     * @var array
     */
    protected $inheritanceStack = [];

    /**
     * Needed to park a configuration path for cyclically inheritances
     * detection while inheritances for this path is ongoing.
     *
     * @var string
     */
    protected $inheritancePathToCheck = '';

    /**
     * Returns an instance of this service. Additionally the configuration
     * which should be resolved can be passed.
     *
     * @param array $configuration
     * @return InheritancesResolver
     */
    public static function create(array $configuration = [])
    {
        /** @var InheritancesResolver $inheritancesResolver */
        $inheritancesResolver = new InheritancesResolver();
        $inheritancesResolver->setReferenceConfiguration($configuration);
        return $inheritancesResolver;
    }

    /**
     * Reset the state of this service.
     * Mainly introduced for unit tests.
     *
     * @return InheritancesResolver
     * @internal
     */
    public function reset()
    {
        $this->referenceConfiguration = [];
        $this->inheritanceStack = [];
        $this->inheritancePathToCheck = '';
        return $this;
    }

    /**
     * Set the reference configuration which is used to get untouched
     * values which can be merged into the touched configuration.
     *
     * @param array $referenceConfiguration
     * @return InheritancesResolver
     */
    public function setReferenceConfiguration(array $referenceConfiguration)
    {
        $this->referenceConfiguration = $referenceConfiguration;
        return $this;
    }

    /**
     * Resolve all inheritances within a configuration.
     * After that the configuration array is cleaned from the
     * inheritance operator.
     *
     * @return array
     */
    public function getResolvedConfiguration()
    {
        $configuration = $this->resolve($this->referenceConfiguration);
        $configuration = $this->removeInheritanceOperatorRecursive($configuration);
        return $configuration;
    }

    /**
     * Resolve all inheritances within a configuration.
     *
     * @param array $configuration
     * @param array $pathStack
     * @param bool $setInheritancePathToCheck
     * @return array
     */
    protected function resolve(array $configuration, array $pathStack = [], $setInheritancePathToCheck = true)
    {
        foreach ($configuration as $key => $values) {
            $pathStack[] = $key;
            $path = implode('.', $pathStack);

            $this->throwExceptionIfCycleInheritances($path, $path);
            if ($setInheritancePathToCheck) {
                $this->inheritancePathToCheck = $path;
            }

            if (is_array($configuration[$key])) {
                if (isset($configuration[$key][self::INHERITANCE_OPERATOR])) {
                    try {
                        $inheritances = ArrayUtility::getValueByPath(
                            $this->referenceConfiguration,
                            $path . '.' . self::INHERITANCE_OPERATOR,
                            '.'
                        );
                    } catch (\RuntimeException $exception) {
                        $inheritances = null;
                    }

                    if (is_array($inheritances)) {
                        $inheritedConfigurations = $this->resolveInheritancesRecursive($inheritances);

                        $configuration[$key] = array_replace_recursive(
                            $inheritedConfigurations,
                            $configuration[$key]
                        );
                    }

                    unset($configuration[$key][self::INHERITANCE_OPERATOR]);
                }

                if (!empty($configuration[$key])) {
                    $configuration[$key] = $this->resolve(
                        $configuration[$key],
                        $pathStack
                    );
                }
            }
            array_pop($pathStack);
        }

        return $configuration;
    }

    /**
     * Additional helper for the resolve method.
     *
     * @toDo: More description
     * @param array $inheritances
     * @return array
     * @throws CycleInheritancesException
     */
    protected function resolveInheritancesRecursive(array $inheritances)
    {
        ksort($inheritances);
        $inheritedConfigurations = [];
        foreach ($inheritances as $inheritancePath) {
            $this->throwExceptionIfCycleInheritances($inheritancePath, $inheritancePath);
            try {
                $inheritedConfiguration = ArrayUtility::getValueByPath(
                    $this->referenceConfiguration,
                    $inheritancePath,
                    '.'
                );
            } catch (\RuntimeException $exception) {
                $inheritedConfiguration = null;
            }

            if (
                isset($inheritedConfiguration[self::INHERITANCE_OPERATOR])
                && count($inheritedConfiguration) === 1
            ) {
                if ($this->inheritancePathToCheck === $inheritancePath) {
                    throw new CycleInheritancesException(
                        $this->inheritancePathToCheck . ' has cycle inheritances',
                        1474900796
                    );
                }

                $inheritedConfiguration = $this->resolveInheritancesRecursive(
                    $inheritedConfiguration[self::INHERITANCE_OPERATOR]
                );
            } else {
                $pathStack = explode('.', $inheritancePath);
                $key = array_pop($pathStack);
                $newConfiguration = [
                    $key => $inheritedConfiguration
                ];
                $inheritedConfiguration = $this->resolve(
                    $newConfiguration,
                    $pathStack,
                    false
                );
                $inheritedConfiguration = $inheritedConfiguration[$key];
            }

            if ($inheritedConfiguration === null) {
                throw new CycleInheritancesException(
                    $inheritancePath . ' does not exist within the configuration',
                    1489260796
                );
            }

            $inheritedConfigurations = array_replace_recursive(
                $inheritedConfigurations,
                $inheritedConfiguration
            );
        }

        return $inheritedConfigurations;
    }

    /**
     * Throw an exception if a cycle is detected.
     *
     * @toDo: More description
     * @param string $path
     * @param string $pathToCheck
     * @throws CycleInheritancesException
     */
    protected function throwExceptionIfCycleInheritances($path, $pathToCheck)
    {
        try {
            $configuration = ArrayUtility::getValueByPath(
                $this->referenceConfiguration,
                $path,
                '.'
            );
        } catch (\RuntimeException $exception) {
            $configuration = null;
        }

        if (isset($configuration[self::INHERITANCE_OPERATOR])) {
            try {
                $inheritances = ArrayUtility::getValueByPath(
                    $this->referenceConfiguration,
                    $path . '.' . self::INHERITANCE_OPERATOR,
                    '.'
                );
            } catch (\RuntimeException $exception) {
                $inheritances = null;
            }

            if (is_array($inheritances)) {
                foreach ($inheritances as $inheritancePath) {
                    try {
                        $configuration = ArrayUtility::getValueByPath(
                            $this->referenceConfiguration,
                            $inheritancePath,
                            '.'
                        );
                    } catch (\RuntimeException $exception) {
                        $configuration = null;
                    }

                    if (isset($configuration[self::INHERITANCE_OPERATOR])) {
                        try {
                            $_inheritances = ArrayUtility::getValueByPath(
                                $this->referenceConfiguration,
                                $inheritancePath . '.' . self::INHERITANCE_OPERATOR,
                                '.'
                            );
                        } catch (\RuntimeException $exception) {
                            $_inheritances = null;
                        }

                        foreach ($_inheritances as $_inheritancePath) {
                            if (strpos($pathToCheck, $_inheritancePath) === 0) {
                                throw new CycleInheritancesException(
                                    $pathToCheck . ' has cycle inheritances',
                                    1474900797
                                );
                            }
                        }
                    }

                    if (
                        array_key_exists($pathToCheck, $this->inheritanceStack)
                        && is_array($this->inheritanceStack[$pathToCheck])
                        && in_array($inheritancePath, $this->inheritanceStack[$pathToCheck])
                    ) {
                        $this->inheritanceStack[$pathToCheck][] = $inheritancePath;
                        throw new CycleInheritancesException(
                            $pathToCheck . ' has cycle inheritances',
                            1474900799
                        );
                    }
                    $this->inheritanceStack[$pathToCheck][] = $inheritancePath;
                    $this->throwExceptionIfCycleInheritances($inheritancePath, $pathToCheck);
                }
                $this->inheritanceStack[$pathToCheck] = null;
            }
        }
    }

    /**
     * Recursively remove self::INHERITANCE_OPERATOR keys
     *
     * @param array $array
     * @return array the modified array
     */
    protected function removeInheritanceOperatorRecursive(array $array)
    {
        $result = $array;
        foreach ($result as $key => $value) {
            if ($key === self::INHERITANCE_OPERATOR) {
                unset($result[$key]);
                continue;
            }

            if (is_array($value)) {
                $result[$key] = $this->removeInheritanceOperatorRecursive($value);
            }
        }
        return $result;
    }
}