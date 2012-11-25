<?php

namespace CLI\Command\Cache;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends Command
{

    protected function configure()
    {
        $this
          ->setName('cache:clear')
          ->setDescription('Clears application cache')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<header>Clearing cache</header>");

        $fs = new Filesystem();
        if($fs->exists(SYSTEM . DIRECTORY_SEPARATOR . 'cache')){
           // Init Cache
            $config = require(SYSTEM . '/configs/config.php');
            \Cache::setConfig($config['cache']);
            \Cache::setCacheDir(SYSTEM . DIRECTORY_SEPARATOR . 'cache');
            \Cache::setPublicCacheDir(WEBROOT . DIRECTORY_SEPARATOR . 'cached');
            // Clear the cache
            \Cache::clearAllCaches();
            $output->writeln("<info>cache cleared</info>");
        }else{
            $output->writeln("<error>cache dir does not exist</error>");
        }


    }


}