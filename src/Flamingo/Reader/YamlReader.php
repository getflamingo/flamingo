<?php

namespace Flamingo\Reader;

use Flamingo\Table;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlReader
 * @package Flamingo\Reader
 */
class YamlReader extends AbstractFileReader
{
    /**
     * @param string $filename
     * @return Table
     */
    protected function fileContents($filename)
    {
        $data = Yaml::parse(file_get_contents($filename));
        $header = count($data) ? array_keys(current($data)) : [];

        return new Table($header, array_values($data));
    }
}
