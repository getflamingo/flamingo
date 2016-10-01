<?php

namespace Flamingo\Process;

use Flamingo\Core\Process;
use Flamingo\Core\Task;
use Flamingo\Utility\NamespaceUtility;
use Flamingo\Utility\ConfUtility;

/**
 * Class SourceProcess
 *
 * The source process add input data to the stream using readers
 * TODO: Handle database format?
 *
 * @package Flamingo\Process
 */
class SourceProcess extends Process
{
    /**
     * For each source, create the appropriate reader
     * Create Tables with those data
     *
     * @param array $data
     * @return int
     */
    public function execute(&$data = [])
    {
        $sources = $this->configuration;

        // Only one source was defined
        if (is_string($sources)) {
            $sources = [$sources];
        }

        if (!is_array($sources)) {
            return Task::ERROR;
        }

        foreach ($sources as $source) {

            // One file as source
            if (is_string($source)) {
                $source = ['file' => $source];
            }

            // No file given, next
            if (empty($source['file'])) {
                continue;
            }

            // Guess reader class
            $extension = NamespaceUtility::getExtension($source['file']);
            if ($readerName = ConfUtility::getParser($extension)) {

                // Build class name
                $className = 'Flamingo\\Reader\\' . ucwords($readerName) . 'Reader';

                // Create reader if it exists
                if (class_exists($className)) {
                    $data[] = $className::read($source);
                }
            }
        }

        return Task::OK;
    }
}