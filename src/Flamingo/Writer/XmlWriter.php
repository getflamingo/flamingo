<?php

namespace Flamingo\Writer;

use Sabre\Xml\Service;

/**
 * Class XmlWriter
 * @package Flamingo\Writer
 */
class XmlWriter extends AbstractFileWriter
{
    /**
     * TODO: Add namespace as table name
     * TODO: Add record tag name option
     * @return string
     */
    protected function tableContents()
    {
        // Cast table into classic array
        $data = $this->table->getArrayCopy();

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
