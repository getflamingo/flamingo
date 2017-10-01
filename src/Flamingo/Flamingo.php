<?php

namespace Flamingo;

use Analog\Analog;
use Flamingo\Core\Task;
use Flamingo\Core\TaskRuntime;
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
     * @var array<\Flamingo\Core\Task>
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
     * Merge configurations into an array of tasks
     * String value is interpreted as YAML
     *
     * @params string|array
     */
    public function addConfiguration()
    {
        foreach (func_get_args() as $arg) {
            if (is_string($arg)) {
                $arg = Yaml::parse($arg);
            }
            if (is_array($arg)) {
                ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $arg);
            }
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
        // Get task from list
        $task = $this->getTask(strtolower($taskName));

        // Create taskRuntime if it does not exist
        if ($taskRuntime === null) {
            $taskRuntime = new TaskRuntime($task);
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