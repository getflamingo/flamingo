<?php

namespace Flamingo\Processor;

use Analog\Analog;
use Flamingo\Core\Task;

/**
 * Class FilterProcess
 * @package Flamingo\Process
 */
class FilterProcess extends AbstractProcessor
{
    /**
     * FilterProcess constructor.
     * @param mixed $configuration
     */
    public function __construct($configuration)
    {
        // Set up a default null filter for this property
        if (is_string($configuration)) {
            $configuration = [
                'property' => $configuration,
                'invert' => true,
            ];
        }

        $defaultConfiguration = [
            'source' => 0,
            'property' => null,
            'value' => null,
            'invert' => false,
        ];

        $configuration = array_replace($defaultConfiguration, $configuration);
        parent::__construct($configuration);
    }

    /**
     * For each field, apply a set of functions
     *
     * @param array $data
     * @return int
     */
    public function execute(&$data)
    {
        $source = $this->configuration['source'];
        $property = $this->configuration['property'];
        $value = $this->configuration['value'];
        $invert = $this->configuration['invert'];

        if (empty($property)) {
            Analog::warning('No property defined');
            return Task::WARN;
        }

        // No source found
        if (false == array_key_exists($source, $data)) {
            Analog::warning(sprintf('No source found with identifier "%s"', $source));
            return Task::ERROR;
        }

        $sourceArray = array_filter(
            iterator_to_array($data[$source]),
            function ($item) use ($property, $value, $invert) {

                $keep = is_null($value)
                    ? empty($item[$property])
                    : ($item[$property] == $value);

                return $invert ? !$keep : $keep;
            }
        );

        $data[$source]->copy($sourceArray);

        return Task::OK;
    }
}