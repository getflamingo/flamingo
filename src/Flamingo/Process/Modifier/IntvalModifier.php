<?php

namespace Flamingo\Process\Modifier;

/**
 * Class IntvalModifier
 * @package Flamingo\Process\Modifier
 */
class IntvalModifier extends AbstractModifier
{
    /**
     * @param mixed $value
     */
    public function process(&$value)
    {
        $value = intval($value);
    }
}