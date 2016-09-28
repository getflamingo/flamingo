<?php

namespace Flamingo\Process;

use Flamingo\Core\Process;
use Flamingo\Core\Task;

/**
 * Class DestinationProcess
 *
 * Output data into a specific format
 * TODO: Add writers for multiple data types
 *
 * @package Flamingo\Process
 */
class DestinationProcess extends Process
{
    /**
     * @param array $data
     * @return int
     */
    public function execute(&$data = [])
    {
        return Task::OK;
    }
}

//class_alias('DestinationProcess', 'DestProcess');
//class_alias('DestinationProcess', 'DestinationsProcess');