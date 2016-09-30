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
     * @param array $data
     */
    public function __construct($name, $data)
    {
        $this->name = $name;
        parent::__construct($data);
    }
}