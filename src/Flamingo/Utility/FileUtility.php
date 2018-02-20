<?php

namespace Flamingo\Utility;

/**
 * Class FileUtility
 * @package Flamingo\Utility
 */
class FileUtility
{
    /**
     * Resolves the absolute filename according to GLOBALS rootDir if defined.
     * This utility is mainly used by the Source and Destination processors.
     *
     * @see \Flamingo\Processor\Reader\AbstractFileReader::read
     * @see \Flamingo\Processor\Writer\AbstractFileWriter::write
     *
     * @param string $filename
     * @return string
     */
    public static function getAbsoluteFilename($filename)
    {
        if (self::isAbsolute($filename)) {
            return $filename;
        }

        return self::getRealPath(self::getRootDir() . DIRECTORY_SEPARATOR . $filename);
    }

    /**
     * Resolves the given path even if it does not exist.
     * @link http://php.net/manual/fr/function.realpath.php#84012
     *
     * @param string $path
     * @return string
     */
    public static function getRealPath($path)
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = [];

        foreach ($parts as $part) {
            if ('.' == $part) {
                continue;
            }
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }

        return DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $absolutes);
    }

    /**
     * Returns the current working directory.
     *
     * @return string
     */
    public static function getRootDir()
    {
        if (isset($GLOBALS['FLAMINGO']['rootDir'])) {
            return $GLOBALS['FLAMINGO']['rootDir'];
        }

        return getcwd();
    }

    /**
     * Returns TRUE if the given path is absolute.
     *
     * @param string $path
     * @return bool
     */
    public static function isAbsolute($path)
    {
        return substr($path, 0, 1) === DIRECTORY_SEPARATOR;
    }
}