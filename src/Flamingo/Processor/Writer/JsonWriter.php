<?php

namespace Flamingo\Processor\Writer;

use Flamingo\Core\Table;

/**
 * Class JsonWriter
 * @package Flamingo\Processor\Writer
 */
class JsonWriter extends AbstractFileWriter
{
    /**
     * @param Table $table
     * @param array $options
     * @return string
     */
    protected function tableContent(Table $table, array $options)
    {
        return json_encode($table->getArrayCopy());
    }
}