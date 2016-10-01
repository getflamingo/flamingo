<?php

namespace Flamingo\Writer;

use Flamingo\Core\Writer;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlWriter
 * @package Flamingo\Reader
 */
abstract class YamlWriter implements Writer
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     */
    public static function write($table, $options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';

        // Dump data
        $yaml = Yaml::dump((array)$table);

        // Write file
        file_put_contents($filename, $yaml);
    }
}