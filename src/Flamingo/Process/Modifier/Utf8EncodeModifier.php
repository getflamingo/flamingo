<?php
namespace Flamingo\Modifier;

/**
 * Class Utf8EncodeModifier
 * @package Flamingo\Modifier
 */
class Utf8EncodeModifier implements ModifierInterface
{
    /**
     * @param string $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = utf8_encode($value);
    }
}