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
        $this
            ->read('examples/Fixtures/Transactions.csv')
            ->mod(
                [
                    'price' => '(int)$price * 1.12',
                ]
            )
            ->map(
                [
                    'price' => 'PriceEUR',
                    'sale_date' => 'Date',
                ]
            )
            ->write('TransformedTransactions.json');
    }
}
