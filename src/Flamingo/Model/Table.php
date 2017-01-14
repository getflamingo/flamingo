<?php

namespace Flamingo\Model;

/**
 * Class Table
 *
 * A table is formed with one source
 * This source can have multiple types:
 *  - CSV, JSON, dbTable, XLS, etc...
 *
 * @package Flamingo\Model
 */
class Table extends \ArrayIterator
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
    public function __construct($name = null, $columns = [], $records = [])
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