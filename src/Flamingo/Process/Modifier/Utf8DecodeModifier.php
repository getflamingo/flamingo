<?php

namespace Flamingo\Process\Modifier;

/**
 * Class Utf8DecodeModifier
 * @package Flamingo\Process\Modifier
 */
class Utf8DecodeModifier extends AbstractModifier
{
    /**
     * @param string $value
     */
    public function process(&$value)
    {
        $value = utf8_decode($value);
    }
}