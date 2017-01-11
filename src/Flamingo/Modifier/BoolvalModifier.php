<?php
namespace Flamingo\Modifier;

/**
 * Class BoolvalModifier
 * @package Flamingo\Modifier
 */
class BoolvalModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = boolval($value);
    }
}