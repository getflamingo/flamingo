<?php

namespace Flamingo;

use Analog\Analog;
use Flamingo\Exception\RuntimeException;
use Flamingo\Service\TaskNameParser;

/**
 * Class Flamingo
 * @package Flamingo
 */
class Flamingo
{
    /**
     * @var string
     */
    protected $version = '@git-version@';

    /**
     * TODO: Hard-code the version in this file so it's available in included sources
     *
     * @return string
     * @throws RuntimeException
     */
    public function getVersion()
    {
        if ($this->version === '@' . 'git-version@') {
            throw new RuntimeException('The version could not be determined');
        }

        return $this->version;
    }

    /**
     * TODO: Add support for other taskName type (like callable)
     * TODO: Pipe errors and info so it can be handled by another logging app
     *
     * @param string $taskName
     * @param array $arguments
     * @throws RuntimeException
     */
    public function run($taskName, array $arguments)
    {
        if (empty($taskName)) {
            throw new RuntimeException('Please specify a task to execute...');
        }

        // Decode the given task
        $parser = new TaskNameParser($taskName);
        $className = $parser->getClass();
        $method = $parser->getMethod();

        Analog::info(sprintf('Running "%s"...', $className ?: $method));
        $startTime = microtime(true);

        // Run the task with arguments
        $parser->run($arguments);

        $elapsedTime = microtime(true) - $startTime;
        Analog::info(sprintf('Finished "%s" in %fs', $className ?: $method, $elapsedTime));
    }
}
