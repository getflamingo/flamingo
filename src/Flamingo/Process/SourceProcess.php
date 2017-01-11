<?php
namespace Flamingo\Process;

use Flamingo\Reader\ReaderInterface;

/**
 * Class SourceProcess
 * @package Flamingo\Process
 */
class SourceProcess extends AbstractIoProcess
{
    /**
     * @var string
     */
    protected $noTypeFound = 'No reader found for the type "%s"';

    /**
     * @param array $data
     * @param string $readerName
     * @param array $configuration
     */
    public function parseData(&$data, $readerName, $configuration)
    {
        // Build class name
        $className = 'Flamingo\\Reader\\' . ucwords($readerName) . 'Reader';

        // Create writer if it exists
        if (class_exists($className) && $parser = new $className) {
            if ($parser instanceof ReaderInterface) {
                $data[] = $parser->read($configuration);
            }
        }
    }
}