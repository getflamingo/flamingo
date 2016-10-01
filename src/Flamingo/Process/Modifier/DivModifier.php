<?php

namespace Flamingo\Process\Modifier;

/**
 * Class DivModifier
 * @package Flamingo\Process\Modifier
 */
class DivModifier extends AbstractModifier
{
    /**
     * @param double $value
     * @param double $amount
     */
    public function process(&$value, $amount = 1.0)
    {
        $value /= $amount;
    }
}