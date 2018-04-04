<?php

namespace Flamingo\Reader;

use Flamingo\Table;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Class SpreadsheetReader
 * @package Flamingo\Reader
 */
class SpreadsheetReader extends AbstractFileReader
{
    /**
     * @var array
     */
    protected $options = [
        'header' => true,
        'sheet' => 0,
        'readOnly' => true,
        'nullValue' => null,
        'calculateFormulas' => false,
        'formatData' => false,
    ];

    /**
     * @param string $filename
     * @return Table
     */
    protected function fileContents($filename)
    {
        $spreadsheet = IOFactory::load($filename);
        $spreadsheet->setActiveSheetIndex($this->options['sheet']);

        // Fetch all lines
        $data = $spreadsheet->getActiveSheet()->toArray(
            $this->options['nullValue'],
            $this->options['calculateFormulas'],
            $this->options['formatData']
        );

        // Use first line as header keys
        $header = $this->options['header'] ? array_shift($data) : [];

        return new Table($header, array_values($data));
    }
}
