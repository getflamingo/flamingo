<?php

// TODO: Move this into Flamingo/Flamingo class

return [

    // Flamingo application version
    // TODO; Get version from composer.json
    'Version' => '2.0.0',

    // Minimum version needed in order to run your custom configuration
    // Need to be a standard format: http://php.net/manual/fr/function.version-compare.php
    // TODO: Implement a range support to prevent backward compat
    'RequiredVersion' => '0',

    // Determine if the current script should be at the root level of cwd
    // FALSE: Set the executable working directory as root level if not already defined (default)
    // TRUE: Set the configuration file dir as root level, all the relative resources will be resolved from that folder
    // STRING: Set a custom path to handle, must be absolute
    'Root' => false,

    // Implementation classes overrides
    // TODO: Remove this? or implement it in another way
    'Classes' => [],

    // Additional global options
    // These can are accessible through $GLOBALS
    'Options' => [

        // Keep old fields after new ones are mapped
        'Mapping' => [
            'KeepOldProperties' => false,
        ],

        // Don't apply processor if field does not exist
        // TODO: Deprecated?
        'Transform' => [
            'PropertyMustExist' => false,
        ],

        // Trim header keys and use the first line
        // Useful if you have a column name on 2 lines (separated by EOL)
        'Header' => [
            'FirstLine' => true,
        ],

        // Execute inserts with ignore clause
        // Can be useful to ignore NULL values
        'Sql' => [
            'InsertIgnore' => false,
        ],

        // Parsers allowed file extensions
        'FileProcessorExtensions' => [
            'csv' => 'Csv',
            'xls' => 'Spreadsheet',
            'xlsx' => 'Spreadsheet',
            'ods' => 'Spreadsheet',
            'json' => 'Json',
            'js' => 'Json',
            'xml' => 'Xml',
            'yaml' => 'Yaml',
            'yml' => 'Yaml',
        ]
    ],
];