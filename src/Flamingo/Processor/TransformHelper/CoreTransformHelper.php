<?php

namespace Flamingo\Processor\TransformHelper;

use Flamingo\Core\TransformHelperRuntime;

/**
 * Class CoreTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
class CoreTransformHelper extends AbstractTransformHelper
{
    /**
     * @param TransformHelperRuntime $runtime
     */
    public function set(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = $runtime['parameters'];
    }

    /**
     * @param TransformHelperRuntime $runtime
     */
    public function copy(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = array_key_exists($runtime['parameters'], $runtime['row'])
            ? $runtime['row'][$runtime['parameters']] : null;
    }
}