<?php

namespace Flamingo\Reader;

use Flamingo\Core\Reader;
use Flamingo\Model\Table;

/**
 * Class CsvReader
 * @package Flamingo\Reader
 */
abstract class CsvReader implements Reader
{
    /**
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    public static function read($options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';
        $firstLineHeader = !empty($options['header']) ? $options['header'] : true;

        // Read data from the file
        $csv = \League\Csv\Reader::createFromPath($filename);
        $data = $csv->fetchAll();

        // Use first line as header keys
        $header = $firstLineHeader ? array_shift($data) : [];

        // Add keys to data records
        if (count($header)) {
            foreach ($data as &$record) {
                $record = array_combine($header, $record);
            }
        }

        return new Table($filename, $data);
    }
}