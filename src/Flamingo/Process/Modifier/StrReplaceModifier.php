<?php
namespace Flamingo\Modifier;

use Analog\Analog;

/**
 * Class StrReplaceModifier
 * @package Flamingo\Modifier
 */
class StrReplaceModifier implements ModifierInterface
{
    /**
     * @param string $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        if (count($options) !== 2) {
            Analog::error('StrReplace: Must have 2 arguments');
            return;
        }

        $value = str_replace($options[0], $options[1], $value);
    }
}