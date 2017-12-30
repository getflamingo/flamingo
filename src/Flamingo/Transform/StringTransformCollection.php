<?php

namespace Flamingo\Transform;

use Flamingo\Core\TransformRuntime;

/**
 * Class StringTransformCollection
 * @package Flamingo\Transform
 */
class StringTransformCollection extends AbstractTransformCollection
{
    /**
     * @param TransformRuntime $runtime
     */
    public function str_replace(TransformRuntime $runtime)
    {
        $arguments = $runtime->getArguments();
        $value = str_replace($arguments[0], $arguments[1], $runtime->getValue());
        $runtime->setValue($value);
    }
}