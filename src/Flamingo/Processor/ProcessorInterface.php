<?php

namespace Flamingo\Processor;

use Flamingo\Core\TaskRuntime;

/**
 * Interface ProcessorInterface
 * @package Flamingo\Processor
 */
interface ProcessorInterface
{
    /**
     * Process data tables using custom functions
     * TODO: Add return signal for the task
     *
     * @param TaskRuntime $taskRuntime
     * @return mixed
     */
    public function execute(TaskRuntime &$taskRuntime);
}