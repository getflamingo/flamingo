<?php

/**
 * Class SimpleExampleTask.
 */
class SimpleExampleTask extends \Flamingo\Task
{
    /**
     * Transform a CSV file into a JSON one.
     */
    public function __invoke()
    {
        $this
            ->read('examples/Fixtures/Transactions.csv')
            ->write('Transactions.json');
    }
}
