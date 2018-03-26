<?php

namespace Flamingo\Writer;

/**
 * Class JsonWriter
 * @package Flamingo\Writer
 */
class JsonWriter extends AbstractFileWriter
{
    /**
     * @return string
     */
    protected function tableContents()
    {
        return json_encode($this->table->getArrayCopy());
    }
}
