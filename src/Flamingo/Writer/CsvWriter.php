<?php

namespace Flamingo\Writer;

use League\Csv\Writer as LCsvWriter;

/**
 * Class CsvWriter
 * @package Flamingo\Writer
 */
class CsvWriter extends AbstractFileWriter
{
    /**
     * @var array
     */
    protected $options = [
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\n",
    ];

    /**
     * @return string
     */
    protected function tableContents()
    {
        // Cast into array
        $data = $this->table->getArrayCopy();

        // Create writer
        $writer = LCsvWriter::createFromFileObject(new \SplTempFileObject());

        // Set up writer controls from options
        $writer->setDelimiter($this->options['delimiter']);
        $writer->setEnclosure($this->options['enclosure']);
        $writer->setEscape($this->options['escape']);
        $writer->setNewline($this->options['newline']);

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
