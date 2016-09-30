<?php

namespace Flamingo\Core;

/**
 * Class Task
 *
 * A task is an entry point to the script execution
 * TODO: Find a way to call tasks from parent script
 *
 * @package Flamingo\Core
 */
class Task
{
    /**
     * Values returned by processes
     */
    const OK = 0;
    const WARN = 1;
    const ERROR = 2;
    const REDIRECT = 3;
    const SUMMON = 4;

    /**
     * @var array<\Flamingo\Core\Process>
     */
    protected $processes = [];

    /**
     * Add a process to the list
     * @param \Flamingo\Core\Process $process
     */
    public function addProcess(Process $process)
    {
        $this->processes[] = $process;
    }

    /**
     * Execute each processes
     * @return array<\Flamingo\Model\Table> $data
     */
    public function execute()
    {
        $data = [];

        foreach ($this->processes as $process) {
            $process->execute($data);
        }

        return $data;
    }
}