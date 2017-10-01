<?php

namespace Flamingo\Processor\Writer;

use Analog\Analog;
use Flamingo\Core\Table;

/**
 * Class ConsoleWriter
 * @package Flamingo\Processor\Writer
 */
class ConsoleWriter implements WriterInterface
{
    /**
     * @param Table $table
     * @param array $options
     */
    public function write(Table $table, array $options)
    {
        Analog::debug(sprintf('Display data from %s', $table->getName()));
        print_r($table->getArrayCopy());
    }
}