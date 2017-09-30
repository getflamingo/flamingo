<?php

namespace Flamingo\Core;

use Flamingo\Process\TaskProcess;
use Flamingo\Utility\ArrayUtility;
use Flamingo\Utility\NamespaceUtility;
use Analog\Analog;

/**
 * Class Compiler
 *
 * Read configuration array and output
 * a list of tasks for a script.
 *
 * @package Flamingo\Core
 */
class ConfigurationParser
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
            Analog::debug('Require array is empty or null');
            return;
        }

        foreach ($requires as $require) {

            if (!is_string($require)) {
                Analog::warning(sprintf('Require list must contain only strings, %s given', gettype($require)));
                continue;
            }

            if (NamespaceUtility::getExtension($require) !== 'php') {
                Analog::warning('Require array only accepts PHP files for the moment!');
                continue;
            }

            if (!file_exists($require)) {
                Analog::warning(sprintf('Required file "%s" does not exist', $require));
                continue;
            }

            Analog::debug(sprintf('Require file "%s"', $require));
            require_once $require;
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
        // Add value global conf key
        Analog::debug(sprintf('Configuration %s/%s = %s', $group, $key, $value));
        $GLOBALS['FLAMINGO']['CONF'][$group][$key] = $value;

        $group = NamespaceUtility::pascalCase($group);
        $key = NamespaceUtility::pascalCase($key);

        // Add new process alias
        if ($group === 'Alias' && !empty($value)) {

            if (!class_exists($className = 'Flamingo\\Process\\' . $key . 'Process')) {
                Analog::warning(sprintf('The class "%s" cannot be found to create aliases', $className));
                return;
            }

            foreach (ArrayUtility::trimsplit(',', $value) as $alias) {
                $alias = NamespaceUtility::pascalCase($alias);
                class_alias($className, 'Flamingo\\Process\\' . $alias . 'Process');
                Analog::debug(sprintf('Register alias "%s" -> "%s"', $alias, $key));
            }
        }
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
            Analog::debug('Task configuration is empty or null');
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
     * @return \Flamingo\Process\ProcessInterface
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
                    Analog::error(sprintf('The process name "%s" is malformed', $name));
                    return null;
                }
            }

            // Build class name
            $className = 'Flamingo\\Process\\' . ucwords($processName[0]) . 'Process';

            if (!class_exists($className)) {
                Analog::error(sprintf('The process "%s" does not exist', $processName[0]));
                return null;
            }

            // Create process if it exists
            Analog::debug(sprintf('Register process "%s"', $className));
            return new $className($configuration);
        }

        Analog::debug('Process configuration is empty or null');
        return null;
    }
}
