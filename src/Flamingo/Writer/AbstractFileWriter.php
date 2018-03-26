<?php

namespace Flamingo\Writer;

use Analog\Analog;
use Flamingo\Table;
use Flamingo\Utility\FileUtility;

/**
 * Class AbstractFileWriter
 * @package Flamingo\Writer
 */
abstract class AbstractFileWriter implements WriterInterface
{
    /**
     * @var Table
     */
    protected $table = null;

    /**
     * @var array
     */
    protected $options = [];

    /**
     * AbstractFileWriter constructor.
     * @param Table $table
     * @param array $options
     */
    public function __construct(Table $table, array $options)
    {
        $this->table = $table;
        $this->options = array_replace($this->options, $options);
    }

    /**
     * Handles errors for writers putting content in file system.
     *
     * @param string $filename
     */
    public function save($filename)
    {
        if (empty($filename)) {
            Analog::error('No filename defined');
        }

        $data = $this->tableContents();
        $filename = FileUtility::getAbsoluteFilename($filename);

        Analog::debug(sprintf('Write data into %s - %s', $filename, json_encode($this->options)));
        file_put_contents($filename, $data);
    }

    /**
     * Encode table data into a readable string to put into destination file.
     *
     * @return string
     */
    protected abstract function tableContents();
}
