<?php

namespace Flamingo\Processor;

/**
 * Class MappingProcessor
 * @package Flamingo\Processor
 */
class MappingProcessor extends AbstractProcessor
{
    /**
     * @var array
     */
    protected $options = [
        'keepProperties' => true,
        'map' => [],
    ];

    /**
     * Rename columns recursively.
     */
    public function run()
    {
        foreach ($this->table as &$record) {

            // Backup current record
            $baseRecord = $record;

            // Reset record if needed
            if ($this->options['keepProperties'] === false) {
                $record = [];
            }

            // Apply mapping
            foreach ($this->options['map'] as $key => $newKey) {

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
