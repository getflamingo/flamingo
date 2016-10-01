<?php

namespace Flamingo\Process\Modifier;

/**
 * Class MultModifier
 * @package Flamingo\Process\Modifier
 */
class MultModifier extends AbstractModifier
{
    /**
     * @param double $value
     * @param double $amount
     */
    public function process(&$value, $amount = 1.0)
    {
        $value *= $amount;
    }
}