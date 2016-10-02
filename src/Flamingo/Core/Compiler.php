<?php

namespace Flamingo\Core;

use Flamingo\Process\TaskProcess;
use Flamingo\Utility\ArrayUtility;
use Flamingo\Utility\ConfUtility;
use Flamingo\Utility\NamespaceUtility;
use Flamingo\Exception\ProcessException;

/**
 * Class Compiler
 *
 * Read configuration array and output
 * a list of tasks for a script.
 *
 * @package Flamingo\Core
 */
class Compiler
{
    /**
     * Read configuration and return Tasks
     *
     * @param array $configuration
     * @return array<Flamingo\Core\Task>
     */
    public function parse($configuration)
    {
        $tasks = [];

        foreach ($configuration as $name => $conf) {

            if ($taskName = NamespaceUtility::matches($name, 'Flamingo/Task/*')) {
                $taskName = strtolower($taskName[0]);
                $tasks[$taskName] = $this->parseTask($conf);
            }

            if (is_array(NamespaceUtility::matches($name, 'Flamingo/Require'))) {
                $this->parseRequire($conf);
            }

            if ($confName = NamespaceUtility::matches($name, 'Conf/*/*')) {
                $this->parseConf($confName[0], $confName[1], $conf);
            }
        }

        return $tasks;
    }

    /**
     * Include all the required files
     * It can be used to call some Composer dependencies
     * TODO: Add YAML configuration support
     *
     * @param mixed $requires
     */
    protected function parseRequire($requires)
    {
        if (is_string($requires)) {
            $requires = [$requires];
        }

        if (!is_array($requires)) {
            return;
        }

        foreach ($requires as $require) {

            if (!is_string($require)) {
                continue;
            }

            if (NamespaceUtility::getExtension($require) == 'php') {
                if (file_exists($require)) {
                    require_once $require;
                }
            }
        }
    }

    /**
     * Parse Flamingo global conf to create class aliases
     * or to add configuration in $GLOBALS['FLAMINGO']['CONF']
     *
     * @param string $group
     * @param string $key
     * @param mixed $value
     */
    protected function parseConf($group, $key, $value)
    {
        $group = NamespaceUtility::pascalCase($group);
        $key = NamespaceUtility::pascalCase($key);

        // Add new process alias
        if ($group === 'Alias' && !empty($value)) {
            if (class_exists($className = 'Flamingo\\Process\\' . $key . 'Process')) {
                foreach (ArrayUtility::trimsplit(',', $value) as $alias) {
                    $alias = NamespaceUtility::pascalCase($alias);
                    class_alias($className, 'Flamingo\\Process\\' . $alias . 'Process');
                }
            }
            return;
        }

        // Configure logs
        if ($group === 'Log' && !empty($value)) {

            if ($key === 'Level') {
                error_reporting($value);
                return;
            }

            if ($key === 'Debug') {

                // Custom error output for logs
                set_error_handler(function ($num, $message) use ($value) {
                    if ($value) {
                        $header = ($num !== E_USER_NOTICE ? '[' . ConfUtility::errorName($num) . '] ' : '');
                        echo $header . $message . PHP_EOL;
                    }
                }, E_ALL);

                return;
            }
        }

        // Add value global conf key
        $GLOBALS['FLAMINGO']['CONF'][$group][$key] = $value;
    }

    /**
     * Parse task conf
     * Note: Each process conf must have no key
     * TODO: Add exception on process key
     *
     * @param array $configuration
     * @return \Flamingo\Core\Task
     */
    protected function parseTask($configuration)
    {
        $task = new Task();

        // Data is not an iterator
        if (empty($configuration) || !is_array($configuration)) {
            return $task;
        }

        foreach ($configuration as $processConf) {
            if ($process = $this->parseProcess($processConf)) {
                $task->addProcess($process);
            }
        }

        return $task;
    }

    /**
     * Build the process class name and
     * create a new process using this conf
     *
     * @param array $configuration
     * @return \Flamingo\Core\Process
     * @throws \Flamingo\Exception\ProcessException
     */
    protected function parseProcess($configuration)
    {
        if (is_string($configuration) && $taskName = NamespaceUtility::matches($configuration, 'Flamingo/Task/*')) {
            return new TaskProcess($configuration);
        }

        if (is_array($configuration)) {

            // Get configuration name and data
            $name = current(array_keys($configuration));
            $configuration = current($configuration);

            // Process name does not match any format
            if (!($processName = NamespaceUtility::matches($name, 'Flamingo/Process/*'))) {
                if (!($processName = NamespaceUtility::matches($name, '*'))) {
                    throw new ProcessException(sprintf('The process name "%s" is malformed', $name));
                }
            }

            // Build class name
            $className = 'Flamingo\\Process\\' . ucwords($processName[0]) . 'Process';

            if (!class_exists($className)) {
                throw new ProcessException(sprintf('The process "%s" does not exist', $processName[0]));
            }

            // Create process if it exists
            return new $className($configuration);
        }

        return null;
    }
}