<?php

namespace Flamingo\Reader;

use Flamingo\Table;

/**
 * Class XmlReader
 * @package Flamingo\Reader
 */
class XmlReader extends AbstractFileReader
{
    /**
     * @var array
     */
    protected $options = [
        'path' => null,
        'nocdata' => true,
    ];

    /**
     * @param string $filename
     * @return Table
     */
    protected function fileContents($filename)
    {
        // Read data from the file and hide namespace
        $xml = file_get_contents($filename);
        $xml = str_replace(' xmlns=', ' ns=', $xml);

        // List of LIBXML parameters
        $parameters = 0;

        // Trim CDATA tags
        if ($this->options['nocdata']) {
            $parameters += LIBXML_NOCDATA;
        }

        // Create document from file data
        $document = new \SimpleXMLElement($xml, $parameters);

        // Use path if defined
        $data = $this->options['path']
            ? $document->xpath($this->options['path'])
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

        return new Table($header, $values);
    }
}
