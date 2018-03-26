<?php

namespace Flamingo;

use Analog\Analog;
use Flamingo\Service\ConfigurationParser;
use Flamingo\Service\InheritancesResolver;
use Flamingo\Utility\ArrayUtility;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Flamingo
 * @package Flamingo
 */
class Flamingo
{
    /**
     * @var array
     */
    protected $configuration = [];

    /**
     * @var Task[]
     */
    protected $tasks = [];

    /**
     * Flamingo constructor.
     * @params string|array
     */
    public function __construct()
    {
        foreach (func_get_args() as $arg) {
            $this->addConfiguration($arg);
        }
        $this->parseConfiguration();
    }

    /**
     * Merge configurations into an array of tasks.
     * String value is interpreted as YAML.
     *
     * @param string|array $configuration
     */
    public function addConfiguration($configuration)
    {
        if (is_string($configuration)) {
            $configuration = Yaml::parse($configuration);
        }

        if (is_array($configuration)) {
            ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $configuration);
        }
    }

    /**
     * Load configuration from file and merge it with the current array of tasks.
     * TODO: Test if file exists before reading its content
     *
     * The filename folder is used to resolve the current root dir.
     * If FALSE, skip that option (default).
     * If TRUE, use the filename folder as root dir.
     * If STRING, it's a custom path, don't touch that.
     *
     * @see \Flamingo\Service\ConfigurationParser::loadGlobalConfiguration
     *
     * @param string $filename
     */
    public function addConfigurationFromFile($filename)
    {
        $configuration = file_get_contents($filename);
        $configuration = Yaml::parse($configuration);

        if (is_array($configuration)) {

            if (isset($configuration['Flamingo']['Root'])) {
                if ($configuration['Flamingo']['Root'] === true) {
                    $configuration['Flamingo']['Root'] = dirname($filename);
                }
            }

            ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $configuration);
        }
    }

    /**
     * Parse and add new tasks to the list
     */
    public function parseConfiguration()
    {
        $configuration = InheritancesResolver::create($this->configuration)->getResolvedConfiguration();
        $this->tasks = ConfigurationParser::create($configuration)->getResolvedTasks();
    }

    /**
     * Run flamingo task
     *
     * @param string $taskName
     * @param TaskRuntime $taskRuntime
     */
    public function run($taskName = 'default', $taskRuntime = null)
    {
        // Check version constraint
        $this->checkVersionRequirements();

        // Get task from list
        $task = $this->getTask(strtolower($taskName));

        // Create taskRuntime if it does not exist
        if ($taskRuntime === null) {
            $taskRuntime = new TaskRuntime($this, $task);
        } else {
            $taskRuntime->setCurrentTask($task);
        }

        // If the task is not at root level, do not display info logs
        if ($taskRuntime->isSubTask()) {
            $task->execute($taskRuntime);
        } else {
            Analog::info(sprintf('Running "%s"...', $taskName));
            $task->execute($taskRuntime);
            Analog::info(sprintf('Finished "%s" in %fs', $taskName, $taskRuntime->getElapsedTime()));
        }
    }

    /**
     * Check if the configuration version is compatible
     * with the current flamingo version
     */
    protected function checkVersionRequirements()
    {
        if (version_compare($GLOBALS['FLAMINGO']['Version'], $GLOBALS['FLAMINGO']['RequiredVersion'], "<")) {
            Analog::alert(sprintf(
                'The current configuration does not meet the version requirements (current: %s, needed: %s)',
                $GLOBALS['FLAMINGO']['Version'],
                $GLOBALS['FLAMINGO']['RequiredVersion']
            ));
        }
    }

    /**
     * Find a task in the current application
     *
     * @param string $taskName
     * @return Task
     * @internal
     */
    public function getTask($taskName)
    {
        if (!array_key_exists($taskName, $this->tasks)) {
            Analog::error(sprintf('The task "%s" does not exist!', $taskName));
            return null;
        }

        $task = $this->tasks[$taskName];

        if (!($task instanceof Task)) {
            Analog::error(sprintf('Registered task "%s" is not valid!', $taskName));
            return null;
        }

        return $task;
    }
}