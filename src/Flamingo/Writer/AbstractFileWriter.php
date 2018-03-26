<?php

namespace Flamingo\Processor\Writer;

use Analog\Analog;
use Flamingo\Core\Table;
use Flamingo\Utility\FileUtility;

/**
 * Class AbstractFileWriter
 * @package Flamingo\Processor\Writer
 */
abstract class AbstractFileWriter implements WriterInterface
{
    /**
     * Handles errors for writers putting content in file system
     *
     * @param Table $table
     * @param array $options
     */
    public function write(Table $table, array $options)
    {
        if (empty($options['file'])) {
            Analog::error(sprintf('No filename defined - %s', json_encode($options)));
        }

        $data = $this->tableContent($table, $options);

        $filename = FileUtility::getAbsoluteFilename($options['file']);
        Analog::debug(sprintf('Write data into %s - %s', $filename, json_encode($options)));
        file_put_contents($filename, $data);
    }

    /**
     * Encode table data into a readable string to put into destination file
     *
     * @param Table $table
     * @param array $options
     * @return string
     */
    protected abstract function tableContent(Table $table, array $options);
}