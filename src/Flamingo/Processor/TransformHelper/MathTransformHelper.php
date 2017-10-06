<?php

namespace Flamingo\Processor\TransformHelper;

use Flamingo\Core\TransformHelperRuntime;

/**
 * Class MathTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
class MathTransformHelper extends AbstractTransformHelper
{
    /**
     * @param TransformHelperRuntime $runtime
     */
    public function add(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = $runtime['value'] + $runtime['parameters'];
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function sub(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = $runtime['value'] - $runtime['parameters'];
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function mul(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = $runtime['value'] * $runtime['parameters'];
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function div(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = $runtime['value'] / $runtime['parameters'];
    }
}