<?php
namespace Flamingo\Modifier;

/**
 * Class ImplodeModifier
 * @package Flamingo\Modifier
 */
class ImplodeModifier extends AbstractArrayModifier
{
    /**
     * @param array $value
     * @param mixed $delimiter
     */
    protected function processArray(&$value, $delimiter)
    {
        $value = implode($delimiter);
    }
}