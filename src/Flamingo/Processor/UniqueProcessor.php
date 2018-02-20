<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class UniqueProcessor
 * @package Flamingo\Processor
 */
class UniqueProcessor extends AbstractSingleSourceProcessor
{
    /**
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table $source, TaskRuntime $taskRuntime)
    {
        $source->copy(array_unique($source->getArrayCopy(), SORT_REGULAR));
    }
}