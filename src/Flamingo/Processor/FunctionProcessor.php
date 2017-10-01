<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class FunctionProcessor
 * @package Flamingo\Processor
 */
class FunctionProcessor extends AbstractSingleSourceProcessor
{
    /**
     * Call user function
     *
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table $source, TaskRuntime $taskRuntime)
    {
        $configuration = is_string($this->configuration) ? ['__function' => $this->configuration] : $this->configuration;
        $userFunction = $configuration['__function'];
        unset($configuration['__function']);

        call_user_func($userFunction, $configuration, $taskRuntime);
    }
}