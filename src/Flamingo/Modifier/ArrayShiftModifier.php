<?php
namespace Flamingo\Modifier;

/**
 * Class ArrayShiftModifier
 * @package Flamingo\Modifier
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