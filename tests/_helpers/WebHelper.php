<?php
namespace Codeception\Module;

// here you can define custom functions for WebGuy 

class WebHelper extends \Codeception\Module
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

        require_once(SYSTEM . '/library/vendor/autoload.php');
    }

  public function logIntoAdmin($username, $password)
  {
    $module = $this->getModule('PhpBrowser');
    $session = $module->session;
    $session->visit($module->config['url'].'/admin/login');
    $page = $session->getPage();
    $el = $page->find('css', '#username');
    $el->setValue($username);
    $el = $page->find('css', '#password');
    $el->setValue($password);
    $el = $page->find('css', '#login');
    $el->press();
  }
}
