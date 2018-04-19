<?php

namespace Flamingo\Reader;

use Analog\Analog;
use Flamingo\Exception\RuntimeException;
use Flamingo\Table;
use Flamingo\Traits\DatabaseAccessTrait;

/**
 * Class DatabaseReader
 * @package Flamingo\Reader
 */
class DatabaseReader implements ReaderInterface
{
    use DatabaseAccessTrait;

    /**
     * DatabaseReader constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = array_replace($this->options, $options);
        $this->initializeObject();
    }

    /**
     * @param string $tableName
     * @return Table
     */
    public function load($tableName)
    {
        if (empty($tableName)) {
            throw new RuntimeException('No table name defined');
        }

        Analog::debug(sprintf('Building query from table name - "%s"', $tableName));
        $query = 'SELECT * FROM ' . $tableName;

        return $this->query($query);
    }

    /**
     * Execute a SQL request manually.
     *
     * @param string $query
     * @return Table
     * @throws RuntimeException
     */
    public function query($query)
    {
        try {

            // Execute query
            $query = $this->pdo->query($query, \PDO::FETCH_ASSOC)->fetchAll();
            $columns = count($query) ? array_keys(current($query)) : [];

            // Build and return table
            return new Table($columns, array_map('array_values', $query));

        } catch (\PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }
}
