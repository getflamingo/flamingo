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
     * The operator which is used to declare user function
     */
    const FUNCTION_OPERATOR = '__function';

    /**
     * Call user function
     *
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table $source, TaskRuntime $taskRuntime)
    {
        $configuration = is_string($this->configuration)
            ? [self::FUNCTION_OPERATOR => $this->configuration]
            : $this->configuration;

        $userFunction = $configuration[self::FUNCTION_OPERATOR];
        unset($configuration[self::FUNCTION_OPERATOR]);

        call_user_func($userFunction, $configuration, $taskRuntime);
    }
}