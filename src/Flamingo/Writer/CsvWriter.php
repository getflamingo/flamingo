<?php

namespace Flamingo\Writer;

/**
 * Class CsvWriter
 * @package Flamingo\Writer
 */
class CsvWriter extends FileWriter
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     * @return string
     */
    protected function tableContent($table, $options)
    {
        return '';
    }
}