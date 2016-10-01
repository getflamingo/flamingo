<?php

namespace Flamingo\Reader;

use Flamingo\Core\Reader;
use Flamingo\Model\Table;
use Sabre\Xml\Service;

/**
 * Class XmlReader
 * TODO: Read correct node using options
 * TODO: Convert parser result into a correct Table
 *
 * @package Flamingo\Reader
 */
abstract class XmlReader implements Reader
{
    /**
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    public static function read($options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';

        // Read data from the file
        $xml = file_get_contents($filename);

        // Parse XML data
        $service = new Service();
        $data = $service->parse($xml);

        return new Table($filename, $data);
    }
}