<?php
namespace Flamingo\Process;

use Flamingo\Core\Task;

/**
 * Class TaskProcess
 *
 * This process should never be called as it is
 * Returns a SUMMON call to its parent task
 *
 * @package Flamingo\Process
 */
class TaskProcess extends AbstractProcess
{
    /**
     * Returns a signal to the task to call another task
     *
     * @param array $data
     * @return array
     */
    public function execute(&$data)
    {
        return [Task::SUMMON, $this->configuration];
    }
}