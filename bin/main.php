<?php

require __DIR__ . '/../vendor/autoload.php';

// Create command controller and register options
$command = new \Commando\Command();

$command
    ->option('v')
    ->aka('version')
    ->describedAs('Output version information and exit.')
    ->boolean();

$command
    ->option('c')
    ->aka('config')
    ->describedAs('Use a custom configuration file.');

$command
    ->option('d')
    ->aka('debug')
    ->describedAs('Output debug information.')
    ->boolean();

$command
    ->option('f')
    ->aka('force')
    ->describedAs('Force execution of the tasks on error.')
    ->boolean();

$command
    ->option()
    ->describedAs('Name of the task to execute.');

// Register error handler
\Analog\Analog::handler(\Flamingo\Service\ErrorHandler::init($command['debug'], $command['force']));

// Create base task runner
$flamingo = new \Flamingo\Flamingo();
$flamingo->addConfiguration(file_get_contents(__DIR__ . '/DefaultConfiguration.yaml'));
$flamingo->addConfiguration(file_get_contents(__DIR__ . '/AdditionalConfiguration.yaml'));

// Output executable version
if ($command['version']) {
    $flamingo->parseConfiguration();
    echo $GLOBALS['FLAMINGO']['Version'] . PHP_EOL;
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

// Add custom configuration and parse the whole
$flamingo->addConfiguration(file_get_contents($configurationFile));
$flamingo->parseConfiguration();

// Run defined task
$flamingo->run($command[0] ?: 'default');
