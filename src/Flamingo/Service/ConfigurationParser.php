<?php

namespace Flamingo\Service;

use Flamingo\Core\Task;
use Analog\Analog;

/**
 * Class ConfigurationParser
 * Inspired by the InheritancesResolverService from TYPO3 core
 *
 * @package Flamingo\Service
 */
class ConfigurationParser
{
    /**
     * @var array
     */
    protected $referenceConfiguration = [];

    /**
     * Returns an instance of this service. Additionally the configuration
     * which should be resolved can be passed.
     *
     * @param array $configuration
     * @return ConfigurationParser
     */
    public static function create(array $configuration = [])
    {
        /** @var ConfigurationParser $configurationParser */
        $configurationParser = new ConfigurationParser();
        $configurationParser->setReferenceConfiguration($configuration);
        return $configurationParser;
    }

    /**
     * Set the reference configuration which is used to get untouched
     * values which can be merged into the touched configuration.
     *
     * @param array $referenceConfiguration
     * @return ConfigurationParser
     */
    public function setReferenceConfiguration(array $referenceConfiguration)
    {
        $this->referenceConfiguration = $referenceConfiguration;
        return $this;
    }

    /**
     * Resolve all inheritances within a configuration.
     *
     * @return array<Flamingo\Core\Task>
     */
    public function getResolvedTasks()
    {
        $tasks = [];

        // Insert global configuration
        if (array_key_exists('Flamingo', $this->referenceConfiguration)) {
            $GLOBALS['FLAMINGO'] = $this->referenceConfiguration['Flamingo'];
        }

        // TODO: Insert required files

        foreach ($this->referenceConfiguration as $name => $conf) {
            if ($taskName = $this->extractTaskName($name)) {
                $tasks[$taskName] = $this->parseTask($conf);
            }
        }

        return $tasks;
    }

    /**
     * Return the taskName if matches the format
     * Return null if not
     *
     * @param string $string
     * @return string|null
     */
    protected function extractTaskName($string)
    {
        $matches = preg_match('/\w*\(\)/', $string);
        $taskName = substr($string, 0, -2);

        return ($matches && $taskName) ? strtolower($taskName) : null;
    }

    /**
     * Include all the required files
     * It can be used to call some Composer dependencies
     * TODO: Add YAML configuration support
     *
     * @param mixed $requires
     */
    protected function parseRequire($requires)
    {
        if (is_string($requires)) {
            $requires = [$requires];
        }

        if (!is_array($requires)) {
            Analog::debug('Require array is empty or null');
            return;
        }

        foreach ($requires as $require) {

            if (!is_string($require)) {
                Analog::warning(sprintf('Require list must contain only strings, %s given', gettype($require)));
                continue;
            }

//            if (NamespaceUtility::getExtension($require) !== 'php') {
//                Analog::warning('Require array only accepts PHP files for the moment!');
//                continue;
//            }

            if (!file_exists($require)) {
                Analog::warning(sprintf('Required file "%s" does not exist', $require));
                continue;
            }

            Analog::debug(sprintf('Require file "%s"', $require));
            require_once $require;
        }
    }

    /**
     * Parse task conf
     * Note: Each process conf must have no key
     * TODO: Add exception on process key
     *
     * @param array $configuration
     * @return \Flamingo\Core\Task
     */
    protected function parseTask($configuration)
    {
        $task = new Task();

        // Data is not an iterator
        if (empty($configuration) || !is_array($configuration)) {
            Analog::debug('Task configuration is empty or null');
            return $task;
        }

        foreach ($configuration as $processIdentifier => $processConf) {
            if ($process = $this->parseProcess($processConf)) {
                $task->addProcess($process);
            }
        }

        return $task;
    }

    /**
     * Build the processor class name and
     * create a new processor object using this conf
     *
     * @param array $configuration
     * @return \Flamingo\Process\ProcessInterface
     */
    protected function parseProcess($configuration)
    {
        if (is_array($configuration)) {

            // Get processor name and data
            $processorName = current(array_keys($configuration));
            $configuration = current($configuration);

            // Find class name
            $className = $GLOBALS['FLAMINGO']['Classes']['Processor'][$processorName]['className'];

            if (!class_exists($className)) {
                Analog::error(sprintf('The process "%s" does not exist', $processorName));
                return null;
            }

            // Create process if it exists
            Analog::debug(sprintf('Register process "%s"', $className));
            return new $className($configuration);
        }

        Analog::debug('Process configuration is empty or null');
        return null;
    }
}
