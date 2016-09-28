<?php

namespace Flamingo\Process;

use Flamingo\Core\Process;
use Flamingo\Core\Task;

/**
 * Class SourceProcess
 *
 * The source process add input data to the stream using readers
 * TODO: Add readers for multiple data types
 * TODO: Handle database format
 * TODO: Check aliases
 *
 * @package Flamingo\Process
 */
class SourceProcess extends Process
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

//class_alias('SourceProcess', 'SrcProcess');
//class_alias('SourceProcess', 'SourcesProcess');