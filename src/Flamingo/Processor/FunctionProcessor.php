<?php

namespace Flamingo\Processor;

use Flamingo\Core\TaskRuntime;

/**
 * Class FunctionProcessor
 * @package Flamingo\Processor
 */
class FunctionProcessor extends AbstractProcessor
{
    /**
     * The operator which is used to declare user function
     */
    const FUNCTION_OPERATOR = '__function';

    /**
     * Call user function (can be a public method of a class name)
     *
     * @param TaskRuntime $taskRuntime
     */
    public function execute(TaskRuntime $taskRuntime)
    {
        $configuration = is_string($this->configuration)
            ? [self::FUNCTION_OPERATOR => $this->configuration]
            : $this->configuration;

        $userFunction = $configuration[self::FUNCTION_OPERATOR];
        unset($configuration[self::FUNCTION_OPERATOR]);

        call_user_func($userFunction, $configuration, $taskRuntime);
    }
}