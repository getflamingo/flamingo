<?php

namespace Flamingo\Core;

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
                $configuration = $arg;
            }
            if (is_string($arg)) {
                $configuration = Yaml::parse($arg);
            }
        }

        $this->tasks = $compiler->parse($configuration);
    }

    /**
     * Run script task
     * @param string $task
     */
    public function run($task = 'default')
    {
        var_dump($this->tasks); die;
    }
}