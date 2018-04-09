<?php

namespace Flamingo;

use Flamingo\Exception\RuntimeException;

/**
 * Class Task
 * @package Flamingo
 */
abstract class Task
{
    /**
     * Read a source and determines the adapted reader according to target type (if a filename).
     *
     * @param string $filename
     * @param array $options
     * @param string $readerType
     * @return Table
     */
    protected function read($filename, array $options = [], $readerType = '')
    {
        return Table::read($filename, $options, $readerType);
    }

    /**
     * Output the table data to a filename.
     *
     * @param Table $table
     * @param string $filename
     * @param array $options
     * @param string $writerType
     */
    protected function write(Table $table, $filename, array $options = [], $writerType = '')
    {
        $table->write($filename, $options, $writerType);
    }

    /**
     * Check if the configuration version is compatible with the current flamingo version.
     *
     * @param string $versions
     * @throws RuntimeException
     */
    protected function checkVersion($versions)
    {
        $versions = explode('-', $versions);

        if (strlen($versions[0]) && version_compare('@git-version@', $versions[0], '<')) {
            throw new RuntimeException(
                'The Flamingo version is too low (current: %s, needed: %s)',
                '@git-version@',
                $versions[0]
            );
        }

        if (strlen($versions[1]) && version_compare('@git-version@', $versions[1], '>')) {
            throw new RuntimeException(
                'The Flamingo version is too high (current: %s, needed: %s)',
                '@git-version@',
                $versions[1]
            );
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
}
