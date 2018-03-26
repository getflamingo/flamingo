<?php

namespace Flamingo\Processor;

use Flamingo\Table;

/**
 * Interface ProcessorInterface
 * @package Flamingo\Processor
 */
interface ProcessorInterface
{
    /**
     * ProcessorInterface constructor.
     * @param Table $table
     * @param array $options
     */
    public function __construct(Table $table, array $options);
}
