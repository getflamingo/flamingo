<?php

namespace Flamingo\Writer;

use Analog\Analog;
use Flamingo\Core\Writer;

/**
 * Class FileWriter
 *
 * Handles errors for writers
 * putting content in file
 *
 * @package Flamingo\Writer
 */
abstract class FileWriter implements Writer
{
    /**
     * @@param \Flamingo\Model\Table $table
     * @param array $options
     */
    public function write($table, $options)
    {
        if (empty($options['file'])) {
            Analog::error(sprintf('No filename defined - %s', json_encode($options)));
        }

        $data = $this->tableContent($table, $options);

        Analog::debug(sprintf('Write data into %s - %s', $options['file'], json_encode($options)));
        file_put_contents($options['file'], $data);
    }

    /**
     * Encode table data into a readable string
     * to put into destination file
     *
     * @param \Flamingo\Model\Table $table
     * @param array $options
     * @return string
     */
    protected abstract function tableContent($table, $options);
}