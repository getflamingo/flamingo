<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;
use Flamingo\Processor\TransformHelper\AbstractTransformHelper;

/**
 * Class TransformProcessor
 * @package Flamingo\Processor
 */
class TransformProcessor extends AbstractSingleSourceProcessor
{
    /**
     * Process every rows and applies modifier on defined columns
     *
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table $source, TaskRuntime $taskRuntime)
    {
        if (!is_array($this->configuration)) {
            return;
        }

        foreach ($source as &$row) {
            foreach ($this->configuration as $field => $modifiers) {

                // No modifier specified
                if (is_null($modifiers)) {
                    continue;
                }

                // Use set for standalone values
                if (!is_array($modifiers)) {
                    $modifiers = [['set' => $modifiers]];
                }

                // This column does not exist
                if (
                    !array_key_exists($field, $row)
                    && $GLOBALS['FLAMINGO']['Options']['Transform']['PropertyMustExist']
                ) {
                    continue;
                }

                foreach ($modifiers as $modConf) {

                    // Add empty params when there is none
                    if (is_string($modConf)) {
                        $modConf = [$modConf => null];
                    }

                    // Conf is probably null
                    if (!is_array($modConf)) {
                        continue;
                    }

                    // Get modifier name and options
                    $method = current(array_keys($modConf));
                    $options = current($modConf);

                    // Invoke TransformHelper and update value
                    $transformHelper = $this->invokeTransformHelper($method, $source, $taskRuntime);
                    $transformHelper->$method($row[$field], $options, $row);
                }
            }
        }
    }

    /**
     * @var array
     */
    protected $helperInstances = [];

    /**
     * Get transform helper instance
     *
     * @param string $identifier
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     * @return AbstractTransformHelper
     */
    protected function invokeTransformHelper($identifier, Table $source, TaskRuntime $taskRuntime)
    {
        $className = null;

        foreach ($GLOBALS['FLAMINGO']['Classes']['TransformHelper'] as $helperConf) {
            if (in_array($identifier, $helperConf['modifiers'])) {
                $className = $helperConf['className'];
            }
        }

        if ($className === null) {
            return null;
        }

        if (!array_key_exists($className, $this->helperInstances)) {
            $this->helperInstances[$className] = new $className($source, $taskRuntime);
        }

        return $this->helperInstances[$className];
    }
}