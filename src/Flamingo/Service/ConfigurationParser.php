<?php

namespace Flamingo\Service;

use Analog\Analog;
use Flamingo\Core\Task;
use Flamingo\Processor\ProcessorInterface;

/**
 * Class ConfigurationParser
 * Inspired by the InheritancesResolverService from TYPO3 core
 *
 * @package Flamingo\Service
 */
class ConfigurationParser
{
    /**
     * @var array
     */
    protected $referenceConfiguration = [];

    /**
     * Returns an instance of this service. Additionally the configuration
     * which should be resolved can be passed.
     *
     * @param array $configuration
     * @return ConfigurationParser
     */
    public static function create(array $configuration = [])
    {
        /** @var ConfigurationParser $configurationParser */
        $configurationParser = new ConfigurationParser();
        $configurationParser->setReferenceConfiguration($configuration);
        return $configurationParser;
    }

    /**
     * Set the reference configuration which is used to get untouched
     * values which can be merged into the touched configuration.
     *
     * @param array $referenceConfiguration
     * @return ConfigurationParser
     */
    public function setReferenceConfiguration(array $referenceConfiguration)
    {
        $this->referenceConfiguration = $referenceConfiguration;
        return $this;
    }

    /**
     * Resolve all inheritances within a configuration.
     *
     * @return Task[]
     */
    public function getResolvedTasks()
    {
        $tasks = [];

        // TODO: Maybe move this elsewhere
        // Insert global configuration
        if (array_key_exists('Flamingo', $this->referenceConfiguration)) {
            $GLOBALS['FLAMINGO'] = $this->referenceConfiguration['Flamingo'];

            // Include once required PHP files
            if (array_key_exists('Include', $this->referenceConfiguration['Flamingo'])) {
                $this->parseInclude($this->referenceConfiguration['Flamingo']['Include']);
            }
        }

        foreach ($this->referenceConfiguration as $name => $conf) {
            if ($taskName = $this->extractTaskName($name)) {
                $tasks[$taskName] = $this->parseTask($conf);
            }
        }

        return $tasks;
    }

    /**
     * Return the taskName if matches the format
     * Return null if not
     *
     * @param string $string
     * @return string|null
     */
    protected function extractTaskName($string)
    {
        $matches = preg_match('/\w*\(\)/', $string);
        $taskName = substr($string, 0, -2);

        return ($matches && $taskName) ? strtolower($taskName) : null;
    }

    /**
     * Include all the required files
     * It can be used to call some Composer dependencies, see documentation
     *
     * @param mixed $files
     */
    protected function parseInclude($files)
    {
        if (is_string($files)) {
            $files = [$files];
        }

        if (!is_array($files)) {
            Analog::debug('Include array is empty or null');
            return;
        }

        foreach ($files as $filename) {

            if (!is_string($filename)) {
                Analog::warning(sprintf('Require list must contain only strings, %s given', gettype($filename)));
                continue;
            }

            if (pathinfo($filename, PATHINFO_EXTENSION) !== 'php') {
                Analog::warning('Include array only accepts PHP files!');
                continue;
            }

            if (!file_exists($filename)) {
                Analog::warning(sprintf('Included file "%s" does not exist', $filename));
                continue;
            }

            Analog::debug(sprintf('Include file "%s"', $filename));
            include_once($filename);
        }
    }

    /**
     * Parse task configuration array
     *
     * @param array $configuration
     * @return Task
     */
    protected function parseTask($configuration)
    {
        $task = new Task();

        // Data is not an iterator
        if (empty($configuration) || !is_array($configuration)) {
            Analog::debug('Task configuration is empty or null');
            return $task;
        }

        foreach ($configuration as $processorIdentifier => $processorConfiguration) {
            if ($processor = $this->parseProcessor($processorConfiguration)) {
                $task->addProcessor($processor);
            }
        }

        return $task;
    }

    /**
     * Build the processor class name and
     * create a new processor object using this conf
     *
     * @param array $configuration
     * @return ProcessorInterface
     */
    protected function parseProcessor($configuration)
    {
        if (is_array($configuration)) {

            // Get processor name and data
            $processorName = current(array_keys($configuration));
            $configuration = current($configuration);

            // Find class name
            $className = $GLOBALS['FLAMINGO']['Classes']['Processor'][ucwords($processorName)]['className'];

            if (!class_exists($className)) {
                Analog::error(sprintf('The processor "%s" does not exist', $processorName));
                return null;
            }

            // Create process if it exists
            Analog::debug(sprintf('Register processor "%s"', $className));
            return new $className($configuration);
        }

        Analog::debug('Processor configuration is empty or null');
        return null;
    }
}
