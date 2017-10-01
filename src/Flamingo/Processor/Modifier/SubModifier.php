<?php
namespace Flamingo\Modifier;

/**
 * Class SubModifier
 * @package Flamingo\Modifier
 */
class SubModifier extends AbstractNumberModifier
{
    /**
     * @param number $value
     * @param mixed $amount
     */
    protected function processNumber(&$value, $amount)
    {
        $value -= is_numeric($amount) ? $amount : 0;
    }
}