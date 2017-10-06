<?php
namespace Flamingo\Processor;

use Analog\Analog;
use Flamingo\Model\Table;
use Flamingo\Utility\ArrayUtility;

/**
 * Class QueryProcess
 * @package Flamingo\Process
 */
class QueryProcess extends AbstractProcessor
{
    /**
     * Execute a LINQ query on multiple sources
     *
     * @param array $data
     * @return void
     */
    public function execute(&$data)
    {
        $newData = [];

        foreach ($this->configuration as $configuration) {

            // Execute 1st level query
            $request = $this->executeQuery($data, $configuration);

            // Insert request data into a new table
            $table = new Table;
            $table->copy($request);

            // Push to data
            $newData[] = $table;
        }

        $data = $newData;
    }

    /**
     * Execute a query on data array
     *
     * @param array $data
     * @param array $configuration
     * @return \YaLinqo\Enumerable
     */
    protected function executeQuery(&$data, $configuration)
    {
        // Determine source identifier
        $identifier = $configuration['from'];
        unset($configuration['from']);

        // Fetch source from original
        $source = iterator_to_array($data[$identifier]);
        $source = \from($source);

        // Use query options on source
        foreach ($configuration as $option => $parameters) {

            // Handle simple type argument
            if (!is_array($parameters)) {
                $parameters = [$parameters];
            }

            // Handle joinned queries
            foreach ($parameters as &$param) {
                if (is_array($param) && array_key_exists('from', $param)) {
                    $param = $this->executeQuery($data, $param);
                }
            }

            // Call selected option
            $source = call_user_func_array([$source, $option], $parameters);
        }

        // Return source as array
        return $source->toArrayDeep();
    }
}