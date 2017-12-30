<?php

namespace Flamingo\Transform;

use Flamingo\Core\TransformRuntime;

/**
 * Class MathTransformCollection
 * @package Flamingo\Transform
 */
class MathTransformCollection extends AbstractTransformCollection
{
    /**
     * @param TransformRuntime $runtime
     */
    public function add(TransformRuntime $runtime)
    {
        $value = $runtime->getValue() + $runtime->getArguments();
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function sub(TransformRuntime $runtime)
    {
        $value = $runtime->getValue() - $runtime->getArguments();
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function mul(TransformRuntime $runtime)
    {
        $value = $runtime->getValue() * $runtime->getArguments();
        $runtime->setValue($value);
    }

    /**
     * @param TransformRuntime $runtime
     */
    public function div(TransformRuntime $runtime)
    {
        $value = $runtime->getValue() / $runtime->getArguments();
        $runtime->setValue($value);
    }
}