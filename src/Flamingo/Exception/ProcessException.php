<?php

namespace Flamingo\Exception;

/**
 * Class ProcessException
 * @package Flamingo\Exception
 */
class ProcessException extends \Exception
{
    /**
     * ProcessException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }
}