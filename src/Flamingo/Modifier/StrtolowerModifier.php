<?php
namespace Flamingo\Modifier;

/**
 * Class StrtolowerModifier
 * @package Flamingo\Modifier
 */
class StrtolowerModifier implements ModifierInterface
{
    /**
     * @param string $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = strtolower($value);
    }
}