<?php
namespace Flamingo\Process;

use Flamingo\Writer\WriterInterface;

/**
 * Class DestinationProcess
 * @package Flamingo\Process
 */
class DestinationProcess extends AbstractStreamProcess
{
    /**
     * @var string
     */
    protected $noTypeFound = 'No type found for this destination - %s';

    /**
     * @param array $data
     * @param string $writerName
     * @param array $configuration
     */
    public function parseData(&$data, $writerName, $configuration)
    {
        // Build class name
        $className = 'Flamingo\\Writer\\' . ucwords($writerName) . 'Writer';

        // Create writer if it exists
        if (class_exists($className) && $parser = new $className) {
            if ($parser instanceof WriterInterface) {
                $parser->write(current($data), $configuration);
                next($data);
            }
        }
    }
}