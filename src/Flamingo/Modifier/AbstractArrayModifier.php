<?php
namespace Flamingo\Modifier;

use Analog\Analog;

/**
 * Class AbstractArrayModifier
 * @package Flamingo\Modifier
 */
abstract class AbstractArrayModifier implements ModifierInterface
{
    /**
     * @param array $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        if (!is_array($value)) {
            Analog::error(sprintf('%s: Value is not an array', __CLASS__));
            return;
        }

        $this->processArray($value, $options);
    }

    /**
     * @param array $value
     * @param mixed $options
     */
    abstract protected function processArray(&$value, $options);
}