<?php
namespace Flamingo\Reader;

use Flamingo\Model\Table;
use League\Csv\Reader as LCsvReader;

/**
 * Class CsvReader
 * @package Flamingo\Reader
 */
class CsvReader extends AbstractFileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    protected function fileContent($filename, $options)
    {
        $defaultOptions = [
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
            'newline' => "\n",
        ];

        // Overwrite default options
        $options = array_replace($defaultOptions, $options);

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