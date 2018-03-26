<?php

namespace Flamingo\Reader;

use Analog\Analog;
use Flamingo\Exception\RuntimeException;
use Flamingo\Table;

/**
 * Class DbReader
 * @package Flamingo\Reader
 */
class DbReader implements ReaderInterface
{
    /**
     * @var \PDO
     */
    protected $pdo = null;

    /**
     * @var array
     */
    protected $options = [
        'driver' => 'mysql',
        'server' => 'localhost',
        'port' => 3306,
        'username' => 'root',
        'password' => '',
        'database' => '',
        'charset' => 'UTF8',
    ];

    /**
     * DbReader constructor.
     * @param array $options
     * @throws RuntimeException
     */
    public function __construct(array $options)
    {
        $this->options = array_replace($this->options, $options);

        $properties = [
            'host' => $this->options['server'],
            'port' => $this->options['port'],
            'dbname' => $this->options['database'],
            'charset' => $this->options['charset'],
        ];

        try {

            $this->pdo = new \PDO(
                $this->options['driver'] . ':' . http_build_query($properties, null, ';'),
                $this->options['username'],
                $this->options['password']
            );

            // PDO should throw exceptions on error
            // Need to be handled by Analog though
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * @param string $tableName
     * @return Table
     * @throws RuntimeException
     */
    public function load($tableName)
    {
        if (empty($tableName)) {
            throw new RuntimeException('No table name defined');
        }

        try {

            Analog::debug(sprintf('Building query from table name - "%s"', $tableName));
            $query = 'SELECT * FROM ' . $tableName;

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
