<?php

namespace Flamingo;

use Flamingo\Exception\RuntimeException;
use Flamingo\Reader\ReaderInterface;
use Flamingo\Writer\WriterInterface;

/**
 * Class Task
 * @package Flamingo
 */
abstract class Task
{
    /**
     * @var array
     */
    protected $processorExtensions = [
        'csv' => 'Csv',
        'xls' => 'Spreadsheet',
        'xlsx' => 'Spreadsheet',
        'ods' => 'Spreadsheet',
        'json' => 'Json',
        'js' => 'Json',
        'xml' => 'Xml',
        'yaml' => 'Yaml',
        'yml' => 'Yaml',
    ];

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
        if (empty($readerType)) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $readerType = $this->processorExtensions[$extension];
        }

        /** @var ReaderInterface $reader */
        $className = sprintf('Flamingo\\Reader\\%sReader', ucwords($readerType));
        $reader = new $className($options);

        return $reader->load($filename);
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
        if (empty($writerType)) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $writerType = $this->processorExtensions[$extension];
        }

        /** @var WriterInterface $writer */
        $className = sprintf('Flamingo\\Writer\\%sWriter', ucwords($writerType));
        $writer = new $className($table, $options);

        $writer->save($filename);
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

        if (strlen($versions[0]) && version_compare($GLOBALS['FLAMINGO']['Version'], $versions[0], '<')) {
            throw new RuntimeException(
                'The Flamingo version is too low (current: %s, needed: %s)',
                $GLOBALS['FLAMINGO']['Version'],
                $versions[0]
            );
        }

        if (strlen($versions[1]) && version_compare($GLOBALS['FLAMINGO']['Version'], $versions[1], '>')) {
            throw new RuntimeException(
                'The Flamingo version is too high (current: %s, needed: %s)',
                $GLOBALS['FLAMINGO']['Version'],
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
