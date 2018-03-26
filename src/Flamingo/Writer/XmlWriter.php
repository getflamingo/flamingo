<?php

namespace Flamingo\Processor\Writer;

use Flamingo\Core\Table;
use Sabre\Xml\Service;

/**
 * Class XmlWriter
 * TODO: Add namespace as table name
 * TODO: Add record tag name option
 *
 * @package Flamingo\Processor\Writer
 */
class XmlWriter extends AbstractFileWriter
{
    /**
     * @param Table $table
     * @param array $options
     * @return string
     */
    protected function tableContent(Table $table, array $options)
    {
        // Cast table into classic array
        $data = $table->getArrayCopy();

        // Convert data in correct XML format
        foreach ($data as &$record) {
            $record = [
                'name' => 'item',
                'value' => $record,
            ];
        }

        // Encode data
        return (new Service)->write('{flamingo}root', $data);
    }
}