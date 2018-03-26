<?php

namespace Flamingo\Writer;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlWriter
 * @package Flamingo\Writer
 */
class YamlWriter extends AbstractFileWriter
{
    /**
     * @return string
     */
    protected function tableContents()
    {
        return Yaml::dump($this->table->getArrayCopy());
    }
}
