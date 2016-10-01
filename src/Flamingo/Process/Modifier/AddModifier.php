<?php

namespace Flamingo\Process\Modifier;

/**
 * Class AddModifier
 * @package Flamingo\Process\Modifier
 */
class AddModifier extends AbstractModifier
{
    /**
     * @param double $value
     * @param double $amount
     */
    public function process(&$value, $amount = 0.0)
    {
        $value += $amount;
    }
}