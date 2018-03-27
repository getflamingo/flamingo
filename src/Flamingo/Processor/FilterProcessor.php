<?php

namespace Flamingo\Processor;

use Flamingo\Exception\RuntimeException;

/**
 * Class MappingProcessor
 * @package Flamingo\Processor
 */
class FilterProcessor extends AbstractProcessor
{
    /**
     * @var array
     */
    protected $options = [
        'property' => null,
        'value' => null,
        'invert' => false,
    ];

    /**
     * Rename columns recursively.
     *
     * @throws RuntimeException
     */
    public function run()
    {
        $property = $this->options['property'];
        $value = $this->options['value'];
        $invert = $this->options['invert'];

        if (empty($property)) {
            throw new RuntimeException('No property defined');
        }

        $sourceArray = array_filter(
            $this->table->getArrayCopy(),
            function ($item) use ($property, $value, $invert) {
                $keep = is_null($value) ? empty($item[$property]) : ($item[$property] == $value);

                return $invert ? !$keep : $keep;
            }
        );

        $this->table->copy($sourceArray);
    }
}
