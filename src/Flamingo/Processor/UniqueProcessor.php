<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class UniqueProcessor
 * @package Flamingo\Processor
 */
class UniqueProcessor extends AbstractSingleSourceProcessor
{
    /**
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    protected function processSource(Table $source, TaskRuntime $taskRuntime)
    {
        if (empty($this->configuration)) {
            $array = $source->getArrayCopy();
            $source->copy(array_unique($array, SORT_REGULAR));

            return;
        }

        $propertyName = $this->configuration;
        $array = [];

        foreach ($source->getArrayCopy() as $row) {
            if (isset($row[$propertyName])) {
                if (isset($array[md5($row[$propertyName])]) && $array[md5($row[$propertyName])] != $row){
                    var_dump($row); die;
                }
                $array[md5($row[$propertyName])] = $row;
            }
        }

        $source->copy($array);
    }
}