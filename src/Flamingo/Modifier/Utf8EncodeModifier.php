<?php
namespace Flamingo\Process\Modifier;

/**
 * Class Utf8EncodeModifier
 * @package Flamingo\Process\Modifier
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