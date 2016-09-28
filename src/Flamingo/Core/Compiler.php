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

        foreach ($configuration as $processName => $processConf) {
            $process = $this->parseProcess($processName, $processConf);
            $task->addProcess($process);
        }

        return $task;
    }

    /**
     * Parse process conf
     *
     * @param string $name
     * @param array $configuration
     * @return \Flamingo\Core\Process
     */
    protected function parseProcess($name, $configuration)
    {
        if (is_string($configuration) && $taskName = Utility::taskName($configuration)) {
            return new TaskProcess($configuration);
        }

        var_dump($configuration); die;
    }
}