<?php

if (!defined('SYSTEM')) {
    define('SYSTEM', __DIR__);
}

if (!defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', SYSTEM.DIRECTORY_SEPARATOR.'application');
}

// Define application environment
if (!defined('APPLICATION_ENV')) {
    define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
}

if(!defined('HTMLPURIFIER_PREFIX')){
    define('HTMLPURIFIER_PREFIX', SYSTEM.'/library/vendor/spekkionu/htmlpurifier');
}

require_once(SYSTEM . '/library/vendor/autoload.php');

$config = require_once(SYSTEM . '/configs/config.php');

$config['debug']['xhprof']['active'] = false;
if(APPLICATION_ENV != 'testing' && extension_loaded('xhprof') && $config['debug']['xhprof']['enabled'] && isset($_GET[$config['debug']['xhprof']['trigger']])) {
    // Set as active
    $config['debug']['xhprof']['active'] = true;
    xhprof_enable(intval($config['debug']['xhprof']['options']));
}

Zend_Registry::set('config', $config);
 
// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    SYSTEM . '/configs/application.ini'
);

$application->bootstrap()->run();

if (APPLICATION_ENV != 'testing') {
    $controller = Zend_Controller_Front::getInstance();
    $request = $controller->getRequest();
    $response = $controller->getResponse();

    if ($config['debug']['xhprof']['active']) {
      // stop profiler
      $xhprof_data = xhprof_disable();

      $XHPROF_ROOT = $config['debug']['xhprof']['root'];
      include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
      include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

      // save raw data for this profiler run using default
      // implementation of iXHProfRuns.
      $xhprof_runs = new XHProfRuns_Default();

      // save the run under a namespace "xhprof_foo"
      $run_id = $xhprof_runs->save_run($xhprof_data, $config['debug']['xhprof']['key']);
      $url = $config['debug']['xhprof']['base_url']."?run={$run_id}&source={$config['debug']['xhprof']['key']}";
    }

    if ($config['debug']['firebug']) {
      $channel = Zend_Wildfire_Channel_HttpHeaders::getInstance();
      $channel->setRequest($request);
      $channel->setResponse($response);
      ob_start();
      if ($config['debug']['xhprof']['active']) {
        if($config['debug']['xhprof']['allowed'] && isset($_SERVER['REMOTE_ADDRESS']) && in_array($_SERVER['REMOTE_ADDRESS'], $config['debug']['xhprof']['allowed'])){
          // If allowed log to firebug
          $firebug->log($run_id, Zend_Log::DEBUG);
          $firebug->log($url, Zend_Log::DEBUG);
        }else{
          // otherwise log to file
          $logger = new Zend_Log(new Zend_Log_Writer_Stream(SYSTEM.'/logs/xhprof.log'));
          $logger->log($run_id, Zend_Log::DEBUG);
          $logger->log($url, Zend_Log::DEBUG);
        }
      }
      $channel->flush();
    }elseif($config['debug']['xhprof']['active']){
      // otherwise log to file
      $logger = new Zend_Log(new Zend_Log_Writer_Stream(SYSTEM.'/logs/xhprof.log'));
      $logger->log($run_id, Zend_Log::DEBUG);
      $logger->log($url, Zend_Log::DEBUG);
    }

    $response->sendResponse();
}
