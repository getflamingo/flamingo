<?php

namespace Flamingo\Writer;

use Flamingo\Core\Writer;

/**
 * Class CsvWriter
 * @package Flamingo\Reader
 */
abstract class CsvWriter implements Writer
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