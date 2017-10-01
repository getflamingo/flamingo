<?php
namespace Flamingo\Reader;

use Flamingo\Model\Table;

/**
 * Class JsonReader
 * @package Flamingo\Reader
 */
class JsonReader extends AbstractFileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    protected function fileContent($filename, $options)
    {
        $data = json_decode(file_get_contents($filename), true);
        $header = count($data) ? array_keys(current($data)) : [];

        return new Table($filename, $header, array_values($data));
    }
}