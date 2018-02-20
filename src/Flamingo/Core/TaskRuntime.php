<?php

namespace Flamingo\Core;

use Commando\Command;
use Flamingo\Flamingo;

/**
 * Class TaskRuntime
 * @package Flamingo\Core
 */
class TaskRuntime
{
    /**
     * @var Flamingo
     */
    protected $container;

    /**
     * @var Task
     */
    protected $currentTask = null;

    /**
     * @var Task
     */
    protected $rootTask = null;

    /**
     * @var array
     */
    protected $tables = [];

    /**
     * @var float
     */
    protected $startTime = 0;

    /**
     * TaskRuntime constructor.
     * @param Flamingo $container
     * @param Task $task
     * @param array $tables
     */
    public function __construct(Flamingo $container, Task $task, array $tables = [])
    {
        $this->container = $container;
        $this->currentTask = $task;
        $this->rootTask = $task;
        $this->tables = $tables;
        $this->startTime = microtime(true);
    }

    /**
     * Return current executed task.
     *
     * @return Task
     */
    public function getCurrentTask()
    {
        return $this->currentTask;
    }

    /**
     * @param Task $task
     */
    public function setCurrentTask(Task $task)
    {
        $this->currentTask = $task;
    }

    /**
     * Tells if the current runtime is at root level.
     *
     * @return bool
     */
    public function isSubTask()
    {
        return $this->currentTask !== $this->rootTask;
    }

    /**
     * Forward to another task.
     * This method preserves the Table data between tasks.
     * TODO: Find a way to send a signal to the container, so both can be separated
     *
     * @param string $taskName
     */
    public function forward($taskName)
    {
        $this->container->run($taskName, $this);
    }

    /**
     * Redirects to a another task.
     * The current data is not preserved in that environment.
     * TODO: Find a way to send a signal to the container, so both can be separated
     *
     * @param string $taskName
     */
    public function redirect($taskName)
    {
        $this->tables = [];
        $this->rootTask = true;
        $this->forward($taskName);
    }

    /**
     * Insert a new table into the runtime.
     *
     * @param Table $table
     */
    public function addTable(Table $table)
    {
        $this->tables[] = $table;
    }

    /**
     * Get table data by source identifier.
     *
     * @param string $identifier
     * @return Table
     */
    public function getTableByIdentifier($identifier)
    {
        return $this->tables[$identifier];
    }

    /**
     * Return a copy of the stream tables.
     *
     * @return array
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Return the first available source.
     *
     * @return Table
     */
    public function getFirstTable()
    {
        return current($this->tables);
    }

    /**
     * Return the elapsed time.
     *
     * @return float
     */
    public function getElapsedTime()
    {
        return microtime(true) - $this->startTime;
    }

    /**
     * Tells if the application is currently running.
     *
     * @return bool
     */
    public function isRunning()
    {
        return $this->startTime > 0;
    }

    /**
     * Get CLI arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return (new Command())->getArguments();
    }

    /**
     * Reset values of the current runtime
     */
    public function reset()
    {
        $this->tables = [];
        $this->startTime = 0;
        $this->currentTask = null;
        $this->rootTask = false;
    }
}