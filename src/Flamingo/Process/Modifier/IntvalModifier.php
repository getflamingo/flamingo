<?php

namespace Flamingo\Process\Modifier;

/**
 * Class IntvalModifier
 * @package Flamingo\Process\Mutate
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