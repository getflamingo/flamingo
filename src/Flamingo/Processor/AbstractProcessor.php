<?php

namespace Flamingo\Processor;

use Flamingo\Table;

/**
 * Class AbstractProcessor
 * @package Flamingo\Processor
 */
abstract class AbstractProcessor implements ProcessorInterface
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
     * AbstractProcessor constructor.
     * @param Table $table
     * @param array $options
     */
    public function __construct(Table $table, array $options)
    {
        $this->table = $table;
        $this->options = array_replace($this->options, $options);
    }

    /**
     * Process data tables using custom functions.
     */
    abstract public function run();
}
