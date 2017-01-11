<?php
namespace Flamingo\Writer;

use Analog\Analog;

/**
 * Class ConsoleWriter
 * @package Flamingo\Writer
 */
class ConsoleWriter implements WriterInterface
{
    /**
     * @param \Flamingo\Model\Table $table
     * @param array $options
     */
    public function write($table, $options)
    {
        Analog::debug(sprintf('Display data from %s', $table->getName()));
        print_r(iterator_to_array($table));
    }
}