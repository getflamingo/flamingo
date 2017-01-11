<?php
namespace Flamingo\Writer;

use Analog\Analog;
use Flamingo\Model\Table;
use Flamingo\Utility\StatementUtility;

/**
 * Class DbWriter
 * TODO: Prepare only one statement, execute and commit changes in db afterwards
 * TODO: Create common class for DbReader and DbWriter (PDO wise)
 * TODO: Add more debug output
 *
 * @package Flamingo\Writer
 */
class DbWriter implements WriterInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @param Table $table
     * @param array $options
     */
    public function write($table, $options)
    {
        $defaultOptions = [
            'driver' => 'mysql',
            'server' => 'localhost',
            'port' => 3306,
            'username' => 'root',
            'password' => '',
            'database' => '',
            'charset' => 'UTF8',
            'unique' => [],
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

            // PDO should throw exceptions on error
            // Need to be handled by Analog though
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (\PDOException $e) {
            Analog::error('PDO: ' . $e->getMessage());
        }

        if (empty($options['table'])) {
            Analog::debug('No destination table defined');
        }

        try {

            Analog::debug(sprintf(
                'Prepare %s records for %s.%s',
                count($table),
                $options['database'],
                $options['table']
            ));

            if (count($options['unique'])) {
                Analog::debug(sprintf(
                    'Use unique constraint on columns : %s',
                    implode(', ', $options['unique'])
                ));
            }

            // Execute queries
            foreach ($table as $record) {
                if ($this->itemExists($record, $options)) {
                    $this->updateItem($record, $options);
                } else {
                    $this->insertItem($record, $options);
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
     * @param array $options
     * @return bool
     */
    protected function itemExists($record, $options)
    {
        // No unique field has been set
        if (count($options['unique']) == 0) {
            return false;
        }

        // Get values from unique constraint
        $unique = array_intersect_key(
            $record, array_flip($options['unique'])
        );

        // Create statement
        $statement = sprintf(
            'SELECT * FROM %s WHERE %s',
            $options['table'],
            StatementUtility::equals($unique, ' AND ')
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
     * @param array $options
     */
    protected function insertItem($record, $options)
    {
        // Build query
        $statement = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $options['table'],
            StatementUtility::keys($record),
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
     * @param array $options
     */
    protected function updateItem($record, $options)
    {
        // Fetch unique data
        $unique = array_intersect_key(
            $record, array_flip($options['unique'])
        );

        // Remove unique fields if needed
        if (count($unique)) {
            $record = array_diff_key(
                $record, array_flip($options['unique'])
            );
        }

        // Build query
        $statement = sprintf(
            'UPDATE %s SET %s',
            $options['table'],
            StatementUtility::equals($record)
        );

        // Add where constraint based on unique fields
        if (count($unique)) {
            $statement .= ' WHERE ' . StatementUtility::equals($unique, ' AND ');
        }

        // Execute update
        $request = $this->pdo->prepare($statement);
        $request->execute(array_merge(array_values($record), array_values($unique)));
    }
}