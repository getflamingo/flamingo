<?php

namespace Flamingo\Processor\TransformHelper;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class AbstractTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
abstract class AbstractTransformHelper
{
    /**
     * @var TaskRuntime
     */
    protected $taskRuntime;

    /**
     * @var Table
     */
    protected $source;

    /**
     * AbstractTransformHelper constructor.
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    public function __construct(Table $source, TaskRuntime $taskRuntime)
    {
        $this->source = $source;
        $this->taskRuntime = $taskRuntime;
    }
}