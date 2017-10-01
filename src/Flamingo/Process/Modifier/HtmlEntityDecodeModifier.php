<?php

namespace Flamingo\Modifier;

/**
 * Class HtmlEntityDecodeModifier
 * @package Flamingo\Modifier
 */
class HtmlEntityDecodeModifier implements ModifierInterface
{
    /**
     * @param string $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = html_entity_decode($value);
    }
}