<?php

namespace Flamingo\Transform;

use Flamingo\Core\TransformRuntime;

/**
 * Class IteratorTransformCollection
 * @package Flamingo\Transform
 */
class IteratorTransformCollection extends AbstractTransformCollection
{
    /**
     * @param TransformRuntime $runtime
     */
    public function array_get(TransformRuntime $runtime)
    {
        $columnName = $runtime->getArguments();
        $value = $runtime->hasRowColumn($columnName) ? $runtime->getRowValue($columnName) : null;
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function array_pop(TransformRuntime $runtime)
    {
        $value = array_pop($runtime->getValue());
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function array_push(TransformRuntime $runtime)
    {
        $value = array_push($runtime->getValue(), $runtime->getArguments());
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function array_shift(TransformRuntime $runtime)
    {
        $value = array_shift($runtime->getValue());
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function array_unshift(TransformRuntime $runtime)
    {
        $value = array_unshift($runtime->getValue(), $runtime->getArguments());
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function implode(TransformRuntime $runtime)
    {
        $value = implode($runtime->getArguments(), $runtime->getValue());
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function explode(TransformRuntime $runtime)
    {
        $value = explode($runtime->getArguments(), $runtime->getValue());
        $runtime->setValue($value);
    }
}