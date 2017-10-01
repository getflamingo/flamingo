<?php
namespace Flamingo\Processor;

/**
 * Interface ProcessorInterface
 *
 * Processes transform data along the task
 * Note: $data is often a reference
 *
 * @package Flamingo\Process
 */
interface ProcessorInterface
{
    /**
     * Process data using custom functions
     * TODO: Add return signal for the task
     *
     * @param array <\Flamingo\Model\Table> $data
     * @return array|int
     */
    public function execute(&$data);
}