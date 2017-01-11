<?php
namespace Flamingo\Process\Modifier;

/**
 * Class MultModifier
 * @package Flamingo\Process\Modifier
 */
class MultModifier implements ModifierInterface
{
    /**
     * @param double $value
     * @param double $amount
     * @param array $record
     */
    public function process(&$value, $amount, $record)
    {
        $value *= is_numeric($amount) ? $amount : 1;
    }
}