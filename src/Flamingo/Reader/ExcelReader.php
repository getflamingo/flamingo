<?php
namespace Flamingo\Reader;

use Flamingo\Model\Table;

/**
 * Class ExcelReader
 * @package Flamingo\Reader
 */
class ExcelReader extends AbstractFileReader
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
            'nullValue' => null,
            'calculateFormulas' => false,
            'formatData' => false,
        ];

        // Overwrite default options
        $options = array_replace($defaultOptions, $options);

        // Create file loader
        /** @var $reader \PHPExcel_Reader_Abstract $reader */
        $reader = \PHPExcel_IOFactory::createReaderForFile($filename);
        $reader->setReadDataOnly($options['readOnly']);

        /** @var \PHPExcel $excel */
        $excel = $reader->load($filename);
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
        if ($GLOBALS['FLAMINGO']['CONF']['Header']['FirstLine']) {
            foreach ($header as &$column) {
                $column = current(explode(PHP_EOL, $column));
            }
            reset($header);
        }

        return new Table($filename, $header, array_values($data));
    }
}