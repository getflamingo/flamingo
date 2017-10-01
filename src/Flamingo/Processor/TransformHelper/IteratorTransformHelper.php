<?php

namespace Flamingo\Processor\TransformHelper;

/**
 * Class ArrayTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
class IteratorTransformHelper extends AbstractTransformHelper
{
    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function get(&$value, $parameter, array $row)
    {
        $value = array_key_exists($parameter, $value) ? $value[$parameter] : null;
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function pop(&$value, $parameter, array $row)
    {
        array_pop($value);
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function push(&$value, $parameter, array $row)
    {
        array_push($value, $parameter);
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function shift(&$value, $parameter, array $row)
    {
        array_shift($value);
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function unshift(&$value, $parameter, array $row)
    {
        array_unshift($value, $parameter);
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function implode(&$value, $parameter, array $row)
    {
        $value = implode($parameter, $value);
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function explode(&$value, $parameter, array $row)
    {
        $value = explode($parameter, $value);
    }
}