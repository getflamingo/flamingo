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
class Table
{
    /** @var string */
    protected $name;

    /** @var array */
    protected $data;
}