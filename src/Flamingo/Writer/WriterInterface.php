<?php

namespace Flamingo\Writer;

use Flamingo\Processor\ProcessorInterface;
use Flamingo\Table;

/**
 * Interface WriterInterface
 * @package Flamingo\Writer
 */
interface WriterInterface extends ProcessorInterface
{
    /**
     * WriterInterface constructor.
     * @param Table $table
     * @param array $options
     */
    public function __construct(Table $table, array $options);

    /**
     * @param string $target
     */
    public function save($target);
}
