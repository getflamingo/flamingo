<?php
namespace Flamingo\Modifier;

/**
 * Class StrtoupperModifier
 * @package Flamingo\Modifier
 */
class StrtoupperModifier implements ModifierInterface
{
    /**
     * @param string $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = strtoupper($value);
    }
}