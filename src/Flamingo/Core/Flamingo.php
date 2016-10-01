<?php

namespace Flamingo\Core;

use Flamingo\Utility\ArrayUtility;
use Flamingo\Exception\RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Flamingo
 * @package Flamingo\Core
 */
class Flamingo
{
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
    }

    /**
     * Merge configurations into array of tasks
     * @params string|array
     */
    public function addConfiguration()
    {
        $configuration = [];

        foreach (func_get_args() as $arg) {
            if (is_array($arg)) {
                $configuration = ArrayUtility::merge($configuration, $arg);
            }
            if (is_string($arg)) {
                $yaml = Yaml::parse($arg);
                $configuration = ArrayUtility::merge($configuration, $yaml);
            }
        }

        // Compile and add new tasks to the list
        $this->tasks += (new Compiler)->parse($configuration);
    }

    /**
     * Run flamingo task
     * @param string $taskName
     */
    public function run($taskName = 'default')
    {
        $taskName = strtolower($taskName);

        if (!array_key_exists($taskName, $this->tasks)) {
            throw new RuntimeException(sprintf('The task "%s" does not exist!', $taskName));
        }

        $task = $this->tasks[$taskName];

        if (!($task instanceof Task)) {
            throw new RuntimeException(sprintf('Registered task "%s" is not valid!', $taskName));
        }

        $task->execute();
    }
}