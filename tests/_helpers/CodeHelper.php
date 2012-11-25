<?php
namespace Codeception\Module;

// here you can define custom functions for CodeGuy 

class CodeHelper extends \Codeception\Module
{

  public function _initialize() {
        if (!defined('SYSTEM')) {
            define('SYSTEM', realpath(dirname(__FILE__) . '/../../src/system'));
        }

        if (!defined('LIBRARY_PATH')) {
            define('LIBRARY_PATH', realpath(SYSTEM . '/library'));
        }

        // Define path to application directory
        if (!defined('APPLICATION_PATH')) {
            define('APPLICATION_PATH', realpath(SYSTEM . '/application'));
        }

        // Define application environment
        if (!defined('APPLICATION_ENV')) {
            define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));
        }

        if(!defined('HTMLPURIFIER_PREFIX')){
            define('HTMLPURIFIER_PREFIX', SYSTEM.'/library/vendor/spekkionu/htmlpurifier');
        }

        require_once(SYSTEM . '/library/vendor/autoload.php');
    }
}
