<?php

namespace Flamingo\Processor\Reader;

use Flamingo\Core\Table;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlReader
 * @package Flamingo\Processor\Reader
 */
class YamlReader extends AbstractFileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return Table
     */
    protected function fileContent($filename, array $options)
    {
        $data = Yaml::parse(file_get_contents($filename));
        $header = count($data) ? array_keys(current($data)) : [];

        return new Table($filename, $header, array_values($data));
    }
}