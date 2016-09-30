<?php

namespace Flamingo\Utility;

/**
 * Class ConfUtility
 * @package Flamingo\Utility
 */
abstract class ConfUtility
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
            if (ArrayUtility::inList($extension, $extensions)) {
                return $reader;
            }
        }

        return false;
    }
}