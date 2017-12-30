<?php

namespace Flamingo\Core;

/**
 * Class TransformRuntime
 * @package Flamingo\Core
 */
class TransformRuntime
{
    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var mixed
     */
    protected $arguments;

    /**
     * @var array
     */
    protected $row;

    /**
     * @var string
     */
    protected $columnName;

    /**
     * TransformRuntime constructor.
     * @param mixed $value
     * @param mixed $arguments
     * @param array $row
     * @param string $columnName
     */
    public function __construct($value, $arguments, array $row, $columnName)
    {
        $this->value = $value;
        $this->arguments = $arguments;
        $this->row = $row;
        $this->columnName = $columnName;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @return array
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param string $columnName
     * @return bool
     */
    public function hasRowColumn($columnName)
    {
        return array_key_exists($columnName, $this->row);
    }

    /**
     * @param string $columnName
     * @return mixed
     */
    public function getRowValue($columnName)
    {
        return $this->row[$columnName];
    }

    /**
     * @param string $columnName
     */
    public function removeRowColumn($columnName)
    {
        unset($this->row[$columnName]);
    }
}