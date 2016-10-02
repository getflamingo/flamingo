<?php

namespace Flamingo\Process\Modifier;

/**
 * Class ArrayPushModifier
 * @package Flamingo\Process\Modifier
 */
class ArrayPushModifier implements ModifierInterface
{
    /**
     * @param array $value
     * @param mixed $var
     * @param array $record
     */
    public function process(&$value, $var, $record)
    {
        $value = array_push($value, $var);
    }
}