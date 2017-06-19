<?php

namespace Flamingo\Modifier;

/**
 * Class ArrayGetModifier
 * @package Flamingo\Modifier
 */
class ArrayGetModifier extends AbstractArrayModifier
{
    /**
     * @param array $value
     * @param mixed $key
     */
    protected function processArray(&$value, $key)
    {
        $value = array_key_exists($key, $value) ? $value[$key] : null;
    }
}