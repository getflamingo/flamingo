<?php
namespace Flamingo\Modifier;

/**
 * Class ExplodeModifier
 * @package Flamingo\Modifier
 */
class ExplodeModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $delimiter
     * @param array $record
     */
    public function process(&$value, $delimiter, $record)
    {
        $value = explode($delimiter, $value);
    }
}