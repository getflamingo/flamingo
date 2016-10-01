<?php

namespace Flamingo\Process\Modifier;

/**
 * Class SubModifier
 * @package Flamingo\Process\Modifier
 */
class SubModifier extends AbstractModifier
{
    /**
     * @param double $value
     * @param double $amount
     */
    public function process(&$value, $amount = 0.0)
    {
        $value -= $amount;
    }
}