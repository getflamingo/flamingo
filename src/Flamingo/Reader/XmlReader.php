<?php

namespace Flamingo\Processor\Reader;

use Flamingo\Core\Table;

/**
 * Class XmlReader
 * @package Flamingo\Processor\Reader
 */
class XmlReader extends AbstractFileReader
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'path' => null,
        'nocdata' => true,
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

        // Read data from the file and hide namespace
        $xml = file_get_contents($filename);
        $xml = str_replace(' xmlns=', ' ns=', $xml);

        // List of LIBXML parameters
        $parameters = 0;

        // Trim CDATA tags
        if ($options['nocdata']) {
            $parameters += LIBXML_NOCDATA;
        }

        // Create document from file data
        $document = new \SimpleXMLElement($xml, $parameters);

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