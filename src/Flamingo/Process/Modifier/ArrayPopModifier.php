<?php

namespace Flamingo\Process\Modifier;

/**
 * Class ArrayPopModifier
 * @package Flamingo\Process\Modifier
 */
class ArrayPopModifier implements ModifierInterface
{
    /**
     * @param array $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = array_pop($value);
    }
}