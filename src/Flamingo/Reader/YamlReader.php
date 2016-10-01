<?php

namespace Flamingo\Reader;

use Flamingo\Core\Reader;
use Flamingo\Model\Table;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlReader
 * @package Flamingo\Reader
 */
abstract class YamlReader implements Reader
{
    /**
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    public static function read($options)
    {
        $filename = !empty($options['file']) ? $options['file'] : '';

        $data = Yaml::parse(
            file_get_contents($filename)
        );

        return new Table($filename, $data);
    }
}