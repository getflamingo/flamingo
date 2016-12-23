<?php
namespace Flamingo\Process;

use Flamingo\Core\Writer;

/**
 * Class DestinationProcess
 * @package Flamingo\Process
 */
class DestinationProcess extends DataProcess
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
            if ($parser instanceof Writer) {
                $parser->write(current($data), $configuration);
                next($data);
            }
        }
    }
}