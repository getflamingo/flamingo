<?php

namespace Flamingo;

use Analog\Analog;

/**
 * Class ErrorHandler
 *
 * DEBUG level is configurable using /Conf/Log/Debug in YAML
 * ERROR must kill the script execution
 *
 * @package Flamingo
 */
class ErrorHandler
{
    private static $codeName = [
        Analog::DEBUG => 'DEBUG',
        Analog::INFO => 'INFO',
        Analog::NOTICE => 'INFO',
        Analog::WARNING => 'WARN',
        Analog::ERROR => 'ERROR',
        Analog::CRITICAL => 'ERROR',
        Analog::ALERT => 'ERROR',
        Analog::URGENT => 'ERROR'
    ];

    /**
     * Handles errors output
     *
     * @param bool $debug
     * @param bool $force
     * @return callable
     */
    public static function init($debug = false, $force = false)
    {
        return function ($error) use ($debug, $force) {

            if (!$debug && $error['level'] == Analog::DEBUG) {
                return;
            }

            $code = ErrorHandler::$codeName[$error['level']];
            echo '[' . $code . '] ' . $error['message'] . PHP_EOL;

            if (!$force && $error['level'] == Analog::ERROR) {
                die;
            }
        };
    }
}