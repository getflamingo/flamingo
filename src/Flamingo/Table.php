<?php

namespace Flamingo;

use Flamingo\Processor\FilterProcessor;
use Flamingo\Processor\MappingProcessor;
use Flamingo\Processor\TransformProcessor;
use Flamingo\Reader\ReaderInterface;
use Flamingo\Writer\WriterInterface;

/**
 * Class Table
 * @package Flamingo
 */
class Table extends \ArrayIterator implements \Traversable
{
    /**
     * @var array
     */
    protected static $processorExtensions = [
        'csv' => 'Csv',
        'xls' => 'Spreadsheet',
        'xlsx' => 'Spreadsheet',
        'ods' => 'Spreadsheet',
        'json' => 'Json',
        'js' => 'Json',
        'xml' => 'Xml',
        'yaml' => 'Yaml',
        'yml' => 'Yaml',
    ];

    /**
     * Table constructor.
     * @param array $columns
     * @param array $records
     */
    public function __construct(array $columns = [], array $records = [])
    {
        if (count($columns) * count($records) > 0) {

            // Add keys to $records
            foreach ($records as &$record) {
                $record = array_combine($columns, $record);
            }

            parent::__construct($records);
        }
    }

    /**
     * Read a source and determines the adapted reader according to target type (if a filename).
     *
     * @param string $filename
     * @param array $options
     * @param string $readerType
     * @return $this
     */
    public static function read($filename, array $options = [], $readerType = '')
    {
        if (empty($readerType)) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $readerType = self::$processorExtensions[$extension];
        }

        /** @var ReaderInterface $reader */
        $className = sprintf('Flamingo\\Reader\\%sReader', ucwords($readerType));
        $reader = new $className($options);

        return $reader->load($filename);
    }

    /**
     * Output the table data to a filename.
     *
     * @param string $filename
     * @param array $options
     * @param string $writerType
     */
    public function write($filename, array $options = [], $writerType = '')
    {
        if (empty($writerType)) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $writerType = self::$processorExtensions[$extension];
        }

        /** @var WriterInterface $writer */
        $className = sprintf('Flamingo\\Writer\\%sWriter', ucwords($writerType));
        $writer = new $className($this, $options);

        $writer->save($filename);
    }

    /**
     * Remove null and empty values.
     *
     * @return $this
     */
    public function sanitize()
    {
        $copy = $this->getArrayCopy();

        $cleanArray = array_filter($copy, function ($record) {
            return (is_array($record) && count($record));
        });

        parent::__construct($cleanArray);

        return $this;
    }

    /**
     * Copy an array into object storage.
     *
     * @param array $array
     * @return $this
     */
    public function copy($array)
    {
        parent::__construct($array);

        return $this;
    }

    /**
     * Remap column names of the object.
     *
     * @param array $map
     * @param bool $keepProperties
     * @return $this
     */
    public function map(array $map, $keepProperties = true)
    {
        $options = [
            'map' => $map,
            'keepProperties' => $keepProperties,
        ];

        (new MappingProcessor($this, $options))->run();

        return $this;
    }

    /**
     * Remove values using the filtering processor.
     *
     * @param string $property
     * @param mixed|null $value
     * @param bool $invert
     * @return $this
     */
    public function filter($property, $value = null, $invert = false)
    {
        $options = [
            'property' => $property,
            'value' => $value,
            'invert' => is_null($value) ? true : $invert,
        ];

        (new FilterProcessor($this, $options))->run();

        return $this;
    }

    /**
     * @param array $modifiers
     * @param bool $propertyMustExist
     * @return $this
     */
    public function mod(array $modifiers, $propertyMustExist = false)
    {
        $options = [
            'modifiers' => $modifiers,
            'propertyMustExist' => $propertyMustExist,
        ];

        (new TransformProcessor($this, $options))->run();

        return $this;
    }
}
