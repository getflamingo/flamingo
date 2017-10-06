<?php

namespace Flamingo\Processor\Reader;

use Analog\Analog;
use Flamingo\Core\Table;

/**
 * Class DbReader
 * @package Flamingo\Processor\Reader
 */
class DbReader implements ReaderInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var array
     */
    protected $defaultOptions = [
        'driver' => 'mysql',
        'server' => 'localhost',
        'port' => 3306,
        'username' => 'root',
        'password' => '',
        'database' => '',
        'charset' => 'UTF8',
    ];

    /**
     * @param array $options
     * @return Table
     */
    public function read(array $options)
    {
        // Overwrite default options
        $options = array_replace($this->defaultOptions, $options);

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

            // PDO should throw exceptions on error
            // Need to be handled by Analog though
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            Analog::error('PDO: ' . $e->getMessage());

            return null;
        }

        if (!empty($options['table'])) {
            $query = "SELECT * FROM " . $options['table'];
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

            Analog::debug(sprintf('Get records from DB - "%s"', $query));

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