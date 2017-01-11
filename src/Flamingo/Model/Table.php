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
    public function __construct($name, $columns, $records)
    {
        $this->name = $name;

        // Add keys to $records
        foreach ($records as &$record) {
            $record = array_combine($columns, $record);
        }

        parent::__construct($records);
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
}