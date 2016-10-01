<?php

namespace Flamingo\Writer;

/**
 * Class CsvWriter
 * @package Flamingo\Reader
 */
abstract class CsvWriter
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     */
    public static function write($table, $options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';
    }
}