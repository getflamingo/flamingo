<?php
namespace Flamingo\Writer;

/**
 * Interface WriterInterface
 * @package Flamingo\Writer
 */
interface WriterInterface
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     */
    public function write($table, $options);
}