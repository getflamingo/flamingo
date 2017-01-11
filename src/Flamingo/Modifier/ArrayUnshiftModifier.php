<?php
namespace Flamingo\Modifier;

/**
 * Class ArrayUnshiftModifier
 * @package Flamingo\Modifier
 */
class ArrayUnshiftModifier implements ModifierInterface
{
    /**
     * @param array $value
     * @param mixed $var
     * @param array $record
     */
    public function process(&$value, $var, $record)
    {
        $value = array_unshift($value, $var);
    }
}