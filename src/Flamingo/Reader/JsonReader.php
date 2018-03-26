<?php

namespace Flamingo\Reader;

use Flamingo\Table;

/**
 * Class JsonReader
 * @package Flamingo\Reader
 */
class JsonReader extends AbstractFileReader
{
    /**
     * @param string $filename
     * @return Table
     */
    protected function fileContents($filename)
    {
        $data = json_decode(file_get_contents($filename), true);
        $header = count($data) ? array_keys(current($data)) : [];

        return new Table($header, array_values($data));
    }
}
