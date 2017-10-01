<?php
namespace Flamingo\Processor;

/**
 * Class FunctionProcess
 * @package Flamingo\Process
 */
class FunctionProcess extends AbstractProcessor
{
    /**
     * FunctionProcess constructor.
     * @param array $configuration
     */
    public function __construct($configuration)
    {
        if (is_string($configuration)) {
            $configuration = [$configuration];
        }

        parent::__construct($configuration);
    }

    /**
     * Call needed user functions
     *
     * @param array $data
     * @return int
     */
    public function execute(&$data)
    {
        foreach ($this->configuration as $callable) {

            // Replace arrow to static call
            $callable = str_replace('->', '::', $callable);

            // Call function if it exists
            if (strpos($callable, '::') || function_exists($callable)) {
                call_user_func($callable, $data);
            }
        }
    }
}