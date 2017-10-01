<?php
namespace Flamingo\Modifier;

/**
 * Class Sha1Modifier
 * @package Flamingo\Modifier
 */
class Sha1Modifier implements ModifierInterface
{
    /**
     * @param string $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        $value = sha1($value);
    }
}