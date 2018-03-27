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

        $data->mod(
            [
                'price' => '(int){?} * 1.12',
            ]
        );

        $data->map(
            [
                'price' => 'PriceEUR',
                'sale_date' => 'Date',
            ]
        );

        $this->write($data, 'TransformedTransactions.json');
    }
}
