<?php

namespace Flamingo\Processor\Reader;

use Analog\Analog;
use Flamingo\Core\Table;
use Flamingo\Utility\FileUtility;

/**
 * Class AbstractFileReader
 * @package Flamingo\Processor\Reader
 */
abstract class AbstractFileReader implements ReaderInterface
{
    /**
     * Handles errors for parsers reading data from file system
     *
     * @param array $options
     * @return Table
     */
    public function read(array $options)
    {
        if (empty($options['file'])) {
            Analog::error(sprintf('No filename defined - %s', json_encode($options)));
            return null;
        }

        $filename = FileUtility::getAbsoluteFilename($options['file']);

        if (!file_exists($filename)) {
            Analog::error(sprintf('The file "%s" does not exist', $filename));
        }

        $table = $this->fileContent($filename, $options);

        Analog::debug(sprintf('Read data from %s - %s', $filename, json_encode($options)));
        return $table;
    }

    /**
     * Read and return formatted file content
     *
     * @param string $filename
     * @param array $options
     * @return Table
     */
    protected abstract function fileContent($filename, array $options);
}