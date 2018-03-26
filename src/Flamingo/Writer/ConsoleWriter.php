<?php

namespace Flamingo\Writer;

use Analog\Analog;
use Flamingo\Table;

/**
 * Class ConsoleWriter
 * @package Flamingo\Writer
 */
class ConsoleWriter implements WriterInterface
{
    /**
     * @var Table
     */
    protected $table = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * ConsoleWriter constructor.
     * @param Table $table
     * @param array $options
     */
    public function __construct(Table $table, array $options)
    {
        $this->table = $table;
        $this->options = array_replace($this->options, $options);
    }

    /**
     * @param string $none
     */
    public function save($none = '')
    {
        print_r($this->table->getArrayCopy());
        Analog::info(sprintf('Rows: %d', $this->table->count()));
    }
}
