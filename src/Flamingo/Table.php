<?php

namespace Flamingo;

use Flamingo\Processor\FilterProcessor;
use Flamingo\Processor\MappingProcessor;

/**
 * Class Table
 * @package Flamingo
 */
class Table extends \ArrayIterator implements \Traversable
{
    /**
     * Table constructor.
     * @param array $columns
     * @param array $records
     */
    public function __construct(array $columns = [], array $records = [])
    {
        if (count($columns) * count($records) > 0) {

            // Add keys to $records
            foreach ($records as &$record) {
                $record = array_combine($columns, $record);
            }

            parent::__construct($records);
        }
    }

    /**
     * Remove null and empty values.
     */
    public function sanitize()
    {
        $copy = $this->getArrayCopy();

        $cleanArray = array_filter($copy, function ($record) {
            return (is_array($record) && count($record));
        });

        parent::__construct($cleanArray);
    }

    /**
     * Copy an array into object storage.
     *
     * @param array $array
     */
    public function copy($array)
    {
        parent::__construct($array);
    }

    /**
     * Remap column names of the object.
     *
     * @param array $map
     * @param bool $keepProperties
     */
    public function map(array $map, $keepProperties = true)
    {
        $options = [
            'map' => $map,
            'keepProperties' => $keepProperties,
        ];

        (new MappingProcessor($this, $options))->run();
    }

    /**
     * Remove values using the filtering processor.
     *
     * @param string $property
     * @param mixed|null $value
     * @param bool $invert
     */
    public function filter($property, $value = null, $invert = true)
    {
        $options = [
            'property' => $property,
            'value' => $value,
            'invert' => $invert,
        ];

        (new FilterProcessor($this, $options))->run();
    }
}
