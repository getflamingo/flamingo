<?php

namespace Flamingo\Core;

use Flamingo\Process\TaskProcess;

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

        foreach ($configuration as $taskName => $taskConf) {
            if ($taskName = Utility::taskName($taskName)) {
                $tasks[$taskName] = $this->parseTask($taskConf);
            }
        }

        return $tasks;
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

            // Build class name
            $processName = Utility::processName($name);
            $className = 'Flamingo\\Process\\' . ucwords($processName) . 'Process';

            // Create process if it exists
            if (class_exists($className)) {
                return new $className($configuration);
            }
        }

        return null;
    }
}