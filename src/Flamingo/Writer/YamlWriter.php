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
     * @param \Flamingo\Model\Table $table
     * @param array $options
     * @return string
     */
    protected function tableContent($table, $options)
    {
        return Yaml::dump((array)$table);
    }
}