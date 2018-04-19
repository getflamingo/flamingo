<?php

namespace Flamingo\Traits;

use Flamingo\Exception\RuntimeException;

/**
 * Trait DatabaseAccessTrait
 * @package Flamingo\Traits
 */
trait DatabaseAccessTrait
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
     * Initialize the PDO instance using local and custom settings.
     *
     * @throws RuntimeException
     */
    protected function initializeObject()
    {
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
}
