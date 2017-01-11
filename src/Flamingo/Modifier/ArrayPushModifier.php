<?php
namespace Flamingo\Modifier;

/**
 * Class ArrayPushModifier
 * @package Flamingo\Modifier
 */
class ArrayPushModifier extends AbstractArrayModifier
{
    /**
     * @param array $array
     * @param mixed $parameter
     */
    public function processArray(&$array, $parameter)
    {
        array_push($array, $parameter);
    }
}