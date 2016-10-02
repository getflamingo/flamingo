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

            // No file given, next
            if (empty($source['file'])) {
                Analog::warning('Source "file" is not defined');
                continue;
            }

            if (!file_exists($source['file'])) {
                Analog::error(sprintf('Source file "%s" does not exist', $source['file']));
            }

            // Guess reader class
            $extension = NamespaceUtility::getExtension($source['file']);
            if ($readerName = ConfUtility::getParser($extension)) {

                // Build class name
                $readerName = NamespaceUtility::pascalCase($readerName);
                $className = 'Flamingo\\Reader\\' . $readerName . 'Reader';

                // Create reader if it exists
                if (!class_exists($className)) {
                    Analog::error(sprintf('Reader "%s" does not exist for the file %s', $readerName, $source['file']));
                    continue;
                }

                $data[] = $className::read($source);
            }
        }

        return Task::OK;
    }
}