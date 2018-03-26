<?php

namespace Flamingo\Service;

use Analog\Analog;

/**
 * Class ErrorHandler
 * @package Flamingo\Service
 */
class ErrorHandler
{
    /**
     * @var array
     */
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

            $code = self::$codeName[$error['level']];
            echo '[' . $code . '] ' . $error['message'] . PHP_EOL;

            if (!$force && $error['level'] == Analog::ERROR) {
                die;
            }
        };
    }
}
