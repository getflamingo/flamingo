<?php
namespace Flamingo\Modifier;

/**
 * Class SubModifier
 * @package Flamingo\Modifier
 */
class SubModifier implements ModifierInterface
{
    /**
     * @param double $value
     * @param double $amount
     * @param array $record
     */
    public function process(&$value, $amount, $record)
    {
        $value -= is_numeric($amount) ? $amount : 0;
    }
}