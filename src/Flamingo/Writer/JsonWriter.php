<?php

namespace Flamingo\Writer;

/**
 * Class JsonWriter
 * @package Flamingo\Reader
 */
abstract class JsonWriter
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     */
    public static function write($table, $options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';

        // Encode data
        $json = json_encode($table);

        // Write output
        file_put_contents($filename, $json);
    }
}