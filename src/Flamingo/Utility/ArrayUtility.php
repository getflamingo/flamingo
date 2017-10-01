<?php

namespace Flamingo\Utility;

/**
 * Class ArrayUtility
 * This file is part of the TYPO3 CMS project.
 * https://github.com/TYPO3/TYPO3.CMS/blob/master/typo3/sysext/core/Classes/Utility/ArrayUtility.php
 *
 * @package Flamingo\Utility
 */
class ArrayUtility
{
    /**
     * Recursively remove keys if their value are NULL.
     *
     * @param array $array
     * @return array
     */
    public static function removeNullValuesRecursive(array $array)
    {
        $result = $array;

        foreach ($result as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::removeNullValuesRecursive($value);
            } elseif ($value === null) {
                unset($result[$key]);
            }
        }

        return $result;
    }

    /**
     * Merges two arrays recursively and "binary safe" (integer keys are
     * overridden as well), overruling similar values in the original array
     * with the values of the overrule array.
     * In case of identical keys, ie. keeping the values of the overrule array.
     *
     * This method takes the original array by reference for speed optimization with large arrays
     *
     * The differences to the existing PHP function array_merge_recursive() are:
     *  * Keys of the original array can be unset via the overrule array. ($enableUnsetFeature)
     *  * Much more control over what is actually merged. ($addKeys, $includeEmptyValues)
     *  * Elements or the original array get overwritten if the same key is present in the overrule array.
     *
     * @param array $original Original array. It will be *modified* by this method and contains the result afterwards!
     * @param array $overrule Overrule array, overruling the original array
     * @param bool $addKeys If set to FALSE, keys that are NOT found in $original will not be set. Thus only existing value can/will be overruled from overrule array.
     * @param bool $includeEmptyValues If set, values from $overrule will overrule if they are empty or zero.
     * @param bool $enableUnsetFeature If set, special values "__UNSET" can be used in the overrule array in order to unset array keys in the original array.
     */
    public static function mergeRecursiveWithOverrule(array &$original, array $overrule, $addKeys = true, $includeEmptyValues = true, $enableUnsetFeature = true)
    {
        foreach ($overrule as $key => $_) {
            if ($enableUnsetFeature && $overrule[$key] === '__UNSET') {
                unset($original[$key]);
                continue;
            }
            if (isset($original[$key]) && is_array($original[$key])) {
                if (is_array($overrule[$key])) {
                    self::mergeRecursiveWithOverrule($original[$key], $overrule[$key], $addKeys, $includeEmptyValues, $enableUnsetFeature);
                }
            } elseif (
                ($addKeys || isset($original[$key])) &&
                ($includeEmptyValues || $overrule[$key])
            ) {
                $original[$key] = $overrule[$key];
            }
        }
        // This line is kept for backward compatibility reasons.
        reset($original);
    }

    /**
     * Returns a value by given path
     *
     * Example
     * - array:
     * array(
     *   'foo' => array(
     *     'bar' => array(
     *       'baz' => 42
     *     )
     *   )
     * );
     * - path: foo/bar/baz
     * - return: 42
     *
     * If a path segments contains a delimiter character, the path segment
     * must be enclosed by " (double quote), see unit tests for details
     *
     * @param array $array Input array
     * @param array|string $path Path within the array
     * @param string $delimiter Defined path delimiter, default /
     * @return mixed
     * @throws \RuntimeException if the path is empty, or if the path does not exist
     * @throws \InvalidArgumentException if the path is neither array nor string
     */
    public static function getValueByPath(array $array, $path, $delimiter = '/')
    {
        // Extract parts of the path
        if (is_string($path)) {
            if ($path === '') {
                throw new \RuntimeException('Path must not be empty', 1341397767);
            }
            $path = str_getcsv($path, $delimiter);
        } elseif (!is_array($path)) {
            throw new \InvalidArgumentException('getValueByPath() expects $path to be string or array, "' . gettype($path) . '" given.', 1476557628);
        }
        // Loop through each part and extract its value
        $value = $array;
        foreach ($path as $segment) {
            if (array_key_exists($segment, $value)) {
                // Replace current value with child
                $value = $value[$segment];
            } else {
                // Fail if key does not exist
                throw new \RuntimeException('Path does not exist in array', 1341397869);
            }
        }
        return $value;
    }

    /**
     * CLean split of values
     *
     * @param string $delimiter
     * @param string $list
     * @return array
     */
    public static function trimsplit($delimiter, $list)
    {
        return array_map('trim', explode($delimiter, $list));
    }

    /**
     * Check if a value is in a coma separated list
     * Note: Does not work with spaces and tabs
     *
     * @param mixed $value
     * @param string $list
     * @return bool
     */
    public static function inList($value, $list)
    {
        $list = ArrayUtility::trimsplit(',', $list);
        return in_array($value, $list);
    }

    /**
     * Add a prefix to array keys
     *
     * @param array $array
     * @param string $prefix
     * @return array
     */
    public static function prefixKeys(&$array, $prefix = '')
    {
        $newKeys = array_map(function ($key) use ($prefix) {
            return $prefix . $key;
        }, array_keys($array));

        return array_combine($newKeys, $array);
    }
}