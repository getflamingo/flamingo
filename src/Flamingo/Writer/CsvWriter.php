<?php

namespace Flamingo\Writer;

use League\Csv\Writer as LCsvWriter;

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
        // Cast into array
        $data = (array)$table;

        // Create writer
        $writer = LCsvWriter::createFromFileObject(new \SplTempFileObject());

        // Add header
        if (count($data)) {
            $writer->insertOne(array_keys(current($data)));
        }

        // Insert the data
        $writer->insertAll($data);

        // Return document content
        return $writer;
    }
}