<?php

namespace Flamingo;

use Analog\Analog;
use Flamingo\Core\Task;
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
     * @param bool $mainTask
     */
    public function run($taskName = 'default', $mainTask = true)
    {
        $taskName = strtolower($taskName);

        if (!array_key_exists($taskName, $this->tasks)) {
            Analog::error(sprintf('The task "%s" does not exist!', $taskName));
            return;
        }

        $task = $this->tasks[$taskName];

        if (!($task instanceof Task)) {
            Analog::error(sprintf('Registered task "%s" is not valid!', $taskName));
            return;
        }

        if ($mainTask) {

            $startTime = microtime(true);
            Analog::info(sprintf('Running "%s"...', $taskName));

            $task->execute($this);

            $execTime = microtime(true) - $startTime;
            Analog::info(sprintf('Finished "%s" in %fs', $taskName, $execTime));

        } else {

            $task->execute($this);
        }
    }
}