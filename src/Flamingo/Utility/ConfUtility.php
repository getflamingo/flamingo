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
}