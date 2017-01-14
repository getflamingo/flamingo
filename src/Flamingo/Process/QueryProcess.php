<?php
namespace Flamingo\Process;

use Analog\Analog;
use Flamingo\Model\Table;
use Flamingo\Utility\ArrayUtility;

/**
 * Class QueryProcess
 * @package Flamingo\Process
 */
class QueryProcess extends AbstractProcess
{
    /**
     * Execute a LINQ query on multiple sources
     *
     * @param array $data
     * @return void
     */
    public function execute(&$data)
    {
        // Execute query on first source
        $source = $this->query($data, $this->configuration);

        // Create result table
        $table = new Table;
        $table->copy($source->toArrayDeep());

        // Insert table into data
        $data = [$table];
    }

    /**
     * Execute a query on data array
     * This has to have its own method to handle recursion
     *
     * @param array $data
     * @param array $configuration
     * @return \YaLinqo\Enumerable
     */
    protected function query(&$data, $configuration)
    {
        $defaultOptions = [
            'source' => null,
            'orderBy' => null,
            'join' => null,
            'where' => null,
        ];

        $options = array_replace($defaultOptions, $configuration);

        if (!is_numeric($index = $options['source'])) {
            Analog::error('Source is undefined');
            return null;
        }

        if (!array_key_exists($index, $data)) {
            Analog::error(sprintf('No source found at index "%s"', $index));
            return null;
        }

        // Create query from the selected source
        $request = \from($data[$index]);

        // Order by a field name
        if (!is_null($orderBy = $options['orderBy'])) {
            $request = $request->orderBy(function ($record) use ($orderBy) {
                return $record[$orderBy];
            });
        }

        // Join with another data source
        if (is_array($joinConstraints = $options['join'])) {

            $joinValues = [];

            foreach ($joinConstraints as $k => $join) {

                $defaultJoin = [
                    'source' => null,
                    'local' => null,
                    'foreign' => null,
                ];

                $join = array_replace($defaultJoin, $join);

                // Join current data with a selected source
                $requestJoin = $request->join(
                    $this->query($data, $join),
                    function ($baseSource) use ($join) {
                        return $baseSource[$join['foreign']];
                    },
                    function ($joinedSource) use ($join) {
                        return $joinedSource[$join['local']];
                    },
                    function ($baseSource, $joinedSource) use ($index, $join) {
                        return array_merge(
                            ArrayUtility::prefixKeys($baseSource, $index . '.'),
                            ArrayUtility::prefixKeys($joinedSource, $join['source'] . '.')
                        );
                    },
                    function ($baseSource) use ($join) {
                        return sha1(serialize(array_values($baseSource)));
                    }
                );

                // Add join values to list
                $joinValues[] = $requestJoin->toArrayDeep();
            }

            // Merge all the values gathered by JOIN constraints
            $joinValues = array_reduce($joinValues, function ($target, $array) {
                return ArrayUtility::merge($target, $array);
            }, []);

            // Add join values to request
            $request = \from(array_values($joinValues));
        }

        return $request;
    }
}