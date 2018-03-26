<?php

namespace Flamingo;

use Flamingo\Processor\MappingProcessor;
use Flamingo\Reader\ReaderInterface;
use Flamingo\Writer\WriterInterface;

/**
 * Class Task
 * @package Flamingo
 */
abstract class Task
{
    /**
     * Implement this class.
     */
    abstract public function __invoke();

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
            $readerType = pathinfo($filename, PATHINFO_EXTENSION);
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
            $writerType = pathinfo($filename, PATHINFO_EXTENSION);
        }

        /** @var WriterInterface $writer */
        $className = sprintf('Flamingo\\Writer\\%sWriter', ucwords($writerType));
        $writer = new $className($table, $options);

        $writer->save($filename);
    }

    /**
     * Remap column names of a table object.
     *
     * @param Table $table
     * @param array $mapping
     * @return Table
     */
    protected function map(Table $table, array $mapping)
    {
        $data = clone $table;
        (new MappingProcessor($data, $mapping))->run();

        return $data;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
}
