<?php
namespace Flamingo\Modifier;

/**
 * Class IntvalModifier
 * @package Flamingo\Modifier
 */
class IntvalModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = intval($value);
    }
}