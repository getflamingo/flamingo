<?php

namespace Flamingo\Processor;

use Flamingo\Core\TaskRuntime;

/**
 * Class AbstractProcessor
 * @package Flamingo\Processor
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var mixed
     */
    protected $configuration;

    /**
     * AbstractProcessor constructor.
     * @param mixed $configuration
     */
    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Process data tables using custom functions
     *
     * @param TaskRuntime $taskRuntime
     * @return TaskRuntime
     */
    abstract public function execute(TaskRuntime &$taskRuntime);
}