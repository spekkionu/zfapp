<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   ZendX
 * @package    ZendX_Loader
 * @subpackage Exception
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Generate class maps for use with autoloading.
 *
 * Usage:
 * --help|-h                    Get usage message
 * --library|-l [ <string> ]    Library to parse; if none provided, assumes 
 *                              current directory
 * --output|-o [ <string> ]     Where to write autoload file; if not provided, 
 *                              assumes ".classmap.php" in library directory
 * --overwrite|-w               Whether or not to overwrite existing autoload 
 *                              file
 */
define('SYSTEM', realpath(dirname(__FILE__) . '/../src/system'));
$libPath = SYSTEM . DIRECTORY_SEPARATOR.'library';
// Set Include Path
set_include_path(
  // Application Library Files
  $libPath . PATH_SEPARATOR . 
  $libPath . DIRECTORY_SEPARATOR . 'vendor'.DIRECTORY_SEPARATOR.'Zend'
);
require(SYSTEM . '/library/vendor/ZendW/Loader/StandardAutoloader.php');

// Setup autoloading
$loader = new ZendW_Loader_StandardAutoloader();
$loader->setFallbackAutoloader(true);
$loader->register();

$rules = array(
    'help|h'        => 'Get usage message',
    'library|l-s'   => 'Library to parse; if none provided, assumes current directory',
    'output|o-s'    => 'Where to write autoload file; if not provided, assumes ".classmap.php" in library directory',
    'overwrite|w'   => 'Whether or not to overwrite existing autoload file',
);

try {
    $opts = new Zend_Console_Getopt($rules);
    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    exit(2);
}

if ($opts->getOption('h')) {
    echo $opts->getUsageMessage();
    exit();
}

$path = SYSTEM;
if (isset($opts->l)) {
    $path = $opts->l;
    if (!is_dir($path)) {
        echo "Invalid library directory provided" . PHP_EOL . PHP_EOL;
        echo $opts->getUsageMessage();
        exit(2);
    }
    $path = realpath($path);
}

$usingStdout = false;
$output = $libPath.DIRECTORY_SEPARATOR.'.classmap.php';
if (isset($opts->o)) {
    $output = $opts->o;
    if ('-' == $output) {
        $output = STDOUT;
        $usingStdout = true;
    } elseif (!is_writeable(dirname($output))) {
        echo "Cannot write to '$output'; aborting." . PHP_EOL
            . PHP_EOL
            . $opts->getUsageMessage();
        exit(2);
    } elseif (file_exists($output)) {
        if (!$opts->getOption('w')) {
            echo "Autoload file already exists at '$output'," . PHP_EOL
                . "but 'overwrite' flag was not specified; aborting." . PHP_EOL 
                . PHP_EOL
                . $opts->getUsageMessage();
            exit(2);
        }
    }
}

$strip     = SYSTEM;

if (!$usingStdout) {
    echo "Creating class file map for library in '$path'..." . PHP_EOL;
}

// Get the ClassFileLocator, and pass it the library path
$l = new ZendW_File_ClassFileLocator($path);

// Iterate over each element in the path, and create a map of 
// classname => filename, where the filename is relative to the library path
$map    = new stdClass;
$strip .= DIRECTORY_SEPARATOR;
function createMap(Iterator $i, $map, $strip) {
    $file      = $i->current();
    $namespace = empty($file->namespace) ? '' : $file->namespace . '\\';
    $filename  = str_replace($strip, '', $file->getRealpath());
    // Windows portability
    $filename  = str_replace(array('/', '\\'), "' . DIRECTORY_SEPARATOR . '", $filename);

    $map->{$namespace . $file->classname} = $filename;

    return true;
}
iterator_apply($l, 'createMap', array($l, $map, $strip));

// Create a file with the class/file map.
// Stupid syntax highlighters make separating < from PHP declaration necessary
$dirStore = 'dirname_' . uniqid();
$content = '<' . "?php\n"
         . '$' . $dirStore . " = dirname(dirname(__FILE__));\n"
         . 'return ' . var_export((array) $map, true) . ';';

// Prefix with dirname(__FILE__); modify the generated content
$content = preg_replace('#(=> )#', '$1$' . $dirStore . ' . DIRECTORY_SEPARATOR . ', $content);
$content = str_replace("\\'", "'", $content);

// Write the contents to disk
file_put_contents($output, $content);

if (!$usingStdout) {
    echo "Wrote classmap file to '" . realpath($output) . "'" . PHP_EOL;
}
