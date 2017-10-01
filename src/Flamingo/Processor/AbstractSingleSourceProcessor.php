<?php

namespace Flamingo\Processor;

use Flamingo\Core\Table;
use Flamingo\Core\TaskRuntime;

/**
 * Class AbstractSingleSourceProcessor
 * @package Flamingo\Processor
 */
abstract class AbstractSingleSourceProcessor extends AbstractProcessor
{
    /**
     * The operator which is used to declare source identifier
     */
    const IDENTIFIER_OPERATOR = '__source';

    /**
     * Default source identifier (usually the first available)
     */
    const IDENTIFIER_DEFAULT = 0;

    /**
     * Process data tables using custom functions
     *
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    abstract protected function processSource(Table $source, TaskRuntime $taskRuntime);

    /**
     * Get identifier from configuration
     * Process selected source
     * TODO: If source is unknown, throw an error
     *
     * @param TaskRuntime $taskRuntime
     * @return mixed
     */
    public function execute(TaskRuntime $taskRuntime)
    {
        $identifier = $this->configuration[self::IDENTIFIER_OPERATOR] ?: self::IDENTIFIER_DEFAULT;
        unset($this->configuration[self::IDENTIFIER_OPERATOR]);
        $this->processSource($taskRuntime->getTableByIdentifier($identifier), $taskRuntime);

        return 0;
    }
}