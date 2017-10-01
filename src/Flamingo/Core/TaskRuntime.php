<?php

namespace Flamingo\Core;

/**
 * Class TaskRuntime
 * @package Flamingo\Core
 */
class TaskRuntime
{
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
     * @var int
     */
    protected $startTime = 0;

    /**
     * TaskRuntime constructor.
     * @param Task $task
     * @param array $tables
     */
    public function __construct(Task $task, array $tables = [])
    {
        $this->currentTask = $task;
        $this->rootTask = $task;
        $this->tables = $tables;
        $this->startTime = microtime(true);
    }

    /**
     * Return current executed task
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
     * Tells if the current runtime is at root level
     * TODO: Implement this using return codes
     *
     * @return bool
     */
    public function isSubTask()
    {
        return $this->currentTask !== $this->rootTask;
    }

    /**
     * Get table data by source identifier
     *
     * @param string $identifier
     * @return mixed
     */
    public function getTableByIdentifier($identifier)
    {
        return $this->tables[$identifier];
    }

    /**
     * Return a copy of the stream tables
     *
     * @return array
     */
    public function getTables()
    {
        return $this->tables;
    }

    /**
     * Return the elapsed time
     * @return int|mixed
     */
    public function getElapsedTime()
    {
        return microtime(true) - $this->startTime;
    }

    /**
     * Tells if the application is currently running
     *
     * @return bool
     */
    public function isRunning()
    {
        return $this->startTime > 0;
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