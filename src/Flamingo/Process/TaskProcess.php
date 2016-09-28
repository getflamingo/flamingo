<?php

namespace Flamingo\Process;

use Flamingo\Core\Task;
use Flamingo\Core\Process;

/**
 * Class TaskProcess
 *
 * This process should never be called as it is
 * Returns a SUMMON call to its parent task
 *
 * @package Flamingo\Process
 */
class TaskProcess extends Process
{
    /**
     * The task to summon next
     * @var string
     */
    protected $task;

    /**
     * TaskProcess constructor.
     * @param $taskName
     */
    public function __construct($taskName)
    {
        parent::__construct();
        $this->task = $taskName;
    }

    /**
     * Returns a signal to the task to call another task
     *
     * @param array $data
     * @return array
     */
    public function execute(&$data)
    {
        return [Task::SUMMON, $this->task];
    }
}