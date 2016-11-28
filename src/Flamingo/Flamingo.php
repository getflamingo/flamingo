<?php

namespace Flamingo;

use Flamingo\Core\Compiler;
use Flamingo\Core\Task;
use Flamingo\Utility\ArrayUtility;
use Symfony\Component\Yaml\Yaml;
use Analog\Analog;

/**
 * Class Flamingo
 * @package Flamingo
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
                if (is_array($yaml = Yaml::parse($arg))) {
                    $configuration = ArrayUtility::merge($configuration, $yaml);
                }
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
            Analog::error(sprintf('The task "%s" does not exist!', $taskName));
            return;
        }

        $task = $this->tasks[$taskName];

        if (!($task instanceof Task)) {
            Analog::error(sprintf('Registered task "%s" is not valid!', $taskName));
            return;
        }

        $startTime = microtime(true);
        Analog::info(sprintf('Running "%s"...', $taskName));

        $task->execute();

        $execTime = microtime(true) - $startTime;
        Analog::info(sprintf('Finished "%s" in %fs', $taskName, $execTime));
    }
}