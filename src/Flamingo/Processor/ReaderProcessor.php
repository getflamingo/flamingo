<?php

namespace Flamingo\Processor;

use Flamingo\Core\TaskRuntime;
use Flamingo\Processor\Reader\ReaderInterface;

/**
 * Class SourceProcessor
 * @package Flamingo\Processor
 */
class ReaderProcessor extends AbstractProcessor
{
    /**
     * Access to stream configuration resolver
     */
    use StreamProcessorTrait;

    /**
     * @var string
     */
    protected $noTypeFound = 'No reader found for the type "%s"';

    /**
     * Insert a table data into current TaskRuntime
     * Accepts multiple sources at once
     * TODO: Maybe remove the fact that the ReaderProcessor can accept multiple sources at once
     *
     * Examples:
     *
     *  - Src: filename.json
     *
     *  - Src:
     *      - filename.json
     *      - filename.csv
     *
     *  - Src:
     *      - file: filename.json
     *        type: json
     *
     * @param TaskRuntime $taskRuntime
     * @return mixed
     */
    public function execute(TaskRuntime $taskRuntime)
    {
        $configuration = $this->configuration;

        // Only one IO was defined
        if (is_string($configuration)) {
            $configuration = [$configuration];
        }

        // No configuration found
        if (!is_array($configuration)) {
            return 0;
        }

        foreach ($configuration as $sourceConfiguration) {

            $streamConfiguration = $this->resolveStreamConfiguration($sourceConfiguration);
            $readerName = $streamConfiguration['parserType'];
            $readerOptions = $streamConfiguration['options'] ?: [];

            // Find class name
            $className = $GLOBALS['FLAMINGO']['Classes']['Reader'][ucwords($readerName)]['className'];

            // Create writer if it exists
            if (class_exists($className) && ($parser = new $className)) {
                if ($parser instanceof ReaderInterface) {
                    $taskRuntime->addTable($parser->read($readerOptions));
                }
            }
        }

        return 0;
    }
}