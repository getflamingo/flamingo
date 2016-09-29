<?php

namespace Flamingo\Core;

use Flamingo\Process\TaskProcess;
use Flamingo\Utility\Iterator as IteratorUtility;
use Flamingo\Utility\Taxonomy as TaxonomyUtility;

/**
 * Class Compiler
 *
 * Read configuration array and output
 * a list of tasks for a script.
 *
 * @package Flamingo\Core
 */
class Compiler
{
    /**
     * Read configuration and return Tasks
     *
     * @param array $configuration
     * @return array<Flamingo\Core\Task>
     */
    public function parse($configuration)
    {
        $tasks = [];

        foreach ($configuration as $name => $conf) {

            if ($taskName = TaxonomyUtility::matches($name, 'Flamingo/Task/*')) {
                $taskName = strtolower($taskName[0]);
                $tasks[$taskName] = $this->parseTask($conf);
            }

            if ($confName = TaxonomyUtility::matches($name, 'Conf/*/*')) {
                $this->parseConf($confName[0], $confName[1], $conf);
            }
        }

        return $tasks;
    }

    /**
     * Parse Flamingo global conf to create class aliases
     * or to add configuration in $GLOBALS['FLAMINGO']['CONF']
     *
     * @param string $domain
     * @param string $key
     * @param mixed $value
     */
    protected function parseConf($domain, $key, $value)
    {
        // Add new process alias
        if (strtolower($domain) === 'alias' && !empty($value)) {
            if (class_exists($className = 'Flamingo\\Process\\' . ucwords($key) . 'Process')) {
                foreach (IteratorUtility::trimsplit(',', $value) as $alias) {
                    class_alias($className, 'Flamingo\\Process\\' . ucwords($alias) . 'Process');
                }
            }
            return;
        }

        // Add value global conf key
        $GLOBALS['FLAMINGO']['CONF'][$domain][$key] = $value;
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

        foreach ($configuration as $processConf) {
            if ($process = $this->parseProcess($processConf)) {
                $task->addProcess($process);
            }
        }

        return $task;
    }

    /**
     * Build the process class name and
     * create a new process using this conf
     *
     * @param array $configuration
     * @return \Flamingo\Core\Process
     */
    protected function parseProcess($configuration)
    {
        if (is_string($configuration) && $taskName = Utility::taskName($configuration)) {
            return new TaskProcess($configuration);
        }

        if (is_array($configuration)) {

            // Get configuration name and data
            $name = current(array_keys($configuration));
            $configuration = current($configuration);

            // Process name does not match any format
            if (!($processName = TaxonomyUtility::matches($name, 'Flamingo/Process/*'))) {
                if (!($processName = TaxonomyUtility::matches($name, '*'))) {
                    return null;
                }
            }

            // Build class name
            $className = 'Flamingo\\Process\\' . ucwords($processName[0]) . 'Process';

            // Create process if it exists
            if (class_exists($className)) {
                return new $className($configuration);
            }
        }

        return null;
    }
}