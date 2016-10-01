<?php

namespace Flamingo\Reader;

use Flamingo\Model\Table;

/**
 * Class JsonReader
 * @package Flamingo\Reader
 */
abstract class JsonReader
{
    /**
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    public static function read($options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';

        // Read data from the file
        $data = file_get_contents($filename);

        return new Table($filename, $data);
    }
}