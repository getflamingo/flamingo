<?php

namespace Flamingo\Utility;

/**
 * Class StatementUtility
 * @package Flamingo\Utility
 */
class StatementUtility
{
    /**
     * @var string
     */
    const OPERATOR_AND = ' AND ';

    /**
     * @var string
     */
    const OPERATOR_OR = ' OR ';

    /**
     * Return an array of formatted value constraints
     *
     * @param array $record
     * @param string $delimiter
     * @return string
     */
    public static function equals($record, $delimiter = ',')
    {
        return implode($delimiter, array_map(function ($k) {
            return $k . '=?';
        }, array_keys($record)));
    }

    /**
     * Replace values by question marks
     *
     * @param array $record
     * @return string
     */
    public static function values($record)
    {
        return substr(str_repeat('?,', count($record)), 0, -1);
    }

    /**
     * Return a list of keys
     *
     * @param array $record
     * @return string
     */
    public static function keys($record)
    {
        return implode(',', array_keys($record));
    }
}