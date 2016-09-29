<?php

namespace Flamingo\Core;

use Flamingo\Utility\Iterator as IteratorUtility;
use Flamingo\Exception\RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Script
 * @package Flamingo\Core
 */
class Script
{
    /**
     * @var array<\Flamingo\Core\Task>
     */
    protected $tasks = [];

    /**
     * Script constructor.
     * @params string|array
     */
    public function __construct()
    {
        $configuration = [];
        $compiler = new Compiler();

        foreach (func_get_args() as $arg) {
            if (is_array($arg)) {
                $configuration = IteratorUtility::merge($configuration, $arg);
            }
            if (is_string($arg)) {
                $yaml = Yaml::parse($arg);
                $configuration = IteratorUtility::merge($configuration, $yaml);
            }
        }

        $this->tasks = $compiler->parse($configuration);
    }

    /**
     * Run script task
     * @param string $taskName
     */
    public function run($taskName = 'default')
    {
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