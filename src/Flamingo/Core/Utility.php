<?php

namespace Flamingo\Core;

/**
 * Class Utility
 * @package Flamingo\Utility
 */
class Utility
{
    /**
     * Split a class namespace into valid parts
     *
     * Example: /Flamingo/Model/Table
     * Result: [ Flamingo, Model, Table ]
     *
     * @param string $className
     * @return array
     */
    public static function classParts($className)
    {
        // Split by slash
        $parts = explode('/', $className);

        // Removes empty values
        $parts = array_diff($parts, ['']);

        // To lowercase
        $parts = array_map('strtolower', $parts);

        return $parts;
    }

    /**
     * Parse a task namespace and return its normal name
     *
     * Example: /Flamingo/Task/Default
     * Result: default
     *
     * @param string $taskName
     * @return string
     */
    public static function taskName($taskName)
    {
        // Split namespace
        $parts = Utility::classParts($taskName);

        // Format: Flamingo/Task/Test
        if (count($parts) === 3 &&
            $parts[0] === 'flamingo' &&
            $parts[1] === 'task' &&
            strlen($parts[2])
        ) {
            return $parts[2];
        }

        return false;
    }

    /**
     * Parse a task namespace and return its normal name
     *
     * Example: /Flamingo/Task/Default
     * Result: default
     *
     * @param string $processName
     * @return string
     */
    public static function processName($processName)
    {
        // Split namespace
        $parts = Utility::classParts($processName);

        // Format: Flamingo/Process/Test
        if (count($parts) === 3 &&
            $parts[0] === 'flamingo' &&
            $parts[1] === 'process' &&
            strlen($parts[2])
        ) {
            return $parts[2];
        }

        // Format: Test
        if (count($parts) === 1 &&
            strlen($parts[0])
        ) {
            return $parts[0];
        }

        return false;
    }
}