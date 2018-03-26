<?php

namespace Flamingo\Reader;

use Flamingo\Table;
use League\Csv\Reader as LCsvReader;

/**
 * Class CsvReader
 * @package Flamingo\Reader
 */
class CsvReader extends AbstractFileReader
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
     * @param string $filename
     * @return Table
     */
    protected function fileContents($filename)
    {
        $firstLineHeader = !empty($this->options['header']) ? $this->options['header'] : true;

        // Read data from the file
        $csv = LCsvReader::createFromPath($filename);

        // Set up controls from options
        $csv->setDelimiter($this->options['delimiter']);
        $csv->setEnclosure($this->options['enclosure']);
        $csv->setEscape($this->options['escape']);
        $csv->setNewline($this->options['newline']);

        // Fetch all lines
        $data = $csv->fetchAll();

        // Use first line as header keys
        $header = $firstLineHeader ? array_shift($data) : [];

        return new Table($header, array_values($data));
    }
}
