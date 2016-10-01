<?php

require __DIR__ . '/../vendor/autoload.php';

$flamingo = new \Flamingo\Core\Flamingo(
    file_get_contents(__DIR__ . '/default.yml')
);

$name = $GLOBALS['FLAMINGO']['CONF']['App']['Name'];
$version = $GLOBALS['FLAMINGO']['CONF']['App']['Version'];
$usage = $GLOBALS['FLAMINGO']['CONF']['App']['Usage'];

$flamingo->addConfiguration(
    file_get_contents(getcwd() . '/flamingo.yml')
);

if (in_array('-h', $argv) || in_array('--help', $argv)) {
    echo $usage;
    exit;
}

if (in_array('-v', $argv) || in_array('--version', $argv)) {
    echo $name . ' ' . $version;
    exit;
}

$flamingo->run(!empty($argv[1]) ? $argv[1] : 'default');