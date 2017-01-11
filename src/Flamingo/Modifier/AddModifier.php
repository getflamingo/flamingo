<?php
namespace Flamingo\Modifier;

/**
 * Class AddModifier
 * @package Flamingo\Modifier
 */
class AddModifier extends AbstractNumberModifier
{
    /**
     * @param number $value
     * @param mixed $amount
     */
    protected function processNumber(&$value, $amount)
    {
        $value += is_numeric($amount) ? $amount : 0;
    }
}