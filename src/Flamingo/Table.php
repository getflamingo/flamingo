<?php

namespace Flamingo;

use Flamingo\Processor\FilterProcessor;
use Flamingo\Processor\MappingProcessor;
use Flamingo\Processor\TransformProcessor;

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
     *
     * @return $this
     */
    public function sanitize()
    {
        $copy = $this->getArrayCopy();

        $cleanArray = array_filter($copy, function ($record) {
            return (is_array($record) && count($record));
        });

        parent::__construct($cleanArray);

        return $this;
    }

    /**
     * Copy an array into object storage.
     *
     * @param array $array
     * @return $this
     */
    public function copy($array)
    {
        parent::__construct($array);

        return $this;
    }

    /**
     * Remap column names of the object.
     *
     * @param array $map
     * @param bool $keepProperties
     * @return $this
     */
    public function map(array $map, $keepProperties = true)
    {
        $options = [
            'map' => $map,
            'keepProperties' => $keepProperties,
        ];

        (new MappingProcessor($this, $options))->run();

        return $this;
    }

    /**
     * Remove values using the filtering processor.
     *
     * @param string $property
     * @param mixed|null $value
     * @param bool $invert
     * @return $this
     */
    public function filter($property, $value = null, $invert = false)
    {
        $options = [
            'property' => $property,
            'value' => $value,
            'invert' => is_null($value) ? true : $invert,
        ];

        (new FilterProcessor($this, $options))->run();

        return $this;
    }

    /**
     * @param array $modifiers
     * @param bool $propertyMustExist
     * @return $this
     */
    public function mod(array $modifiers, $propertyMustExist = false)
    {
        $options = [
            'modifiers' => $modifiers,
            'propertyMustExist' => $propertyMustExist,
        ];

        (new TransformProcessor($this, $options))->run();

        return $this;
    }
}
