<?php

namespace Flamingo\Processor;

use Flamingo\Table;
use Flamingo\TaskRuntime;

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
     * TODO: Add configuration as argument
     *
     * @param TaskRuntime $taskRuntime
     * @return mixed
     */
    abstract public function execute(TaskRuntime $taskRuntime);
}
