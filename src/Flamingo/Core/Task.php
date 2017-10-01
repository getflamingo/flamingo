<?php

namespace Flamingo\Core;

use Flamingo\Flamingo;
use Flamingo\Processor\ProcessorInterface;

/**
 * Class Task
 * A task is an entry point to the script execution
 * TODO: Implement a more object oriented way to summon tasks
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
     * @var array<\Flamingo\Processor\ProcessorInterface>
     */
    protected $processors = [];

    /**
     * Add a process to the list
     *
     * @param \Flamingo\Processor\ProcessorInterface $processor
     */
    public function addProcessor($processor)
    {
        if ($processor instanceof ProcessorInterface) {
            $this->processors[] = $processor;
        }
    }

    /**
     * Execute each processors
     *
     * @param \Flamingo\Core\TaskRuntime $taskRuntime
     * @return \Flamingo\Core\TaskRuntime
     */
    public function execute($taskRuntime)
    {
        foreach ($this->processors as $processor) {
            $processor->execute($taskRuntime);
        }

        return $taskRuntime;
    }
}