<?php

namespace Flamingo\Reader;

use Flamingo\Model\Table;

/**
 * Class JsonReader
 * @package Flamingo\Reader
 */
class JsonReader extends FileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    protected function fileContent($filename, $options)
    {
        $data = file_get_contents($filename);
        $header = count($data) ? array_keys(current($data)) : [];

        return new Table($filename, $header, array_values($data));
    }
}