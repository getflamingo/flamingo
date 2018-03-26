<?php

namespace Flamingo\Processor;

use Flamingo\TaskRuntime;

/**
 * Class MappingProcessor
 * @package Flamingo\Processor
 */
class MappingProcessor extends AbstractProcessor
{
    /**
     * Rename columns recursively.
     *
     * @param TaskRuntime $taskRuntime
     */
    public function execute(TaskRuntime $taskRuntime)
    {
        foreach ($this->table as &$record) {

            // Backup current record
            $baseRecord = $record;

            // Reset record if needed
            if ($GLOBALS['FLAMINGO']['Options']['Mapping']['KeepOldProperties'] === false) {
                $record = [];
            }

            // Apply mapping
            foreach ($this->options as $key => $newKey) {

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
