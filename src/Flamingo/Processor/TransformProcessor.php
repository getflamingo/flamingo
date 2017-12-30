<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;
use Flamingo\Core\TransformRuntime;
use Flamingo\Transform\AbstractTransformCollection;

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

                    // Get modifier method and arguments
                    $method = current(array_keys($modConf));
                    $arguments = current($modConf);

                    // Invoke TransformHelper and update value
                    $transformHelper = $this->invokeTransformCollection($method, $source, $taskRuntime);
                    $value = array_key_exists($field, $row) ? $row[$field] : null;
                    $runtime = new TransformRuntime($value, $arguments, $row, $field);
                    $transformHelper->$method($runtime);

                    // Apply changes on row
                    if ($row !== $runtime->getRow()) {
                        $row = $runtime->getRow();
                        continue;
                    }

                    // Apply changes on column
                    if ($value !== $runtime->getValue()) {
                        $row[$field] = $runtime->getValue();
                        continue;
                    }
                }
            }
        }
    }

    /**
     * @var array
     */
    protected $transformCollectionInstances = [];

    /**
     * Get transform collection instance, according to the needed modifier.
     * TODO: Remove the $source and $taskRuntime parameters since its out of context
     *
     * @param string $identifier
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     * @return AbstractTransformCollection
     */
    protected function invokeTransformCollection($identifier, Table $source, TaskRuntime $taskRuntime)
    {
        $className = null;

        foreach ($GLOBALS['FLAMINGO']['Classes']['TransformCollection'] as $collectionConf) {
            if (in_array($identifier, $collectionConf['modifiers'])) {
                $className = $collectionConf['className'];
            }
        }

        if ($className === null) {
            return null;
        }

        if (!array_key_exists($className, $this->transformCollectionInstances)) {
            $this->transformCollectionInstances[$className] = new $className($source, $taskRuntime);
        }

        return $this->transformCollectionInstances[$className];
    }
}