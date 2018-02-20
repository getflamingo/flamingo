<?php

namespace Flamingo\Processor;

use Flamingo\Core\TaskRuntime;

/**
 * Class TaskProcessor
 * @package Flamingo\Process
 */
class TaskProcessor extends AbstractProcessor
{
    /**
     * Redirects to the specified tasks.
     * TODO: Add a property to forward to the given tasks instead of redirect
     * TODO: Add error handling
     *
     * @param TaskRuntime $taskRuntime
     */
    public function execute(TaskRuntime $taskRuntime)
    {
        foreach ($this->configuration as $taskName) {
            $taskRuntime->redirect($taskName);
        }
    }
}