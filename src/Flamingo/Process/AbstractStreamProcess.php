<?php
namespace Flamingo\Process;

use Analog\Analog;
use Flamingo\Core\Task;
use Flamingo\Utility\ArrayUtility;
use Flamingo\Utility\NamespaceUtility;

/**
 * Class AbstractStreamProcess
 *
 * Handles basic input and output processes creation
 * Base version for Source and Destination
 *
 * @package Flamingo\Process
 */
abstract class AbstractStreamProcess extends AbstractProcess
{
    /**
     * Error to display when no type is found
     * @var string
     */
    protected $noTypeFound = '';

    /**
     * Crawl configuration array and execute a custom parser (from YML conf)
     *
     * @param array $data
     * @return int
     */
    public function execute(&$data)
    {
        $configuration = $this->configuration;

        // Only one IO was defined
        if (is_string($configuration)) {
            $configuration = [$configuration];
        }

        if (!is_array($configuration)) {
            return Task::ERROR;
        }

        foreach ($configuration as $index => $target) {

            // One file as target
            if (is_string($target)) {
                $target = ['file' => $target];
            }

            // Guess target type
            if (!empty($target['type'])) {
                $type = $target['type'];
            } elseif (!empty($target['file'])) {
                $type = NamespaceUtility::getExtension($target['file']);
            } else {
                Analog::error(sprintf($this->noTypeFound, json_encode($target)));
                continue;
            }

            // Search for compatible parser
            if ($parserName = $this->getParser($type)) {
                $this->parseData($data, $parserName, $target);
            }
        }

        return Task::OK;
    }

    /**
     * Get parser class from file extension and global settings
     * If not matching parser is found, skip this stream
     *
     * @param string $extension
     * @return bool|string
     */
    protected function getParser($extension)
    {
        $parsers = $GLOBALS['FLAMINGO']['CONF']['Parser'];

        foreach ($parsers as $parser => $extensions) {
            if (ArrayUtility::inList($extension, $extensions)) {
                return $parser;
            }
        }

        return false;
    }

    /**
     * @param <\Flamingo\Model\Table> $data
     * @param string $parserName
     * @param array $configuration
     */
    abstract protected function parseData(&$data, $parserName, $configuration);
}