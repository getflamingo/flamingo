<?php

namespace Flamingo\Reader;

use Flamingo\Table;

/**
 * Interface ReaderInterface
 * @package Flamingo\Reader
 */
interface ReaderInterface
{
    /**
     * ReaderInterface constructor.
     * @param array $options
     */
    public function __construct(array $options);

    /**
     * @param string $target
     * @return Table
     */
    public function load($target);
}
