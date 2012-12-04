<?php

namespace CLI\Command\Permissions;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Fixes application permissions
 *
 * @package CLI
 * @subpackage Permissions
 */
class SetCommand extends Command
{

    protected function configure()
    {
        $this->setName('permissions:set');
        $this->setDescription('Fixes file / directory permissions');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Collect info
        $dialog = $this->getHelperSet()->get('dialog');
        $ownread = $dialog->ask($output, '<question>What should the owner for readable files be?</question> [username] ', 'username');
        $grpread = $dialog->ask($output, '<question>What should the group for readable directories be?</question> [usergroup] ', 'usergroup');
        $ownwrite = $dialog->ask($output, '<question>What should the owner for writable files be?</question> [www-data] ', 'www-data');
        $grpwrite = $dialog->ask($output, '<question>What should the group for writable directories be?</question> [www-data] ', 'www-data');

        // Set permissions
        $fs = new Filesystem();

        // Set Readable directory owners
        $fs->chown(WEBROOT, $ownread, true);
        $fs->chgrp(WEBROOT, $grpread, true);
        $fs->chown(SYSTEM, $ownread, true);
        $fs->chgrp(SYSTEM, $grpread, true);

        // Make sure writable directories exist
        if (!$fs->exists(SYSTEM . DIRECTORY_SEPARATOR . 'cache')) {
            $fs->mkdir(SYSTEM . DIRECTORY_SEPARATOR . 'cache', 0777);
        }
        if (!$fs->exists(SYSTEM . DIRECTORY_SEPARATOR . 'logs')) {
            $fs->mkdir(SYSTEM . DIRECTORY_SEPARATOR . 'logs', 0777);
        }
        if (!$fs->exists(SYSTEM . DIRECTORY_SEPARATOR . 'userfiles')) {
            $fs->mkdir(SYSTEM . DIRECTORY_SEPARATOR . 'userfiles', 0777);
        }
        if (!$fs->exists(WEBROOT . DIRECTORY_SEPARATOR . 'userfiles')) {
            $fs->mkdir(WEBROOT . DIRECTORY_SEPARATOR . 'userfiles', 0777);
        }

        // Set writable directory owners
        $fs->chown(WEBROOT . DIRECTORY_SEPARATOR . 'userfiles', $ownwrite, true);
        $fs->chgrp(WEBROOT . DIRECTORY_SEPARATOR . 'userfiles', $grpwrite, true);
        $fs->chown(SYSTEM . DIRECTORY_SEPARATOR . 'userfiles', $ownwrite, true);
        $fs->chgrp(SYSTEM . DIRECTORY_SEPARATOR . 'userfiles', $grpwrite, true);
        $fs->chown(SYSTEM . DIRECTORY_SEPARATOR . 'cache', $ownwrite, true);
        $fs->chgrp(SYSTEM . DIRECTORY_SEPARATOR . 'cache', $grpwrite, true);
        $fs->chown(SYSTEM . DIRECTORY_SEPARATOR . 'logs', $ownwrite, true);
        $fs->chgrp(SYSTEM . DIRECTORY_SEPARATOR . 'logs', $grpwrite, true);

        // Set permissions
        $fs->chmod(WEBROOT, 0644, true);
        $fs->chmod(SYSTEM, 0644, true);

        $fs->chmod(WEBROOT . DIRECTORY_SEPARATOR . 'userfiles', 0644, true);
        $fs->chmod(SYSTEM . DIRECTORY_SEPARATOR . 'userfiles', 0644, true);
        $fs->chmod(SYSTEM . DIRECTORY_SEPARATOR . 'cache', 0600, true);
        $fs->chmod(SYSTEM . DIRECTORY_SEPARATOR . 'logs', 0644, true);
    }

}
