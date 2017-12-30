<?php

namespace Flamingo\Transform;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class AbstractTransformCollection
 * @package Flamingo\Transform
 */
abstract class AbstractTransformCollection
{
    /**
     * @var TaskRuntime
     * @deprecated
     */
    protected $taskRuntime;

    /**
     * @var Table
     * @deprecated
     */
    protected $source;

    /**
     * AbstractTransformCollection constructor.
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    public function __construct(Table $source, TaskRuntime $taskRuntime)
    {
        $this->source = $source;
        $this->taskRuntime = $taskRuntime;
    }
}