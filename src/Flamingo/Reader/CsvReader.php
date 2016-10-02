<?php

namespace Flamingo\Reader;

use Flamingo\Model\Table;
use League\Csv\Reader as LCsvReader;

/**
 * Class CsvReader
 * @package Flamingo\Reader
 */
class CsvReader extends FileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    protected function fileContent($filename, $options)
    {
        $firstLineHeader = !empty($options['header']) ? $options['header'] : true;

        // Read data from the file
        $csv = LCsvReader::createFromPath($filename);
        $data = $csv->fetchAll();

        // Use first line as header keys
        $header = $firstLineHeader ? array_shift($data) : [];

        return new Table($filename, $header, array_values($data));
    }
}