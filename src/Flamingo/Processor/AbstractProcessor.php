<?php
namespace Flamingo\Processor;

/**
 * Class AbstractProcessor
 * @package Flamingo\Process
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    /**
     * @var mixed
     */
    protected $configuration;

    /**
     * AbstractProcessor constructor.
     * @param $configuration
     */
    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @param <\Flamingo\Model\Table> $data
     * @return int
     */
    abstract public function execute(&$data);
}