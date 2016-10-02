<?php

namespace Flamingo\Reader;

use Analog\Analog;
use Flamingo\Core\Reader;

/**
 * Class FileReader
 *
 * Handles errors for readers
 * reading data from file system
 *
 * @package Flamingo\Reader
 */
abstract class FileReader implements Reader
{
    /**
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    public function read($options)
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
     * @return \Flamingo\Model\Table
     */
    protected abstract function fileContent($filename, $options);
}