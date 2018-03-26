<?php

namespace Flamingo\Processor\Reader;

use Flamingo\Core\Table;
use League\Csv\Reader as LCsvReader;

/**
 * Class CsvReader
 * @package Flamingo\Processor\Reader
 */
class CsvReader extends AbstractFileReader
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'delimiter' => ',',
        'enclosure' => '"',
        'escape' => '\\',
        'newline' => "\n",
    ];

    /**
     * @param string $filename
     * @param array $options
     * @return Table
     */
    protected function fileContent($filename, array $options)
    {
        // Overwrite default options
        $options = array_replace($this->defaultOptions, $options);

        $firstLineHeader = !empty($options['header']) ? $options['header'] : true;

        // Read data from the file
        $csv = LCsvReader::createFromPath($filename);

        // Set up controls from options
        $csv->setDelimiter($options['delimiter']);
        $csv->setEnclosure($options['enclosure']);
        $csv->setEscape($options['escape']);
        $csv->setNewline($options['newline']);

        // Fetch all lines
        $data = $csv->fetchAll();

        // Use first line as header keys
        $header = $firstLineHeader ? array_shift($data) : [];

        return new Table($filename, $header, array_values($data));
    }
}