<?php

namespace Flamingo\Reader;

use Flamingo\Model\Table;

/**
 * Class ExcelReader
 * @package Flamingo\Reader
 */
class ExcelReader extends FileReader
{
    /**
     * @param string $filename
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    protected function fileContent($filename, $options)
    {
        $defaultOptions = [
            'header' => true,
            'sheet' => 0,
            'readOnly' => true,
        ];

        // Overwrite default options
        $options = array_replace($defaultOptions, $options);

        // Create file loader
        $reader = \PHPExcel_IOFactory::createReaderForFile($filename);
        $reader->setReadDataOnly($options['readOnly']);

        /** @var \PHPExcel $excel */
        $excel = $reader->load($filename);
        $excel->setActiveSheetIndex($options['sheet']);

        // Fetch all lines
        $data = $excel->getActiveSheet()->toArray();

        // Use first line as header keys
        $header = $options['header'] ? array_shift($data) : [];

        return new Table($filename, $header, array_values($data));
    }
}