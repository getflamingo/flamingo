<?php

namespace Flamingo\Process\Modifier;

/**
 * Class AddModifier
 * @package Flamingo\Process\Modifier
 */
class AddModifier implements ModifierInterface
{
    /**
     * @param double $value
     * @param double $amount
     * @param array $record
     */
    public function process(&$value, $amount, $record)
    {
        $value += is_numeric($amount) ? $amount : 0;
    }
}