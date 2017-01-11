<?php
namespace Flamingo\Process;

use Flamingo\Core\Task;
use Flamingo\Utility\NamespaceUtility;

/**
 * Class ModifyProcess
 * @package Flamingo\Process
 */
class ModifyProcess extends AbstractProcess
{
    /**
     * ModifyProcess constructor.
     * Clean up the configuration array before using it
     *
     * @param array $configuration
     */
    public function __construct($configuration)
    {
        if (!is_array($configuration)) {
            return;
        }

        foreach ($configuration as $field => &$modifiers) {

            // No modifier specified
            if (empty($modifiers)) {
                continue;
            }

            // Only one modifier (as a string) given
            if (is_string($modifiers)) {
                $modifiers = [$modifiers => null];
            }

            foreach ($modifiers as $index => &$modConf) {

                // Add empty params when there is none
                if (is_string($modConf)) {
                    $modConf = [$modConf => null];
                }

                // Conf is probably null
                if (!is_array($modConf)) {
                    continue;
                }

                // Get modifier class name
                $name = current(array_keys($modConf));
                $className = 'Flamingo\\Process\\Modifier\\' . NamespaceUtility::pascalCase($name) . 'Modifier';

                // Class does not exist, remove from modifiers
                if (!class_exists($className)) {
                    unset($modifiers[$index]);
                }
            }

            // Remove empty elements
            if (empty(array_filter($modifiers))) {
                unset($configuration[$field]);
            }
        }

        parent::__construct($configuration);
    }

    /**
     * For each field, apply a set of functions
     *
     * @param array $data
     * @return int
     */
    public function execute(&$data)
    {
        // Execute those for every
        foreach ($data as &$table) {
            foreach ($table as &$record) {
                foreach ($this->configuration as $field => $modifiers) {

                    // This column does not exists
                    if (!array_key_exists($field, $record) && $GLOBALS['FLAMINGO']['CONF']['Modify']['MustExist']) {
                        continue;
                    }

                    foreach ($modifiers as $modConf) {

                        // Get modifier name and options
                        $name = current(array_keys($modConf));
                        $options = current($modConf);

                        // Create class name and execute it
                        $className = 'Flamingo\\Process\\Modifier\\' . NamespaceUtility::pascalCase($name) . 'Modifier';
                        (new $className)->process($record[$field], $options, $record);
                    }
                }
            }
        }

        return Task::OK;
    }
}