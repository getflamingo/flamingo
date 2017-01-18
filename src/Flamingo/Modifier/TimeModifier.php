<?php
namespace Flamingo\Modifier;

/**
 * Class TimeModifier
 * @package Flamingo\Modifier
 */
class TimeModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = time();
    }
}