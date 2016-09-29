<?php

namespace Flamingo\Utility;

/**
 * Class Conf
 * @package Flamingo\Utility
 */
abstract class Conf
{
    /**
     * Return reader name associated to a file extension
     *
     * @param string $extension
     * @return string
     */
    public static function getReader($extension)
    {
        $readers = $GLOBALS['FLAMINGO']['CONF']['Reader'];

        foreach ($readers as $reader => $extensions) {
            if (Iterator::inList($extension, $extensions)) {
                return $reader;
            }
        }

        return false;
    }
}