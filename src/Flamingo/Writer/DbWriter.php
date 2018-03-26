<?php

namespace Flamingo\Writer;

use Analog\Analog;
use Flamingo\Table;
use Flamingo\Utility\StatementUtility;

/**
 * Class DbWriter
 * @package Flamingo\Writer
 */
class DbWriter implements WriterInterface
{
    /**
     * @var \PDO
     */
    protected $pdo = null;

    /**
     * @var Table
     */
    protected $table = null;

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
        'unique' => [],
    ];

    /**
     * AbstractFileWriter constructor.
     * @param Table $table
     * @param array $options
     */
    public function __construct(Table $table, array $options)
    {
        $this->table = $table;
        $this->options = array_replace($this->options, $options);
    }

    /**
     * TODO: Prepare only one statement, execute and commit changes in db afterwards
     * TODO: Create common trait for DbReader and DbWriter (PDO wise)
     * TODO: Add more debug output
     *
     * @param string $tableName
     */
    public function save($tableName)
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
            Analog::error('PDO: ' . $e->getMessage());
        }

        if (empty($this->options['table'])) {
            Analog::debug('No destination table defined');
        }

        try {

            Analog::debug(sprintf(
                'Prepare %s records for %s.%s',
                count($this->table),
                $this->options['database'],
                $this->options['table']
            ));

            if (count($this->options['unique'])) {
                Analog::debug(sprintf(
                    'Use unique constraint on columns : %s',
                    implode(', ', $this->options['unique'])
                ));
            }

            // Execute queries
            foreach ($this->table as $record) {
                if ($this->itemExists($record)) {
                    $this->updateItem($record);
                } else {
                    $this->insertItem($record);
                }
            }

        } catch (\PDOException $e) {

            if (isset($request) && $request instanceof \PDOStatement) {
                Analog::debug('PDO: ' . $request->queryString);
            }

            Analog::error('PDO: ' . $e->getMessage());
        }
    }

    /**
     * Check if a record already exists
     *
     * @param array $record
     * @return bool
     */
    protected function itemExists($record)
    {
        // No unique field has been set
        if (count($this->options['unique']) == 0) {
            return false;
        }

        // Get values from unique constraint
        $unique = array_intersect_key(
            $record, array_flip($this->options['unique'])
        );

        // Create statement
        $statement = sprintf(
            'SELECT * FROM %s WHERE %s',
            $this->options['table'],
            StatementUtility::equals($unique, $this->options['table'], ' AND ')
        );

        // Execute and return found records
        $request = $this->pdo->prepare($statement);
        $request->execute(array_values($unique));

        return $request->rowCount() > 0;
    }

    /**
     * Insert a new record
     *
     * @param array $record
     */
    protected function insertItem($record)
    {
        // Build query
        $statement = sprintf(
            'INSERT %s INTO %s (%s) VALUES (%s)',
            $GLOBALS['FLAMINGO']['Options']['Sql']['InsertIgnore'] ? 'IGNORE' : '',
            $this->options['table'],
            StatementUtility::keys($record, $this->options['table']),
            StatementUtility::values($record)
        );

        // Execute insert
        $request = $this->pdo->prepare($statement);
        $request->execute(array_values($record));
    }

    /**
     * Update an existing record based on unique fields
     *
     * @param array $record
     */
    protected function updateItem($record)
    {
        // Fetch unique data
        $unique = array_intersect_key(
            $record, array_flip($this->options['unique'])
        );

        // Remove unique fields if needed
        if (count($unique)) {
            $record = array_diff_key(
                $record, array_flip($this->options['unique'])
            );
        }

        // No columns to update
        if (count($record) == 0) {
            return;
        }

        // Build query
        $statement = sprintf(
            'UPDATE %s SET %s',
            $this->options['table'],
            StatementUtility::equals($record, $this->options['table'])
        );

        // Add where constraint based on unique fields
        if (count($unique)) {
            $statement .= ' WHERE ' . StatementUtility::equals($unique, $this->options['table'], ' AND ');
        }

        // Execute update
        $request = $this->pdo->prepare($statement);
        $request->execute(array_merge(array_values($record), array_values($unique)));
    }
}
