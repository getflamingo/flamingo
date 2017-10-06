<?php

namespace Flamingo\Processor\Reader;

use Analog\Analog;
use Flamingo\Core\Table;

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

        if (!file_exists($options['file'])) {
            Analog::error(sprintf('The file "%s" does not exist', $options['file']));
        }

        $table = $this->fileContent($options['file'], $options);

        Analog::debug(sprintf('Read data from %s - %s', $options['file'], json_encode($options)));
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