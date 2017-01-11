<?php
namespace Flamingo\Writer;

use Sabre\Xml\Service;

/**
 * Class XmlWriter
 * TODO: Add namespace as table name
 * TODO: Add record tag name option
 *
 * @package Flamingo\Writer
 */
class XmlWriter extends AbstractFileWriter
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     * @return string
     */
    protected function tableContent($table, $options)
    {
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
        return (new Service)->write('{flamingo}root', $data);
    }
}