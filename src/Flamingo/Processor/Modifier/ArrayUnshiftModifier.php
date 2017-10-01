<?php
namespace Flamingo\Modifier;

/**
 * Class ArrayUnshiftModifier
 * @package Flamingo\Modifier
 */
class ArrayUnshiftModifier extends AbstractArrayModifier
{
    /**
     * @param array $value
     * @param mixed $parameter
     */
    public function processArray(&$value, $parameter)
    {
        array_unshift($value, $parameter);
    }
}