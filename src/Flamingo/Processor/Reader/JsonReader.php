<?php

namespace Flamingo\Processor\Reader;

use Flamingo\Core\Table;

/**
 * Class JsonReader
 * @package Flamingo\Processor\Reader
 */
class JsonReader extends AbstractFileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return Table
     */
    protected function fileContent($filename, array $options)
    {
        $data = json_decode(file_get_contents($filename), true);
        $header = count($data) ? array_keys(current($data)) : [];

        return new Table($filename, $header, array_values($data));
    }
}