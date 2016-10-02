<?php

namespace Flamingo\Reader;

use Flamingo\Model\Table;
use Sabre\Xml\Service;

/**
 * Class XmlReader
 * TODO: Read correct node using options
 * TODO: Convert parser result into a correct Table
 *
 * @package Flamingo\Reader
 */
class XmlReader extends FileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    protected function fileContent($filename, $options)
    {
        // Read data from the file
        $xml = file_get_contents($filename);

        // Parse XML data
        $service = new Service();
        $data = $service->parse($xml);
        $header = [];

        $data = array_map(function ($record) use (&$header) {
            $header += array_column($record['value'], 'name');
            return array_column($record['value'], 'value');
        }, $data);

        return new Table($filename, $header, array_values($data));
    }
}