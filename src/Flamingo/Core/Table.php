<?php

namespace Flamingo\Core;

/**
 * Class Table
 * @package Flamingo\Core
 */
class Table extends \ArrayIterator implements \Traversable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Table constructor.
     * @param string $name
     * @param array $columns
     * @param array $records
     */
    public function __construct($name = null, array $columns = [], array $records = [])
    {
        $this->name = $name;

        if (count($columns) * count($records) > 0) {

            // Add keys to $records
            foreach ($records as &$record) {
                $record = array_combine($columns, $record);
            }

            parent::__construct($records);
        }
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Remove null and empty values
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
     * Copy an array into object storage
     * @param array $array
     */
    public function copy($array)
    {
        parent::__construct($array);
    }
}