<?php

namespace Flamingo\Core;

/**
 * Interface Writer
 * @package Flamingo\Core
 */
interface Writer
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     */
    public function write($table, $options);
}