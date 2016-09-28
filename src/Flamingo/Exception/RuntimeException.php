<?php

namespace Flamingo\Exception;

/**
 * Class RuntimeException
 * @package Flamingo\Exception
 */
class RuntimeException extends \RuntimeException
{
    /**
     * RuntimeException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }
}