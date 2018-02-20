<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class QueryProcessor
 * @package Flamingo\Processor
 */
class QueryProcessor extends AbstractProcessor
{
    /**
     * Execute a LINQ query on multiple sources.
     *
     * @param TaskRuntime $taskRuntime
     */
    public function execute(TaskRuntime $taskRuntime)
    {
        foreach ($this->configuration as $configuration) {

            // Execute 1st level query
            $request = $this->executeQuery($taskRuntime, $configuration);

            // Insert request data into a new table
            $table = new Table();
            $table->copy($request);

            $taskRuntime->addTable($table);
        }
    }

    /**
     * @param TaskRuntime $taskRuntime
     * @param array $configuration
     * @return array
     */
    protected function executeQuery(TaskRuntime $taskRuntime, array $configuration)
    {
        // Determine source identifier
        $identifier = $configuration['from'];
        unset($configuration['from']);

        // Fetch source from original
        $source = iterator_to_array($taskRuntime->getTableByIdentifier($identifier));
        $source = \from($source);

        // Use query options on source
        foreach ($configuration as $option => $parameters) {

            // Handle simple type argument
            if (!is_array($parameters)) {
                $parameters = [$parameters];
            }

            // Handle joined queries
            foreach ($parameters as &$param) {
                if (is_array($param) && array_key_exists('from', $param)) {
                    $param = $this->executeQuery($taskRuntime, $param);
                }
            }

            // Call selected option
            $source = call_user_func_array([$source, $option], $parameters);
        }

        // Return source as array
        return $source->toArrayDeep();
    }
}