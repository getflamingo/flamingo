<?php

namespace Flamingo\Core;

/**
 * Class TaskRuntime
 * @package Flamingo\Core
 */
class TaskRuntime extends \ArrayIterator implements \Traversable
{
    /**
     * Get table data by source identifier
     *
     * @param string $identifier
     * @return mixed
     */
    public function getTableByIdentifier($identifier)
    {
        return $this->offsetGet($identifier);
    }

    /**
     * Return a copy of the stream tables
     *
     * @return array
     */
    public function getTables()
    {
        return $this->getArrayCopy();
    }
}