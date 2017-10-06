<?php

namespace Flamingo\Processor;

use Analog\Analog;
use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class FilterProcess
 * @package Flamingo\Process
 */
class FilterProcessor extends AbstractSingleSourceProcessor
{
    /**
     * Filter table rows properties by a value
     *
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table $source, TaskRuntime $taskRuntime)
    {
        $configuration = $this->configuration;

        // Set up a default null filter for this property
        if (is_string($configuration)) {
            $configuration = [
                'property' => $configuration,
                'invert' => true,
            ];
        }

        $defaultConfiguration = [
            'property' => null,
            'value' => null,
            'invert' => false,
        ];

        $configuration = array_replace($defaultConfiguration, $configuration);
        $property = $configuration['property'];
        $value = $configuration['value'];
        $invert = $configuration['invert'];

        if (empty($property)) {
            Analog::warning('No property defined');
            return;
        }

        $sourceArray = array_filter(
            $source->getArrayCopy(),
            function ($item) use ($property, $value, $invert) {
                $keep = is_null($value) ? empty($item[$property]) : ($item[$property] == $value);
                return $invert ? !$keep : $keep;
            }
        );

        $source->copy($sourceArray);
    }
}