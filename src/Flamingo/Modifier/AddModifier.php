<?php
namespace Flamingo\Modifier;

/**
 * Class AddModifier
 * @package Flamingo\Modifier
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