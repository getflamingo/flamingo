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
        $defaultOptions = [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
            'newline' => "\n",
        ];

        // Overwrite default options
        $options = array_replace($defaultOptions, $options);

        // Cast into array
        $data = (array)$table;

        // Create writer
        $writer = LCsvWriter::createFromFileObject(new \SplTempFileObject());

        // Set up writer controls from options
        $writer->setDelimiter($options['delimiter']);
        $writer->setEnclosure($options['enclosure']);
        $writer->setEscape($options['escape']);
        $writer->setNewline($options['newline']);

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