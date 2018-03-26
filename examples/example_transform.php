<?php

/**
 * Class TransformExampleTask
 */
class TransformExampleTask extends \Flamingo\Task
{
    /**
     * Transform a CSV file into a JSON one.
     */
    public function __invoke()
    {
        $data = $this->read('examples/Fixtures/Transactions.csv');

        foreach ($data as &$row) {
            $row['price'] = (int)$row['price'] * 1.12;
        }

        $data = $this->map(
            $data,
            [
                'price' => 'PriceEUR',
                'sale_date' => 'Date',
            ]
        );

        $this->write($data, 'TransformedTransactions.json');
    }
}
