<?php
namespace Flamingo\Core;

use Flamingo\Flamingo;
use Flamingo\Process\ProcessInterface;

/**
 * Class Task
 * A task is an entry point to the script execution
 * TODO: Implement a more object oriented way to summon tasks
 *
 * @package Flamingo\Core
 */
class Task
{
    /**
     * Values returned by processes
     */
    const OK = 0;
    const WARN = 1;
    const ERROR = 2;
    const REDIRECT = 3;
    const SUMMON = 4;

    /**
     * @var array<\Flamingo\Core\Process>
     */
    protected $processes = [];

    /**
     * Add a process to the list
     * @param \Flamingo\Process\ProcessInterface $process
     */
    public function addProcess($process)
    {
        if ($process instanceof ProcessInterface) {
            $this->processes[] = $process;
        }
    }

    /**
     * Execute each processes
     *
     * @param \Flamingo\Flamingo $flamingo
     * @return array<\Flamingo\Model\Table> $data
     */
    public function execute($flamingo = null)
    {
        $data = [];

        foreach ($this->processes as $process) {

            // Reset iterator index
            reset($data);

            // Execute process
            $signal = $process->execute($data);

            // A summon action has been called
            if (
                is_array($signal) &&
                count($signal) == 2 &&
                $signal[0] == Task::SUMMON &&
                !empty($takList = $signal[1]) &&
                ($flamingo instanceof Flamingo)
            ) {

                // Support one task as a string
                if (!is_array($takList)) {
                    $takList = [$takList];
                }

                // Run tasks
                foreach ($takList as $task) {
                    $flamingo->run($task, false);
                }
            }
        }

        return $data;
    }
}