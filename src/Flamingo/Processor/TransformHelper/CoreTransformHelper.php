<?php

namespace Flamingo\Processor\TransformHelper;

/**
 * Class CoreTransformHelper
 * @package Flamingo\Processor\TransformHelper
 */
class CoreTransformHelper extends AbstractTransformHelper
{
    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function set(&$value, $parameter, array $row)
    {
        $value = $parameter;
    }

    /**
     * @param $value
     * @param $parameter
     * @param array $row
     */
    public function copy(&$value, $parameter, array $row)
    {
        $value = array_key_exists($parameter, $row) ? $row[$parameter] : null;
    }
}