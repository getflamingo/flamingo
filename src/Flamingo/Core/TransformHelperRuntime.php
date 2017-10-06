<?php

namespace Flamingo\Core;

/**
 * Class TransformHelperRuntime
 * @package Flamingo\Core
 */
class TransformHelperRuntime extends \ArrayIterator
{
    /**
     * TransformHelperValues constructor.
     * @param mixed $value
     * @param mixed $parameters
     * @param array $row
     * @param string $property
     */
    public function __construct($value, $parameters, array $row, $property)
    {
        parent::__construct([
            'value' => $value,
            'parameters' => $parameters,
            'row' => $row,
            'property' => $property,
        ]);
    }
}