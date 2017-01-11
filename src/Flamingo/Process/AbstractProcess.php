<?php
namespace Flamingo\Process;

/**
 * Class AbstractProcess
 * @package Flamingo\Process
 */
abstract class AbstractProcess implements ProcessInterface
{
    /**
     * @var mixed
     */
    protected $configuration;

    /**
     * AbstractProcess constructor.
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