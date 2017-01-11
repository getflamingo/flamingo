<?php
namespace Flamingo\Modifier;

/**
 * Class DivModifier
 * @package Flamingo\Modifier
 */
class DivModifier implements ModifierInterface
{
    /**
     * @param double $value
     * @param double $amount
     * @param array $record
     */
    public function process(&$value, $amount, $record)
    {
        $value /= is_numeric($amount) ? $amount : 1;
    }
}