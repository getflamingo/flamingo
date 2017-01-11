<?php
namespace Flamingo\Process\Modifier;

/**
 * Class IntvalModifier
 * @package Flamingo\Process\Modifier
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