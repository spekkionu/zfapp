<?php

namespace CLI\Command\Migrate;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run Doctine Migrations
 */
class RunCommand extends Command
{

    protected function configure()
    {
        $this->setName('migrate:run');
        $this->setDescription('Runs database migration');
        $this->addArgument('version', InputArgument::OPTIONAL, 'What version do you want to migrate to?', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $version = $input->getArgument('version');
        if (!is_null($version)) {
            if (!is_numeric($version)) {
                $version = null;
            } else {
                // Version was provided
                $version = intval($version);
            }
        }
        $output->writeln("<header>Migrating Database</header>");

        if (!defined('DISABLE_CACHES')) {
            define("DISABLE_CACHES", true);
        }
        require_once(SCRIPTPATH . '/doctrine/config.php');
        $migration = new \Doctrine_Migration(MIGRATIONS_PATH, $conn);
        $current = $migration->getCurrentVersion();
        $latest = $migration->getLatestVersion();
        if (is_null($version) && $current == $latest) {
            $output->writeln("<error>Database is already at version {$latest}</error>");
            return;
        }
        if ($version > $latest) {
            $output->writeln("<error>The latest version is {$latest}</error>");
            return;
        }
        if ($current == $version && $current > 0) {
            $output->writeln("<error>Database is already at version {$version}</error>");
            return;
        }
        if (is_null($version)) {
            $output->writeln("<comment>From version {$current} to {$latest}</comment>");
        } else {
            $output->writeln("<comment>From version {$current} to {$version}</comment>");
        }
        $result = $migration->migrate($version);
        if ($result !== false) {
            $output->writeln("<info>Successfully migrated to version {$result}</info>");
            $output->writeln("<comment>Recommended to clear cache.</comment>");
        }
    }
}
