<?php

namespace Flamingo\Processor\Writer;

use Flamingo\Core\Table;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlWriter
 * @package Flamingo\Processor\Writer
 */
class YamlWriter extends AbstractFileWriter
{
    /**
     * @param Table $table
     * @param array $options
     * @return string
     */
    protected function tableContent(Table $table, array $options)
    {
        return Yaml::dump($table->getArrayCopy());
    }
}