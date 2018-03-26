<?php

namespace Flamingo;

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
     * Create a reader object with options.
     *
     * @param string $readerType
     * @param array $options
     * @return ReaderInterface
     */
    public function createReader($readerType, array $options = [])
    {
        $className = sprintf('Flamingo\\Reader\\%sReader', ucwords($readerType));

        return $className($options);
    }

    /**
     * Create a writer object with options.
     *
     * @param string $writerType
     * @param Table $table
     * @param array $options
     * @return WriterInterface
     */
    public function createWriter($writerType, Table $table, array $options = [])
    {
        $className = sprintf('Flamingo\\Writer\\%sWriter', ucwords($writerType));

        return $className($table, $options);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }
}
