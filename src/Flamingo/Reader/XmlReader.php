<?php

namespace Flamingo\Reader;

use Flamingo\Model\Table;

/**
 * Class XmlReader
 * @package Flamingo\Reader
 */
class XmlReader extends AbstractFileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    protected function fileContent($filename, $options)
    {
        $defaultOptions = [
            'path' => null,
        ];

        $options = array_replace($defaultOptions, $options);

        // Read data from the file
        $xml = file_get_contents($filename);

        // Hide XML namespace
        $xml = str_replace(' xmlns=', ' ns=', $xml);

        // Create document from file data
        $document = new \SimpleXMLElement($xml);

        // Use path if defined
        $data = $options['path']
            ? $document->xpath($options['path'])
            : $document->children();

        // Cast to array
        $data = json_decode(json_encode($data), true);

        // Get first element keys
        $header = array_keys(current($data));

        // Retrieve the maximum number of columns
        foreach ($data as $record) {
            $header = array_unique(array_merge($header, array_keys($record)));
        }

        // Create an empty dummy record
        $dummyRecord = array_flip($header);
        $dummyRecord = array_map(function () {
            return null;
        }, $dummyRecord);

        // Add missing columns to records
        $values = array_map(function ($record) use ($dummyRecord) {
            return array_replace($dummyRecord, $record);
        }, $data);

        return new Table($filename, $header, $values);
    }
}