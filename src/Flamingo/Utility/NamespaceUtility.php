<?php

namespace Flamingo\Utility;

/**
 * Class NamespaceUtility
 *
 * Ths utility is used to identify custom classes in YAML configuration
 * Following this scheme: /Flamingo/Task/Default
 *
 * @package Flamingo\Utility
 */
abstract class NamespaceUtility
{
    /**
     * Check if the name is following the scheme structure
     * Use '*' value in namespace array to ignore comparison
     * Returns matches in an array
     *
     * Example:
     *  NamespaceUtility::matches('Flamingo/Task/Default', 'Flamingo/Task/*')
     *
     * @param string $name
     * @param string $scheme
     * @param bool $sensitive
     * @return array|null
     */
    public static function matches($name, $scheme, $sensitive = false)
    {
        // Removes slashes around
        $name = trim($name, '/');
        $scheme = trim($scheme, '/');

        // Split by slashes
        $name = explode('/', $name);
        $scheme = explode('/', $scheme);

        // Sizes are different
        if (count($name) != count($scheme)) {
            return null;
        }

        // Removes empty values
        $name = array_diff($name, ['']);
        $scheme = array_diff($scheme, ['']);
        $basename = $name;

        // To lowercase if needed
        if (!$sensitive) {
            $name = array_map('strtolower', $name);
            $scheme = array_map('strtolower', $scheme);
        }

        $matches = [];

        for ($i = 0, $l = count($name); $i < $l; $i++) {

            // Skip compare
            if ($scheme[$i] == '*') {
                $matches[] = $basename[$i];
                continue;
            }

            // Parts are different
            if ($name[$i] != $scheme[$i]) {
                return null;
            }
        }

        return $matches;
    }

    /**
     * Return file extension
     *
     * @param string $file
     * @return string
     */
    public static function getExtension($file)
    {
        $parts = explode('/', $file);
        $filename = array_pop($parts);
        $parts = explode('.', $filename);

        if (count($parts) < 2) {
            return false;
        }

        $extension = array_pop($parts);
        $basename = implode('.', $parts);

        return $extension;
    }
}