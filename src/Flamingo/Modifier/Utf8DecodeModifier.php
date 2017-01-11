<?php
namespace Flamingo\Modifier;

/**
 * Class Utf8DecodeModifier
 * @package Flamingo\Modifier
 */
class Utf8DecodeModifier implements ModifierInterface
{
    /**
     * @param string $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = utf8_decode($value);
    }
}