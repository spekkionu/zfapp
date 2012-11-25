<?php

namespace CLI\Command\Asset;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MinifyCommand extends Command
{

    protected function configure()
    {
        $this
          ->setName('asset:minify')
          ->setDescription('Minifies assets')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<header>Minify Assets</header>");




    }


}