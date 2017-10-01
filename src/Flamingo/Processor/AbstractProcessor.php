<?php

namespace Flamingo\Processor;

use Flamingo\Core\TaskRuntime;

/**
 * Class AbstractProcessor
 * @package Flamingo\Processor
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * Process data tables using custom functions
     *
     * @param array $configuration
     * @param TaskRuntime $taskRuntime
     * @return TaskRuntime
     */
    abstract public function execute(array $configuration, TaskRuntime &$taskRuntime);
}