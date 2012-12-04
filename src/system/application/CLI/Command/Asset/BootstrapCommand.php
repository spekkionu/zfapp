<?php

namespace CLI\Command\Asset;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Filter;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;

/**
 * Compiles Bootstap Assets
 *
 * @package CLI
 * @subpackage Asset
 */
class BootstrapCommand extends Command
{

    protected function configure()
    {
        $this->setName('asset:bootstrap');
        $this->setDescription('Compiles bootstrap assets.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<header>Compile Bootstrap Assets</header>");
        $fs = new Filesystem();
        if (!$fs->exists(WEBROOT . '/assets/styles/bootstrap')) {
            $fs->mkdir(WEBROOT . '/assets/styles/bootstrap');
        }
        try {
            $output->writeln("Building main bootstrap css file.");
            $input = array(new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/less/bootstrap.less'));
            $css = new AssetCollection($input, array(
                new Filter\LessphpFilter(),
                new Filter\CssImportFilter(),
                new Filter\Yui\CssCompressorFilter(realpath(PROJECT . '/scripts/tools/yuicompressor/yuicompressor.jar'), 'java'),
              ));
            if (!$fs->exists(WEBROOT . '/assets/styles/bootstrap/css')) {
                $fs->mkdir(WEBROOT . '/assets/styles/bootstrap/css');
            }

            file_put_contents(WEBROOT . "/assets/styles/bootstrap/css/bootstrap.min.css", $css->dump());
            $output->writeln("<info>Saved to assets/styles/bootstrap/css/bootstrap.min.css</info>");
        } catch (Exception $e) {
            $output->writeln("<error>Failed to build bootstrap.min.css</error>");
        }
        try {
            $output->writeln("Building responsive bootstrap css file.");
            $input = array(new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/less/responsive.less'));
            $css = new AssetCollection($input, array(
                new Filter\LessphpFilter(),
                new Filter\CssImportFilter(),
                new Filter\Yui\CssCompressorFilter(realpath(PROJECT . '/scripts/tools/yuicompressor/yuicompressor.jar'), 'java'),
              ));

            file_put_contents(WEBROOT . "/assets/styles/bootstrap/css/bootstrap-responsive.min.css", $css->dump());
            $output->writeln("<info>Saved to assets/styles/bootstrap/css/bootstrap-responsive.min.css</info>");
        } catch (Exception $e) {
            $output->writeln("<error>Failed to build bootstrap-responsive.min.css</error>");
        }

        try {
            $output->writeln("Build Bootstrap jQuery plugins.");
            $input = array(
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-affix.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-alert.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-button.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-carousel.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-collapse.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-dropdown.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-modal.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-popover.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-scrollspy.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-tab.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-tooltip.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-transition.js'),
              new FileAsset(WEBROOT . DIRECTORY_SEPARATOR . 'assets/vendor/bootstrap/js/bootstrap-typeahead.js'),
            );
            $output->writeln("Save full javascript file.");
            $js = new AssetCollection($input, array());
            if (!$fs->exists(WEBROOT . '/assets/styles/bootstrap/js')) {
                $fs->mkdir(WEBROOT . '/assets/styles/bootstrap/js');
            }
            file_put_contents(WEBROOT . "/assets/styles/bootstrap/js/bootstrap.js", $js->dump());
            $output->writeln("<info>Saved to assets/styles/bootstrap/js/bootstrap.js</info>");

            $output->writeln("Save minified javascript file to assets/styles/bootstrap/js/bootstrap.js.");
            $js = new AssetCollection($input, array(
                new Filter\GoogleClosure\CompilerJarFilter(realpath(PROJECT . '/scripts/tools/closure-compiler/compiler.jar'), 'java'),
              ));
            file_put_contents(WEBROOT . "/assets/styles/bootstrap/js/bootstrap.min.js", $js->dump());

            $output->writeln("<info>Saved to assets/styles/bootstrap/js/bootstrap.min.js</info>");
        } catch (Exception $e) {
            $output->writeln("<error>Failed to build bootstrap.js</error>");
        }

        $output->writeln("Copy image files.");
        if (!$fs->exists(WEBROOT . '/assets/styles/bootstrap/img')) {
            $fs->mkdir(WEBROOT . '/assets/styles/bootstrap/img');
        }
        $fs->mirror(WEBROOT . '/assets/vendor/bootstrap/img', WEBROOT . '/assets/styles/bootstrap/img');
    }

}