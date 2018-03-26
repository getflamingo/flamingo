<?php

/**
 * Class DefaultTask.
 */
class DefaultTask extends \Flamingo\Task
{
    /**
     * Transform a CSV file into a JSON one.
     */
    public function __invoke()
    {
//        $reader = $this->createReader('Csv');
//        $reader = $this->createReaderForFile('Fixtures/Transactions.csv');
//        $source = $reader->load('Fixtures/Transactions.csv');

        $source = $this->createReader('Csv')->load('Fixtures/Transactions.csv');
        $this->createWriter('Json', $source)->save('Results/Transactions.json');
    }
}
