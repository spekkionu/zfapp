<?php

namespace CLI\Command\Environment;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;

class CheckCommand extends Command
{

    protected function configure()
    {
        $this
          ->setName('environment:check')
          ->setDescription('Check environment')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // Check PHP Version
        $output->writeln("<header>Checking PHP version</header>");
        $output->write(PHP_VERSION);
        if (version_compare(PHP_VERSION, '5.3.7') < 0) {
            $output->writeln(": <error>PHP version must be at least 5.3.7</error>");
        } else {
            $output->writeln(": <info>OK</info>");
        }

        // Check for required php extensions
        $output->writeln("<header>Checking required PHP extensions</header>");
        $required = array(
          'curl', 'mysql', 'pdo_mysql', 'pdo_sqlite', 'json', 'mcrypt',
          'mbstring', 'hash', 'mhash', 'gd', 'dom', 'fileinfo', 'filter',
          'intl', 'iconv', 'libxml', 'openssl', 'pcre', 'soap', 'sockets',
          'xml', 'xmlreader', 'xmlrpc', 'xmlwriter'
        );
        foreach ($required as $extension) {
            $output->write($extension);
            if ($this->checkPHPExtension($extension)) {
                $output->writeln(": <info>OK</info>");
            } else {
                $output->writeln(": <error>Required extension {$extension} is missing</error>");
            }
        }
        // Check for optional php extensions
        $output->writeln("<header>Checking optional PHP extensions</header>");
        $optional = array(
          'apc', 'xcache', 'wincache', 'bz2', 'zip', 'mysqlnd', 'ssh2',
          'suhosin', 'tidy', 'uploadprogress', 'xhprof', 'zlib'
        );
        foreach ($optional as $extension) {
            $output->write($extension);
            if ($this->checkPHPExtension($extension)) {
                $output->writeln(": <info>OK</info>");
            } else {
                $output->writeln(": <comment>No</comment>");
            }
        }

        // Check Permissions
        $command = $this->getApplication()->find('permissions:check');
        $arguments = array(
            'command' => 'permissions:check',
        );
        $cinput = new ArrayInput($arguments);
        $returnCode = $command->run($cinput, $output);


    }

    /**
     * Checks if a php extension is installed and optionally is at a minimum version
     * @param string $exension The extension to check for
     * @param string $min_version The minimum version. Leave null for no version check
     */
    protected function checkPHPExtension($exension, $min_version = null)
    {
        if (!extension_loaded($exension)) {
            return false;
        }
        $version = phpversion($exension);
        if (version_compare($version, $min_version) < 0) {
            return false;
        } else {
            return true;
        }
    }

}