<?php
namespace Flamingo\Reader;

/**
 * Interface ReaderInterface
 * @package Flamingo\Reader
 */
interface ReaderInterface
{
    /**
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    public function read($options);
}