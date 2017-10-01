<?php
namespace Flamingo\Modifier;

/**
 * Class MultModifier
 * @package Flamingo\Modifier
 */
class MultModifier extends AbstractNumberModifier
{
    /**
     * @param number $value
     * @param mixed $amount
     */
    protected function processNumber(&$value, $amount)
    {
        $value *= is_numeric($amount) ? $amount : 1;
    }
}