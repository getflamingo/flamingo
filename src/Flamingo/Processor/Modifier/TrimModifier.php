<?php

namespace Flamingo\Modifier;

/**
 * Class TrimModifier
 * @package Flamingo\Modifier
 */
class TrimModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param string $charList
     * @param array $record
     */
    public function process(&$value, $charList, $record)
    {
        $value = $charList ? trim($value, $charList) : trim($value);
    }
}