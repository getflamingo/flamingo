<?php
namespace Flamingo\Modifier;

use Analog\Analog;

/**
 * Class CopyModifier
 * @package Flamingo\Modifier
 */
class CopyModifier implements ModifierInterface
{
    /**
     * @param mixed $value
     * @param string $column
     * @param array $record
     */
    public function process(&$value, $column, $record)
    {
        if (!array_key_exists($column, $record)) {
            Analog::error(sprintf(
                '%s: No field "%s" found in this record - %s',
                __CLASS__, $column, json_encode(array_keys($record))
            ));
        }

        $value = $record[$column];
    }
}