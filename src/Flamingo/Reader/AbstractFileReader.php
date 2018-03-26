<?php

namespace Flamingo\Reader;

use Analog\Analog;
use Flamingo\Exception\RuntimeException;
use Flamingo\Table;
use Flamingo\Utility\FileUtility;

/**
 * Class AbstractFileReader
 * @package Flamingo\Reader
 */
abstract class AbstractFileReader implements ReaderInterface
{
    /**
     * @var array
     */
    protected $options = [];

    /**
     * AbstractFileReader constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = array_replace($this->options, $options);
    }

    /**
     * Handles errors for parsers reading data from file system.
     *
     * @param string $filename
     * @param array $options
     * @return Table
     * @throws RuntimeException
     */
    public function load($filename, array $options = [])
    {
        if (empty($filename)) {
            throw new RuntimeException('No filename defined');
        }

        $filename = FileUtility::getAbsoluteFilename($filename);

        if (!file_exists($filename)) {
            throw new RuntimeException(sprintf('The file "%s" does not exist', $filename));
        }

        $table = $this->fileContents($filename);
        Analog::debug(sprintf('Read data from %s - %s', $filename, json_encode($options)));

        return $table;
    }

    /**
     * Read and return formatted file content.
     *
     * @param string $filename
     * @return Table
     */
    protected abstract function fileContents($filename);
}
