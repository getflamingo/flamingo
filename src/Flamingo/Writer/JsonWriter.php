<?php

namespace Flamingo\Writer;

/**
 * Class JsonWriter
 * @package Flamingo\Writer
 */
class JsonWriter extends FileWriter
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     * @return string
     */
    protected function tableContent($table, $options)
    {
        return json_encode((array)$table);
    }
}