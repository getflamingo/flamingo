<?php

namespace Flamingo\Core;

use Flamingo\Processor\ProcessorInterface;

/**
 * Class Task
 * @package Flamingo\Core
 */
class Task
{
    /**
     * Values returned by processors
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
     * @param ProcessorInterface $processor
     */
    public function addProcessor(ProcessorInterface $processor)
    {
        $this->processors[] = $processor;
    }

    /**
     * Execute each processors through current runtime
     * TODO: Keep track of current Runtime status
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