<?php
namespace Flamingo\Process\Modifier;

/**
 * Class SetModifier
 * @package Flamingo\Process\Modifier
 */
class SetModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $newValue
     * @param array $record
     */
    public function process(&$value, $newValue, $record)
    {
        $value = $newValue;
    }
}