<?php

namespace Flamingo\Core;

/**
 * Class Process
 *
 * Processes transform data along the task
 * Note: $data is often a reference
 *
 * @package Flamingo\Core
 */
abstract class Process
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * Process constructor.
     * @param array $configuration
     */
    public function __construct($configuration = [])
    {
        $this->configuration = $configuration;
    }

    /**
     * Process data using custom functions
     * TODO: Add return signal for the task
     *
     * @param array $data
     * @return array|int
     */
    abstract public function execute(&$data);
}