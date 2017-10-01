<?php
namespace Flamingo\Modifier;

use Analog\Analog;

/**
 * Class AbstractNumberModifier
 * @package Flamingo\Modifier
 */
abstract class AbstractNumberModifier implements ModifierInterface
{
    /**
     * @param number $value
     * @param mixed $options
     * @param array $record
     */
    public function process(&$value, $options, $record)
    {
        if (!is_numeric($value)) {
            Analog::error(sprintf('%s: %s is not a number', __CLASS__, strval($value)));
            return;
        }

        $this->processNumber($value, $options);
    }

    /**
     * @param number $value
     * @param mixed $options
     */
    abstract protected function processNumber(&$value, $options);
}