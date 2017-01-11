<?php
namespace Flamingo\Modifier;

/**
 * Interface ModifierInterface
 * @package Flamingo\Modifier
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