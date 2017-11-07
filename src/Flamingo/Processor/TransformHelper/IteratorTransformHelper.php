<?php

namespace Flamingo\Processor\TransformHelper;

use Flamingo\Core\TransformHelperRuntime;

/**
 * Class IteratorTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
class IteratorTransformHelper extends AbstractTransformHelper
{
    /**
     * @param TransformHelperRuntime $runtime
     */
    public function get(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = array_key_exists($runtime['parameters'], $runtime['value'])
            ? $runtime['value'][$runtime['parameters']] : null;
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function pop(TransformHelperRuntime $runtime)
    {
        array_pop($runtime['value']);
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function push(TransformHelperRuntime $runtime)
    {
        array_push($runtime['value'], $runtime['parameters']);
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function shift(TransformHelperRuntime $runtime)
    {
        array_shift($runtime['value']);
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function unshift(TransformHelperRuntime $runtime)
    {
        array_unshift($runtime['value'], $runtime['parameters']);
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function implode(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = implode($runtime['parameters'], $runtime['value']);
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function explode(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = explode($runtime['parameters'], $runtime['value']);
    }
}