<?php

namespace Flamingo\Writer;

use Analog\Analog;
use Flamingo\Exception\RuntimeException;
use Flamingo\Table;
use Flamingo\Traits\DatabaseAccessTrait;
use Flamingo\Utility\StatementUtility;

/**
 * Class DatabaseWriter
 * @package Flamingo\Writer
 */
class DatabaseWriter implements WriterInterface
{
    use DatabaseAccessTrait;

    /**
     * @var Table
     */
    protected $table = null;

    /**
     * DatabaseWriter constructor.
     * @param Table $table
     * @param array $options
     */
    public function __construct(Table $table, array $options)
    {
        $this->table = $table;
        $this->options = array_replace($this->options, $options);
        $this->initializeObject();
    }

    /**
     * @param string $tableName
     * @param array $unique
     */
    public function save($tableName, array $unique = [])
    {
        if (count($unique)) {
            $this->update($tableName, $unique);
        } else {
            $this->insert($tableName);
        }
    }

    /**
     * TODO: Prepare only one statement, execute and commit changes in db afterwards
     * TODO: Create common trait for DbReader and DbWriter (PDO wise)
     * TODO: Add more debug output
     *
     * @param string $tableName
     * @throws RuntimeException
     */
    public function insert($tableName)
    {
        if (empty($tableName)) {
            throw new RuntimeException('No destination table defined');
        }

        try {

            Analog::debug(sprintf(
                'Prepare %s records for %s.%s',
                $this->table->count(),
                $this->options['database'],
                $tableName
            ));

            // Execute queries
            foreach ($this->table as $record) {
                $this->insertItem($record, $tableName);
            }

        } catch (\PDOException $e) {

            if (isset($request) && $request instanceof \PDOStatement) {
                Analog::debug('PDO: ' . $request->queryString);
            }

            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * @param $tableName
     * @param array $unique
     */
    public function update($tableName, array $unique = [])
    {
        if (empty($tableName)) {
            throw new RuntimeException('No destination table defined');
        }

        try {

            Analog::debug(sprintf(
                'Prepare %s records for %s.%s',
                $this->table->count(),
                $this->options['database'],
                $tableName
            ));

            if (count($unique)) {
                Analog::debug(sprintf(
                    'Use unique constraint on columns : %s',
                    implode(', ', $unique)
                ));
            }

            // Execute queries
            foreach ($this->table as $record) {
                if ($this->itemExists($record, $tableName)) {
                    $this->updateItem($record, $tableName);
                } else {
                    $this->insertItem($record, $tableName);
                }
            }

        } catch (\PDOException $e) {

            if (isset($request) && $request instanceof \PDOStatement) {
                Analog::debug('PDO: ' . $request->queryString);
            }

            throw new RuntimeException($e->getMessage());
        }
    }

    /**
     * Check if a record already exists.
     *
     * @param array $record
     * @param string $tableName
     * @param array $unique
     * @return bool
     */
    protected function itemExists($record, $tableName, array $unique = [])
    {
        // No unique field has been set
        if (count($unique) == 0) {
            return false;
        }

        // Get values from unique constraint
        $unique = array_intersect_key($record, array_flip($unique));

        // Create statement
        $statement = sprintf(
            'SELECT * FROM %s WHERE %s',
            $tableName,
            StatementUtility::equals($unique, $tableName, ' AND ')
        );

        // Execute and return found records
        $request = $this->pdo->prepare($statement);
        $request->execute(array_values($unique));

        return $request->rowCount() > 0;
    }

    /**
     * Insert a new record.
     *
     * @param array $record
     * @param string $tableName
     */
    protected function insertItem($record, $tableName)
    {
        // Build query
        $statement = sprintf(
            'INSERT %s INTO %s (%s) VALUES (%s)',
            $GLOBALS['FLAMINGO']['Options']['Sql']['InsertIgnore'] ? 'IGNORE' : '',
            $tableName,
            StatementUtility::keys($record, $tableName),
            StatementUtility::values($record)
        );

        // Execute insert
        $request = $this->pdo->prepare($statement);
        $request->execute(array_values($record));
    }

    /**
     * Update an existing record based on unique fields.
     *
     * @param array $record
     * @param string $tableName
     * @param array $unique
     */
    protected function updateItem($record, $tableName, array $unique = [])
    {
        // Fetch unique data
        $uniqueConstraint = array_intersect_key($record, array_flip($unique));

        // Remove unique fields if needed
        if (count($uniqueConstraint)) {
            $record = array_diff_key($record, array_flip($unique));
        }

        // No columns to update
        if (count($record) === 0) {
            return;
        }

        // Build query
        $statement = sprintf(
            'UPDATE %s SET %s',
            $tableName,
            StatementUtility::equals($record, $tableName)
        );

        // Add where constraint based on unique fields
        if (count($uniqueConstraint)) {
            $statement .= ' WHERE ' . StatementUtility::equals($uniqueConstraint, $tableName, ' AND ');
        }

        // Execute update
        $request = $this->pdo->prepare($statement);
        $request->execute(array_merge(array_values($record), array_values($uniqueConstraint)));
    }
}
