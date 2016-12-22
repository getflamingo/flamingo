<?php

namespace Flamingo\Process;

use Flamingo\Core\Process;
use Flamingo\Core\Task;
use Flamingo\Utility\NamespaceUtility;
use Flamingo\Utility\ConfUtility;

/**
 * Class DestinationProcess
 *
 * Output data into a specific format
 *
 * @package Flamingo\Process
 */
class DestinationProcess extends Process
{
    /**
     * @param array $data
     * @return int
     */
    public function execute(&$data = [])
    {
        $destinations = $this->configuration;
        reset($data);

        // Only one destination was defined
        if (is_string($destinations)) {
            $destinations = [$destinations];
        }

        if (!is_array($destinations)) {
            return Task::ERROR;
        }

        foreach ($destinations as $index => $destination) {

            // One file as destination
            if (is_string($destination)) {
                $destination = ['file' => $destination];
            }

            // No file given, next
            if (empty($destination['file'])) {
                continue;
            }

            // Guess writer class
            $extension = NamespaceUtility::getExtension($destination['file']);
            if ($writerName = ConfUtility::getParser($extension)) {

                // Build class name
                $className = 'Flamingo\\Writer\\' . ucwords($writerName) . 'Writer';

                // Create writer if it exists
                if (class_exists($className)) {
                    (new $className)->write(current($data), $destination);
                    next($data);
                }
            }
        }

        return Task::OK;
    }
}