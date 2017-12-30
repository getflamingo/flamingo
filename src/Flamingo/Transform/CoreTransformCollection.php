<?php

namespace Flamingo\Processor\TransformHelper;

use Flamingo\Core\TransformRuntime;
use Flamingo\Transform\AbstractTransformCollection;

/**
 * Class CoreTransformCollection
 * @package Flamingo\Processor\TransformHelper
 */
class CoreTransformCollection extends AbstractTransformCollection
{
    /**
     * @param TransformRuntime $runtime
     */
    public function set(TransformRuntime $runtime)
    {
        $runtime->setValue($runtime->getArguments());
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function copy(TransformRuntime $runtime)
    {
        $columnName = $runtime->getArguments();
        $value = $runtime->hasRowColumn($columnName) ? $runtime->getRowValue($columnName) : null;
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function remove(TransformRuntime $runtime)
    {
        $columnName = $runtime->getArguments();
        if ($runtime->hasRowColumn($columnName)) {
            $runtime->removeRowColumn($columnName);
        }
    }
}