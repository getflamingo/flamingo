<?php

namespace Flamingo\Utility;

/**
 * Class ConfUtility
 * @package Flamingo\Utility
 */
abstract class ConfUtility
{
    /**
     * Return parser name associated to a file extension
     * A parser can be a Reader or Writer name
     *
     * @param string $extension
     * @return string
     */
    public static function getParser($extension)
    {
        $parsers = $GLOBALS['FLAMINGO']['CONF']['Parser'];

        foreach ($parsers as $parser => $extensions) {
            if (ArrayUtility::inList($extension, $extensions)) {
                return $parser;
            }
        }

        return false;
    }

    /**
     * Return error name
     *
     * @param integer $code
     * @return string
     */
    public static function errorName($code)
    {
        $errors = [
            E_USER_ERROR => 'Error',
            E_USER_WARNING => 'Warn',
        ];

        return array_key_exists($code, $errors) ? $errors[$code] : '';
    }
}