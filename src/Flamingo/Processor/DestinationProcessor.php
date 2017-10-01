<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;
use Flamingo\Processor\Writer\WriterInterface;

/**
 * Class DestinationProcessor
 * @package Flamingo\Processor
 */
class DestinationProcessor extends AbstractSingleSourceProcessor
{
    /**
     * Access to stream configuration resolver
     */
    use StreamProcessorTrait;

    /**
     * Default writer parser name
     */
    const WRITER_DEFAULT = 'cli';

    /**
     * @var string
     */
    protected $noTypeFound = 'No type found for this destination - %s';

    /**
     * Output a single data table
     *
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table &$source, TaskRuntime $taskRuntime)
    {
        // Process configuration as a single stream
        $streamConfiguration = $this->resolveStreamConfiguration($this->configuration);

        // Use console writer if none is defined
        $writerName = $streamConfiguration ? $streamConfiguration['parserType'] : self::WRITER_DEFAULT;

        // Find class name
        $className = $GLOBALS['FLAMINGO']['Classes']['Writer'][ucwords($writerName)]['className'];

        // Create writer if it exists
        if (class_exists($className) && $parser = new $className) {
            if ($parser instanceof WriterInterface) {
                $parser->write($source, $streamConfiguration['options']);
            }
        }
    }
}