<?php
namespace Flamingo\Modifier;

/**
 * Class ArrayPopModifier
 * @package Flamingo\Modifier
 */
class ArrayPopModifier extends AbstractArrayModifier
{
    /**
     * @param array $value
     * @param mixed $options
     */
    public function processArray(&$value, $options)
    {
        $value = array_pop($value);
    }
}