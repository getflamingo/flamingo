<?php
namespace Flamingo\Modifier;

/**
 * Class ArrayShiftModifier
 * @package Flamingo\Modifier
 */
class ArrayShiftModifier extends AbstractArrayModifier
{
    /**
     * @param array $value
     * @param mixed $options
     */
    protected function processArray(&$value, $options)
    {
        $value = array_shift($value);
    }
}