<?php

namespace Flamingo\Process;

use Analog\Analog;
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
            Analog::warning('Sources array must be empty or null');
            return Task::ERROR;
        }

        foreach ($sources as $source) {

            // One file as source
            if (is_string($source)) {
                $source = ['file' => $source];
            }

            // Guess reader type
            if (!empty($source['type'])) {
                $type = $source['type'];
            } elseif (!empty($source['file'])) {
                $type = NamespaceUtility::getExtension($source['file']);
            } else {
                Analog::error(sprintf('No type found for this source - %s', json_encode($source)));
                continue;
            }

            // Search for compatible parser name
            if ($readerName = ConfUtility::getParser($type)) {

                // Build class name
                $readerName = NamespaceUtility::pascalCase($readerName);
                $className = 'Flamingo\\Reader\\' . $readerName . 'Reader';

                // Create reader if it exists
                if (!class_exists($className)) {
                    Analog::error(sprintf('No reader found for the type "%s"', $type));
                    continue;
                }

                $data[] = (new $className)->read($source);
            }
        }

        return Task::OK;
    }
}