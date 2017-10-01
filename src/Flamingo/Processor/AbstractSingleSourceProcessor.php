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
        if (
            is_array($this->configuration)
            && array_key_exists(self::IDENTIFIER_OPERATOR, $this->configuration)
        ) {
            $identifier = $this->configuration[self::IDENTIFIER_OPERATOR];
            unset($this->configuration[self::IDENTIFIER_OPERATOR]);
        } else {
            $identifier = self::IDENTIFIER_DEFAULT;
        }

        return $this->processSource($taskRuntime->getTableByIdentifier($identifier), $taskRuntime);
    }
}