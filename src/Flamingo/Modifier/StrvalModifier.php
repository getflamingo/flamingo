<?php
namespace Flamingo\Modifier;

/**
 * Class StrvalModifier
 * @package Flamingo\Modifier
 */
class StrvalModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = strval($value);
    }
}