<?php

namespace Flamingo\Process\Modifier;

/**
 * Class Utf8EncodeModifier
 * @package Flamingo\Process\Modifier
 */
class Utf8EncodeModifier extends AbstractModifier
{
    /**
     * @param string $value
     */
    public function process(&$value)
    {
        $value = utf8_encode($value);
    }
}