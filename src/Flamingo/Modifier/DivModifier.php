<?php
namespace Flamingo\Modifier;

/**
 * Class DivModifier
 * @package Flamingo\Modifier
 */
class DivModifier extends AbstractNumberModifier
{
    /**
     * @param number $value
     * @param mixed $amount
     */
    protected function processNumber(&$value, $amount)
    {
        $value /= is_numeric($amount) ? $amount : 1;
    }
}