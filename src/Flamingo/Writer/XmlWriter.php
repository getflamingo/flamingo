<?php

namespace Flamingo\Writer;

use Flamingo\Core\Writer;
use Sabre\Xml\Service;

/**
 * Class XmlWriter
 * TODO: Add namespace as table name
 * TODO: Add record tag name option
 *
 * @package Flamingo\Reader
 */
abstract class XmlWriter implements Writer
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     */
    public static function write($table, $options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';

        // Cast table into classic array
        $data = (array)$table;

        // Convert data in correct XML format
        foreach ($data as &$record) {
            $record = [
                'name' => 'item',
                'value' => $record,
            ];
        }

        // Encode data
        $xml = (new Service)->write('{flamingo}root', $data);

        // Write output
        file_put_contents($filename, $xml);
    }
}