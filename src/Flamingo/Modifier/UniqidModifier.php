<?php
namespace Flamingo\Modifier;

/**
 * Class UniqidModifier
 * @package Flamingo\Modifier
 */
class UniqidModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = uniqid();
    }
}