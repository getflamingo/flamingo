<?php
namespace Flamingo\Modifier;

/**
 * Class FloatvalModifier
 * @package Flamingo\Modifier
 */
class FloatvalModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = floatval($value);
    }
}