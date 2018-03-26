<?php

namespace Flamingo\Processor\Writer;

use Flamingo\Core\Table;

/**
 * Interface WriterInterface
 * @package Flamingo\Processor\Writer
 */
interface WriterInterface
{
    /**
     * @param Table $table
     * @param array $options
     */
    public function write(Table $table, array $options);
}