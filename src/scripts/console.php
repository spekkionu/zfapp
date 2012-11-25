<?php

/**
 * Base CLI Application
 *
 */
if (!defined('SYSTEM')) {
    define('SYSTEM', dirname(__DIR__).DIRECTORY_SEPARATOR.'system');
}

if (!defined('WEBROOT')) {
    // Set the webroot to the current dir
    define('WEBROOT', dirname(SYSTEM).DIRECTORY_SEPARATOR.'public_html');
}

if (!defined('PROJECT')) {
    define('PROJECT', dirname(SYSTEM));
}

// Change the directory to the app root
chdir(PROJECT);

// Set Include Path
set_include_path(
  // Application Library Files
  SYSTEM . DIRECTORY_SEPARATOR . 'library' . PATH_SEPARATOR .
  realpath(SYSTEM . '/library/vendor/digital-canvas/zend-framework/library')
);

// Init Autoloader
require(SYSTEM . '/library/vendor/autoload.php');

// Initialize CLI Application
$application = new Symfony\Component\Console\Application('ZF App');

// Add Commands
$application->add(new CLI\Command\Environment\CheckCommand);
$application->add(new CLI\Command\Environment\SetupCommand);
$application->add(new CLI\Command\Cache\ClearCommand);
$application->add(new CLI\Command\Permissions\CheckCommand);
$application->add(new CLI\Command\Permissions\SetCommand);
$application->add(new CLI\Command\Asset\MinifyCommand);
$application->add(new CLI\Command\Asset\BootstrapCommand);

// Configure styles
$output = new Symfony\Component\Console\Output\ConsoleOutput();
$style = new Symfony\Component\Console\Formatter\OutputFormatterStyle('blue', null, array('bold'));
$output->getFormatter()->setStyle('header', $style);

// Run CLI Application
$application->run(null, $output);

// Exit the app
exit(PHP_EOL);