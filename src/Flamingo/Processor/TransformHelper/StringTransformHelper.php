<?php

namespace Flamingo\Processor\TransformHelper;

use Flamingo\Core\TransformHelperRuntime;

/**
 * Class StringTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
class StringTransformHelper extends AbstractTransformHelper
{
    /**
     * @param TransformHelperRuntime $runtime
     */
    public function str_replace(TransformHelperRuntime $runtime)
    {
        $runtime['value'] = str_replace($runtime['parameters'][0], $runtime['parameters'][1], $runtime['value']);
    }
}