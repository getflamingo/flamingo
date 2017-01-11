<?php
namespace Flamingo\Modifier;

/**
 * Class ArrayPopModifier
 * @package Flamingo\Modifier
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