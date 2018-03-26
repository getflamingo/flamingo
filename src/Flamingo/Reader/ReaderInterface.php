<?php

namespace Flamingo\Processor\Reader;

use Flamingo\Core\Table;

/**
 * Interface ReaderInterface
 * @package Flamingo\Processor\Reader
 */
interface ReaderInterface
{
    /**
     * @param array $options
     * @return Table
     */
    public function read(array $options);
}