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
     * @param array $configuration
     * @param Table $source
     * @param TaskRuntime $taskRuntime
     */
    abstract protected function processSource(array $configuration, Table &$source, TaskRuntime $taskRuntime);

    /**
     * Get identifier from configuration
     * Process selected source
     * If __source is not defined, throw an error
     *
     * @param array $configuration
     * @param TaskRuntime $taskRuntime
     * @return mixed
     */
    public function execute(array $configuration, TaskRuntime &$taskRuntime)
    {
        $identifier = $configuration[self::IDENTIFIER_OPERATOR] ?: self::IDENTIFIER_DEFAULT;
        $this->processSource($configuration, $taskRuntime->getTableByIdentifier($identifier), $taskRuntime);

        return 0;
    }
}