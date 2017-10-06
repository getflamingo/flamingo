<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class MappingProcessor
 * @package Flamingo\Processor
 */
class MappingProcessor extends AbstractSingleSourceProcessor
{
    /**
     * Rename some columns
     *
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table $source, TaskRuntime $taskRuntime)
    {
        foreach ($source as &$record) {

            // Backup current record
            $baseRecord = $record;

            // Reset record if needed
            if ($GLOBALS['FLAMINGO']['Options']['Mapping']['KeepOldProperties'] === false) {
                $record = [];
            }

            // Apply mapping
            foreach ($this->configuration as $key => $newKey) {

                if (array_key_exists($key, $baseRecord)) {
                    $record[$newKey] = $baseRecord[$key];

                    if ($key !== $newKey) {
                        unset($record[$key]);
                    }
                }
            }
        }
    }
}