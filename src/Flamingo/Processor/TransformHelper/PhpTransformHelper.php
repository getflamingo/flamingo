<?php

namespace Flamingo\Processor\TransformHelper;

/**
 * Class PhpTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
class PhpTransformHelper extends AbstractTransformHelper
{
    /**
     * @param string $function
     * @param $parameters
     */
    public function __call($function, $parameters)
    {
        if (is_callable($function)) {
            $parameters[0]['value'] = $function($parameters[0]['value']);
        }
    }
}