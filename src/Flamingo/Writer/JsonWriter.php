<?php

namespace Flamingo\Writer;

use Flamingo\Core\Writer;

/**
 * Class JsonWriter
 * @package Flamingo\Reader
 */
abstract class JsonWriter implements Writer
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     */
    public static function write($table, $options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';

        // Encode data
        $json = json_encode((array)$table);

        // Write output
        file_put_contents($filename, $json);
    }
}