<?php
namespace Flamingo\Process\Modifier;

/**
 * Interface ModifierInterface
 * @package Flamingo\Process\Modifier
 */
interface ModifierInterface
{
    /**
     * @param mixed $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record);
}