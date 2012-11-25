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

class CheckCommand extends Command
{

    protected function configure()
    {
        $this
          ->setName('permissions:check')
          ->setDescription('Check file / directory permissions')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<header>Checking file permissions</header>");

        // Check File owner / group
        $dialog = $this->getHelperSet()->get('dialog');
        $ownread = $dialog->ask(
          $output, '<question>What should the owner for readable files be?</question> [username] ', 'username'
        );
        $grpread = $dialog->ask(
          $output, '<question>What should the group for readable directories be?</question> [usergroup] ', 'usergroup'
        );
        $ownwrite = $dialog->ask(
          $output, '<question>What should the owner for writable files be?</question> [www-data] ', 'www-data'
        );
        $grpwrite = $dialog->ask(
          $output, '<question>What should the group for writable directories be?</question> [www-data] ', 'www-data'
        );

        // Check webroot
        $output->writeln("<header>Checking webroot</header>");
        $file = new \SplFileInfo(WEBROOT);
        // owner
        $output->write("Owner");
        $owner = posix_getpwuid($file->getOwner());
        if ($ownread != $owner['name']) {
            $output->writeln(": <error>Webroot is owned by {$owner['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        // group
        $output->write("Group");
        $group = posix_getgrgid($file->getGroup());
        if ($grpread != $group['name']) {
            $output->writeln(": <error>Webroot is in group {$group['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        // permissions
        $output->write("Permissions");
        $perms = substr(sprintf('%o', $file->getPerms()), -4);
        if ($perms != "0775") {
            $output->writeln(": <error>Webroot should have permissions 0775</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }

        // SYSTEM
        $output->writeln("<header>Checking system folder</header>");
        $file = new \SplFileInfo(SYSTEM);
        // owner
        $output->write("Owner");
        $owner = posix_getpwuid($file->getOwner());
        if ($ownread != $owner['name']) {
            $output->writeln(": <error>System folder is owned by {$owner['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        // group
        $output->write("Group");
        $group = posix_getgrgid($file->getGroup());
        if ($grpread != $group['name']) {
            $output->writeln(": <error>System folder is in group {$group['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        // permissions
        $output->write("Permissions");
        $perms = substr(sprintf('%o', $file->getPerms()), -4);
        if ($perms != "0775") {
            $output->writeln(": <error>System folder should have permissions 0775</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }

        // Public Files
        $output->writeln("<header>Checking public user files</header>");
        $file = new \SplFileInfo(WEBROOT . DIRECTORY_SEPARATOR . 'userfiles');
        // owner
        $output->write("Owner");
        $owner = posix_getpwuid($file->getOwner());
        if ($ownwrite != $owner['name']) {
            $output->writeln(": <error>Userfiles folder is owned by {$owner['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        //group
        $output->write("Group");
        $group = posix_getgrgid($file->getGroup());
        if ($grpwrite != $group['name']) {
            $output->writeln(": <error>Userfiles folder is in group {$group['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        // permissions
        $output->write("Permissions");
        $perms = substr(sprintf('%o', $file->getPerms()), -4);
        if ($perms != "0777") {
            $output->writeln(": <error>Userfiles folder should have permissions 0777</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }

        // Private Files
        $output->writeln("<header>Checking private user files</header>");
        $file = new \SplFileInfo(SYSTEM . DIRECTORY_SEPARATOR . 'userfiles');
        // owner
        $output->write("Owner");
        $owner = posix_getpwuid($file->getOwner());
        if ($ownwrite != $owner['name']) {
            $output->writeln(": <error>Userfiles folder is owned by {$owner['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        //group
        $output->write("Group");
        $group = posix_getgrgid($file->getGroup());
        if ($grpwrite != $group['name']) {
            $output->writeln(": <error>Userfiles folder is in group {$group['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        // permissions
        $output->write("Permissions");
        $perms = substr(sprintf('%o', $file->getPerms()), -4);
        if ($perms != "0777") {
            $output->writeln(": <error>Userfiles folder should have permissions 0777</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }

        // Logs
        $output->writeln("<header>Checking log folder</header>");
        $file = new \SplFileInfo(SYSTEM . DIRECTORY_SEPARATOR . 'logs');
        // owner
        $output->write("Owner");
        $owner = posix_getpwuid($file->getOwner());
        if ($ownwrite != $owner['name']) {
            $output->writeln(": <error>Log folder is owned by {$owner['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        //group
        $output->write("Group");
        $group = posix_getgrgid($file->getGroup());
        if ($grpwrite != $group['name']) {
            $output->writeln(": <error>Log folder is in group {$group['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        // permissions
        $output->write("Permissions");
        $perms = substr(sprintf('%o', $file->getPerms()), -4);
        if ($perms != "0777") {
            $output->writeln(": <error>Log folder should have permissions 0777</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }

        // Cache
        $output->writeln("<header>Checking cache folder</header>");
        $file = new \SplFileInfo(SYSTEM . DIRECTORY_SEPARATOR . 'cache');
        // owner
        $output->write("Owner");
        $owner = posix_getpwuid($file->getOwner());
        if ($ownwrite != $owner['name']) {
            $output->writeln(": <error>Cache folder is owned by {$owner['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        //group
        $output->write("Group");
        $group = posix_getgrgid($file->getGroup());
        if ($grpwrite != $group['name']) {
            $output->writeln(": <error>Cache folder is in group {$group['name']}</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
        // permissions
        $output->write("Permissions");
        $perms = substr(sprintf('%o', $file->getPerms()), -4);
        if ($perms != "0777") {
            $output->writeln(": <error>Cache folder should have permissions 0777</error>");
        }else{
            $output->writeln(": <info>OK</info>");
        }
    }


}