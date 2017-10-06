<?php

namespace Flamingo\Core;

/**
 * Interface UserFunctionInterface
 * @package Flamingo\Core
 */
interface UserFunctionInterface
{
    /**
     * @param array $configuration
     * @param TaskRuntime $taskRuntime
     * @return mixed
     */
    public function run(array $configuration, TaskRuntime $taskRuntime);
}