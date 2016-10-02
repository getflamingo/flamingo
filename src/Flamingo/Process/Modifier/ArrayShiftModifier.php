<?php

namespace Flamingo\Process\Modifier;

/**
 * Class ArrayShiftModifier
 * @package Flamingo\Process\Modifier
 */
class ArrayShiftModifier implements ModifierInterface
{
    /**
     * @param array $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = array_shift($value);
    }
}