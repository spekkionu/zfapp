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

class SetupCommand extends Command
{

    protected function configure()
    {
        $this
          ->setName('environment:setup')
          ->setDescription('Basic application setup')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<header>Create missing directrories</header>");
        $fs = new Filesystem();

        if (!$fs->exists(SYSTEM . DIRECTORY_SEPARATOR . 'cache')) {
            try {
                $fs->mkdir(SYSTEM . DIRECTORY_SEPARATOR . 'cache', 0777);
                $output->writeln("<info>Created cache directory</info>");
            } catch (Exception $e) {
                $output->writeln("<error>Failed to create cache directory</error>");
            }
        }
        if (!$fs->exists(SYSTEM . DIRECTORY_SEPARATOR . 'logs')) {
            try {
                $fs->mkdir(SYSTEM . DIRECTORY_SEPARATOR . 'logs', 0777);
                $output->writeln("<info>Created log directory</info>");
            } catch (Exception $e) {
                $output->writeln("<error>Failed to create log directory</error>");
            }
        }
        if (!$fs->exists(SYSTEM . DIRECTORY_SEPARATOR . 'userfiles')) {
            try {
                $fs->mkdir(SYSTEM . DIRECTORY_SEPARATOR . 'userfiles', 0777);
                $output->writeln("<info>Created private files directory</info>");
            } catch (Exception $e) {
                $output->writeln("<error>Failed to create private files directory</error>");
            }
        }
        if (!$fs->exists(WEBROOT . DIRECTORY_SEPARATOR . 'userfiles')) {
            try {
                $fs->mkdir(WEBROOT . DIRECTORY_SEPARATOR . 'userfiles', 0777);
                $output->writeln("<info>Created public files directory</info>");
            } catch (Exception $e) {
                $output->writeln("<error>Failed to create public files directory</error>");
            }
        }
        if (!$fs->exists(WEBROOT . DIRECTORY_SEPARATOR . 'cached')) {
            try {
                $fs->mkdir(WEBROOT . DIRECTORY_SEPARATOR . 'cached', 0777);
                $output->writeln("<info>Created page cache directory</info>");
            } catch (Exception $e) {
                $output->writeln("<error>Failed to create page cache directory</error>");
            }
        }

        $output->writeln("<header>Setup Config</header>");
        if ($fs->exists(SYSTEM . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'config.local.php')) {
            $output->writeln("<error>Config file aready exists</error>");
            $save_config = false;
        } else {
            $save_config = true;
        }
        $dialog = $this->getHelperSet()->get('dialog');
        if ($save_config) {
            // Load existing config
            $config = require(SYSTEM . '/configs/config.php');

            $output->writeln("<header>General Site Settings</header>");
            $local['{{site_name}}'] = $dialog->ask(
              $output, '<question>Site Name:</question> [' . $config['site']['name'] . '] ', $config['site']['name']
            );
            $local['{{site_domain}}'] = $dialog->ask(
              $output, '<question>Site Name:</question> [' . $config['site']['domain'] . '] ', $config['site']['domain']
            );
            $local['{{site_webroot}}'] = $config['site']['webroot'];

            $output->writeln("<header>Database Credentials</header>");
            $local['{{database_hostname}}'] = $dialog->ask(
              $output, '<question>Hostname:</question> [' . $config['database']['params']['host'] . '] ', $config['database']['params']['host']
            );
            $local['{{database_username}}'] = $dialog->ask(
              $output, '<question>Username:</question> [' . $config['database']['params']['username'] . '] ', $config['database']['params']['username']
            );
            $local['{{database_password}}'] = $dialog->ask(
              $output, '<question>Password:</question> [' . $config['database']['params']['password'] . '] ', $config['database']['params']['password']
            );
            $local['{{database_database}}'] = $dialog->ask(
              $output, '<question>Database Name:</question> [' . $config['database']['params']['dbname'] . '] ', $config['database']['params']['dbname']
            );
            $size = mcrypt_get_iv_size($config['security']['crypt']['algorithm'], $config['security']['crypt']['mode']);
            $key = mcrypt_create_iv($size, MCRYPT_DEV_URANDOM);
            $key = substr(strtr(base64_encode($key), '+', '.'), 0, $size);
            $local['{{crypt_key}}'] = $key;

            // Load Config Template
            $template = file_get_contents(SYSTEM.'/configs/.config-template.txt');
            // Replace Variables
            $template = str_replace(array_keys($local), array_values($local), $template);
            if(@file_put_contents(SYSTEM . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'config.local.php', $template) === FALSE){
                $output->writeln("<error>Failed to write local config file.</error>");
            }else{
                $output->writeln("<info>Saved local config file.</info>");
            }
        }
    }

}