<?php

require __DIR__ . '/../vendor/autoload.php';

use Analog\Analog;
use Commando\Command;
use Flamingo\ErrorHandler;
use Flamingo\Flamingo;

// Create command controller and register options
$command = new Command;

$command
    ->option('v')
    ->aka('version')
    ->describedAs('Output version information and exit.')
    ->boolean();

$command
    ->option('f')
    ->aka('file')
    ->describedAs('Use a custom configuration file.');

$command
    ->option()
    ->describedAs('Name of the task to execute.');

// Register error handler
Analog::handler(ErrorHandler::init(false));

// Create base task runner
$flamingo = new Flamingo(file_get_contents(__DIR__ . '/default.yml'));

// Output executable version
if ($command['version']) {
    $appConf = $GLOBALS['FLAMINGO']['CONF']['App'];
    echo $appConf['Name'] . ' ' . $appConf['Version'] . PHP_EOL;
    exit;
}

// Get configuration file from option or current dir
$configurationFile = $command['file'] ?: getcwd() . '/flamingo.yml';

// No configuration file found
if (!file_exists($configurationFile)) {

    echo $command['file']
        ? sprintf('The configuration file "%s" does not exist!', $command['file'])
        : sprintf('No flamingo.yml file found in %s', getcwd());

    echo PHP_EOL;
    exit;
}

// Add project configuration
$flamingo->addConfiguration(file_get_contents($configurationFile));

// Register error handler
Analog::handler(ErrorHandler::init($GLOBALS['FLAMINGO']['CONF']['Log']['Debug']));

// Run defined task
$flamingo->run($command[0] ?: 'default');
