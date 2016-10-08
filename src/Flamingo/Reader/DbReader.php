<?php

namespace Flamingo\Reader;

use Analog\Analog;
use Flamingo\Core\Reader;
use Flamingo\Model\Table;

/**
 * Class DbReader
 * @package Flamingo\Reader
 */
class DbReader implements Reader
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @param array $options
     * @return \Flamingo\Model\Table
     */
    public function read($options)
    {
        $defaultOptions = [
            'driver' => 'mysql',
            'server' => 'localhost',
            'port' => 3306,
            'username' => 'root',
            'password' => '',
            'database' => '',
            'charset' => 'UTF8',
        ];

        $options = array_replace($defaultOptions, $options);

        $properties = [
            'host' => $options['server'],
            'port' => $options['port'],
            'dbname' => $options['database'],
            'charset' => $options['charset'],
        ];

        try {

            $this->pdo = new \PDO(
                $options['driver'] . ':' . http_build_query($properties, null, ';'),
                $options['username'],
                $options['password']
            );

            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            Analog::error('PDO: ' . $e->getMessage());
            return null;
        }

        if (!empty($options['table'])) {
            $query = "SELECT * FROM ". $options['table'];
            Analog::debug(sprintf('Building query from table name - "%s"', $query));
        }

        if (!empty($options['query'])) {
            $query = $options['query'];
            Analog::debug(sprintf('Using query from options - "%s"', $query));
        }

        if (empty($query)) {
            Analog::error(sprintf('No table name or query defined - %s', json_encode($options)));
            return null;
        }

        try {

            // Execute query
            $query = $this->pdo->query($query, \PDO::FETCH_ASSOC)->fetchAll();

            // Get columns
            $columns = count($query) ? array_keys(current($query)) : [];

            // Build and return table
            return new Table('', $columns, array_map('array_values', $query));

        } catch (\PDOException $e) {
            Analog::error('PDO: ' . $e->getMessage());
            return null;
        }
    }
}