<?php

namespace Flamingo\Core;

use Flamingo\Processor\ProcessorInterface;

/**
 * Class Task
 * A task is an entry point to the script execution
 *
 * @package Flamingo\Core
 */
class Task
{
    /**
     * Values returned by processes
     */
    const STATUS_OK = 0;
    const STATUS_WARN = 1;
    const STATUS_ERROR = 2;
    const STATUS_REDIRECT = 3;
    const STATUS_SUMMON = 4;

    /**
     * @var ProcessorInterface[]
     */
    protected $processors = [];

    /**
     * Add a process to the list
     *
     * @param ProcessorInterface $processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * Execute each processors through current runtime
     *
     * @param TaskRuntime $taskRuntime
     * @return TaskRuntime
     */
    public function execute(TaskRuntime $taskRuntime)
    {
        foreach ($this->processors as $processor) {
            $processor->execute($taskRuntime);
        }

        return $taskRuntime;
    }
}