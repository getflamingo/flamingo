<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;
use Flamingo\Processor\Writer\WriterInterface;

/**
 * Class WriterProcessor
 * @package Flamingo\Processor
 */
class WriterProcessor extends AbstractSingleSourceProcessor
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
    protected $noTypeFound = 'No writer found for the type - %s';

    /**
     * Output a single data table
     *
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table $source, TaskRuntime $taskRuntime)
    {
        // Use console writer if none is defined
        $streamConfiguration = [
            'parserType' => self::WRITER_DEFAULT,
            'options' => [],
        ];

        // Process configuration as a single stream
        if (!empty($this->configuration)) {
            $streamConfiguration = $this->resolveStreamConfiguration($this->configuration);
        }

        $writerName = $streamConfiguration['parserType'];
        $writerOptions = $streamConfiguration['options'] ?: [];

        // Find class name
        $className = $GLOBALS['FLAMINGO']['Classes']['Writer'][ucwords($writerName)]['className'];

        // Create writer if it exists
        if (class_exists($className) && $parser = new $className) {
            if ($parser instanceof WriterInterface) {
                $parser->write($source, $writerOptions);
            }
        }
    }
}