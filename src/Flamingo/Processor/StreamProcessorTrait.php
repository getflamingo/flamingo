<?php

namespace Flamingo\Processor;

use Analog\Analog;

/**
 * Trait StreamProcessorTrait
 * @package Flamingo\Processor
 */
trait StreamProcessorTrait
{
    /**
     * Sanitize given stream configuration
     * Allows IO processor to be more flexible and readable
     *
     * Note: This function only resolves an iteration in your source array
     * That case is handled in the ReaderProcessor
     *
     * @param mixed $configuration
     * @return array
     */
    protected function resolveStreamConfiguration($configuration)
    {
        $parserType = null;

        // One file as target
        if (is_string($configuration)) {
            $configuration = ['file' => $configuration];
        }

        // Get type from file extension
        if (!empty($configuration['file'])) {
            $extension = pathinfo($configuration['file'], PATHINFO_EXTENSION);
            $parserType = $GLOBALS['FLAMINGO']['Options']['FileProcessorExtensions'][strtolower($extension)];
        }

        // Get from type property
        if (!empty($configuration['type'])) {
            $parserType = $configuration['type'];
        }

        // No type found
        if (!isset($parserType)) {
            Analog::error(sprintf($this->noTypeFound, json_encode($configuration)));
        }

        $streamConfiguration = [
            'parserType' => $parserType,
            'options' => $configuration,
        ];

        return $streamConfiguration;
    }
}