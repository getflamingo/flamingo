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
        $data = $this->createReader('Csv')->load('Fixtures/Transactions.csv');
        $this->createWriter('Json', $data)->save('Results/Transactions.json');
    }
}
