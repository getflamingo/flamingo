<?php

namespace Flamingo\Reader;

use Flamingo\Model\Table;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlReader
 * @package Flamingo\Reader
 */
class YamlReader extends FileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    protected function fileContent($filename, $options = [])
    {
        $data = Yaml::parse(file_get_contents($filename));
        $header = count($data) ? array_keys(current($data)) : [];

        return new Table($filename, $header, array_values($data));
    }
}