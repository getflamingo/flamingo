<?php

namespace Flamingo\Utility;

/**
 * Class Iterator
 * @package Flamingo\Utility
 */
abstract class Iterator
{
    /**
     * array_merge_recursive does indeed merge arrays, but it converts values with duplicate
     * keys to arrays rather than overwriting the value in the first array with the duplicate
     * value in the second array, as array_merge does.
     *
     * @param array $a
     * @param array $b
     * @return array
     */
    public static function merge(array &$a, array &$b)
    {
        $merged = $a;

        foreach ($b as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = Iterator::merge($merged[$key], $value);
            } else {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }

    /**
     * CLean split of values
     *
     * @param string $delimiter
     * @param array $array
     * @return array
     */
    public static function trimsplit($delimiter, $array)
    {
        return array_map('trim', explode($delimiter, $array));
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
        $list = Iterator::trimsplit(',', $list);
        return in_array($value, $list);
    }
}