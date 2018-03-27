<?php

namespace Flamingo;

use Analog\Analog;
use Flamingo\Service\TaskNameParser;

/**
 * Class Flamingo
 * @package Flamingo
 */
class Flamingo
{
    /**
     * @var array
     */
    protected $options = [

        // Flamingo application version
        // TODO; Get version from composer.json
        'Version' => '2.0.0',

        // Parsers allowed file extensions
        'FileProcessorExtensions' => [
            'csv' => 'Csv',
            'xls' => 'Spreadsheet',
            'xlsx' => 'Spreadsheet',
            'ods' => 'Spreadsheet',
            'json' => 'Json',
            'js' => 'Json',
            'xml' => 'Xml',
            'yaml' => 'Yaml',
            'yml' => 'Yaml',
        ]
    ];

    /**
     * Flamingo constructor.
     * Load Flamingo options into $GLOBALS.
     * TODO: Add support for additional configuration?
     */
    public function __construct()
    {
        $GLOBALS['FLAMINGO'] = $this->options;
    }

    /**
     * @param Task|Callable|string $task
     * @param array $arguments
     */
    public function run($task, array $arguments = [])
    {
        Analog::info(sprintf('Running "%s"...', $task));
        $startTime = microtime(true);

        $parser = new TaskNameParser($task);
        $className = $parser->getClass();
        $method = $parser->getMethod();

        if ($filename = $parser->getFilename()) {
            require_once $filename;
        }

        if ($className) {
            if ($method) {
                call_user_func_array([$className, $method], $arguments);
            } else {
                // TODO: Check for __invoke method
                call_user_func_array(new $className, $arguments);
            }
        } else {
            if ($method) {
                call_user_func_array($method, $arguments);
            }
        }

        Analog::info(sprintf('Finished "%s" in %fs', $task, microtime(true) - $startTime));
    }
}
