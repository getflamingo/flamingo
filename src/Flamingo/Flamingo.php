<?php

namespace Flamingo;

use Analog\Analog;

/**
 * Class Flamingo
 * @package Flamingo
 */
class Flamingo
{
    /**
     * @param Task $task
     * @param TaskRuntime $taskRuntime
     */
    public function run(Task $task, TaskRuntime $taskRuntime)
    {
        // Check version constraint
        // TODO: Fix the version requirement check
//        $this->checkVersionRequirements();

        // Create taskRuntime if it does not exist
        if ($taskRuntime === null) {
            $taskRuntime = new TaskRuntime($this, $task);
        } else {
            $taskRuntime->setCurrentTask($task);
        }

        // If the task is not at root level, do not display info logs
        if ($taskRuntime->isSubTask()) {
//            $task->execute($taskRuntime);
        } else {
            Analog::info(sprintf('Running "%s"...', $task));
//            $task->execute($taskRuntime);
            Analog::info(sprintf('Finished "%s" in %fs', $task, $taskRuntime->getElapsedTime()));
        }
    }

    /**
     * Check if the configuration version is compatible with the current flamingo version.
     * TODO: Check version requirements based on a range of versions
     */
    protected function checkVersionRequirements()
    {
        if (version_compare($GLOBALS['FLAMINGO']['Version'], $GLOBALS['FLAMINGO']['RequiredVersion'], "<")) {
            Analog::alert(sprintf(
                'The current configuration does not meet the version requirements (current: %s, needed: %s)',
                $GLOBALS['FLAMINGO']['Version'],
                $GLOBALS['FLAMINGO']['RequiredVersion']
            ));
        }
    }
}
