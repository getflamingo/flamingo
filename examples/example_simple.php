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
        $data = $this->read('Fixtures/Transactions.csv');
        $this->write($data, 'Results/Transactions.json');
    }
}
