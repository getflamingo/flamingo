<?php

namespace Flamingo\Processor\TransformHelper;

/**
 * Class MathTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
class MathTransformHelper extends AbstractTransformHelper
{
    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function add(&$value, $parameter, array $row)
    {
        $value += $parameter;
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function sub(&$value, $parameter, array $row)
    {
        $value -= $parameter;
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function mul(&$value, $parameter, array $row)
    {
        $value *= $parameter;
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function div(&$value, $parameter, array $row)
    {
        $value /= $parameter;
    }
}