<?php

namespace Flamingo\Processor;

use Analog\Analog;
use Flamingo\Utility\ArrayUtility;

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
     * That case is handled in the SourceProcessor
     *
     * @param mixed $configuration
     * @return array
     */
    protected function resolveStreamConfiguration($configuration)
    {
        $streamConfiguration = [
            'options' => [],
        ];

        // One file as target
        if (is_string($configuration)) {
            $configuration = ['file' => $configuration];
        }

        // Get from file extension
        if (!empty($configuration['file'])) {
            $type = pathinfo($configuration['file'], PATHINFO_EXTENSION);
        }

        // Get from type property
        if (!empty($configuration['type'])) {
            $type = $configuration['type'];
        }

        // No type found
        if (!isset($type)) {
            Analog::error(sprintf($this->noTypeFound, json_encode($configuration)));
            return $streamConfiguration;
        }

        // Search for compatible parser
        if ($parserType = $this->getParserType($type)) {
            $streamConfiguration = [
                'parserType' => $parserType,
                'options' => $configuration,
            ];
        }

        return $streamConfiguration;
    }

    /**
     * Get parser class from file extension and global settings
     * If not matching parser is found, skip this stream
     *
     * @param string $extension
     * @return bool|string
     */
    protected function getParserType($extension)
    {
        foreach ($GLOBALS['FLAMINGO']['Options']['FileProcessorExtensions'] as $parser => $extensions) {
            if (ArrayUtility::inList($extension, $extensions)) {
                return $parser;
            }
        }
        return false;
    }
}