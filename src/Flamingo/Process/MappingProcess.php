<?php
namespace Flamingo\Process;

use Analog\Analog;
use Flamingo\Core\Task;

/**
 * Class MappingProcess
 * @package Flamingo\Process
 */
class MappingProcess extends AbstractProcess
{
    /**
     * MappingProcess constructor.
     * Clean up the configuration array before using it
     *
     * @param array $configuration
     */
    public function __construct($configuration)
    {
        // No modifier specified
        if (!is_array($configuration)) {
            Analog::warning('Mapping array is empty or null');
            return;
        }

        foreach ($configuration as $key => $newKey) {

            // Support string as "needed" value
            if (is_string($newKey)) {
                $newKey = [$newKey => $newKey];
            }

            // Support sub arrays syntax
            if (is_array($newKey)) {
                $key = current(array_keys($newKey));
                $newKey = current(array_values($newKey));
            }

            // Add non null mapping values to object conf
            if (!empty($newKey)) {
                $this->configuration[$key] = $newKey;
            }
        }
    }

    /**
     * Remap some keys
     *
     * @param array $data
     * @return int
     */
    public function execute(&$data)
    {
        foreach ($data as &$table) {
            foreach ($table as &$record) {

                // Backup current record
                $baseRecord = $record;

                // Reset record if needed
                if ($GLOBALS['FLAMINGO']['CONF']['Mapping']['Keep'] == false) {
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

        return Task::OK;
    }
}