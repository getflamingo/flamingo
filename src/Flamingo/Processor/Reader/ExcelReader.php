<?php

namespace Flamingo\Processor\Reader;

use Flamingo\Core\Table;

/**
 * Class ExcelReader
 * @package Flamingo\Processor\Reader
 */
class ExcelReader extends AbstractFileReader
{
    /**
     * @var array
     */
    protected $defaultOptions = [
        'header' => true,
        'sheet' => 0,
        'readOnly' => true,
        'nullValue' => null,
        'calculateFormulas' => false,
        'formatData' => false,
    ];

    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Core\Table
     */
    protected function fileContent($filename, array $options)
    {
        // Overwrite default options
        $options = array_replace($this->defaultOptions, $options);

        /** @var \PHPExcel $excel */
        $excel = \PHPExcel_IOFactory::load($filename);
        $excel->setActiveSheetIndex($options['sheet']);

        // Fetch all lines
        $data = $excel->getActiveSheet()->toArray(
            $options['nullValue'],
            $options['calculateFormulas'],
            $options['formatData']
        );

        // Use first line as header keys
        $header = $options['header'] ? array_shift($data) : [];

        // Clean up header keys
        if ($GLOBALS['FLAMINGO']['Options']['Header']['FirstLine']) {
            foreach ($header as &$column) {
                $column = current(explode(PHP_EOL, $column));
            }
            reset($header);
        }

        return new Table($filename, $header, array_values($data));
    }
}