<?php

namespace Flamingo\Reader;

use Analog\Analog;
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

        if (empty($filename)) {
            Analog::error(sprintf('%s No filename defined', get_class(CsvReader::class)));
            return null;
        }

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

        Analog::debug(sprintf('Read data from %s - %s', $filename, json_encode($options)));
        return new Table($filename, $data);
    }
}