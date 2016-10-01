<?php

namespace Flamingo\Process\Modifier;

/**
 * Class AbstractModifier
 * @package Flamingo\Process\Modifier
 */
abstract class AbstractModifier
{
    /**
     * @param mixed $value
     * @param array $options
     * @param array $record
     */
    public function process(&$value, $options = [], &$record = []) {
        return;
    }
}