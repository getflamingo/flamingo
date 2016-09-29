<?php

require __DIR__ . '/../vendor/autoload.php';

$script = new \Flamingo\Core\Script(
    file_get_contents(__DIR__ . '/default.yml'),
    file_get_contents(getcwd() . '/flamingo.yml')
);

$script->run(!empty($argv[1]) ? $argv[1] : 'default');