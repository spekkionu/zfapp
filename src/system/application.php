<?php

// Set Unicode settings
ini_set('default_charset', 'UTF-8');
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
mb_internal_encoding('UTF-8');

define('SYSTEM', dirname(__FILE__));

// Set Include Path
set_include_path(
  // Application Library Files
  SYSTEM . DIRECTORY_SEPARATOR . 'library'
);

// Set up autoload.
require_once(SYSTEM . '/library/ZendW/Loader/AutoloaderFactory.php');
ZendW_Loader_AutoloaderFactory::factory(array(
  'ZendW_Loader_ClassMapAutoloader' => array(
    SYSTEM . '/library/.classmap.php',
  ),
  'ZendW_Loader_StandardAutoloader' => array(
    'prefixes' => array(
      'Zend' => SYSTEM . '/library/Zend',
      'ZendX' => SYSTEM . '/library/ZendX',
      'ZendW' => SYSTEM . 'library/ZendW',
      'HTMLPurifier' => SYSTEM . '/library/HTMLPurifier/HTMLPurifier',
      'WideImage' => SYSTEM . '/library/WideImage',
      'ZFDebug' => SYSTEM . '/library/ZFDebug',
      'Cache' => SYSTEM . '/library/Cache',
      'Form' => SYSTEM . '/library/Form',
      'Options' => SYSTEM . '/library/Options',
      'Session' => SYSTEM . '/library/Session',
      'Validate' => SYSTEM . '/library/Validate',
      'App' => SYSTEM . '/library/App'
    ),
    'namespaces' => array(
      'Assetic' => SYSTEM . '/library/Assetic/src/Assetic'
    ),
    'fallback_autoloader' => true,
  ),
));

// Load Config
$config = require( SYSTEM . '/configs/config.php');
$config['system'] = SYSTEM;

if (!defined('WEBROOT')) {
  define('WEBROOT', $config['site']['webroot']);
}

// Set Timezone
date_default_timezone_set($config['locale']['timezone']);

// Init Cache
Cache::setConfig($config['cache']);
Cache::setCacheDir(SYSTEM . DIRECTORY_SEPARATOR . 'cache');

// Set Locale
Zend_Locale::setDefault($config['locale']['locale']);
$locale = new Zend_Locale($config['locale']['locale']);
Zend_Registry::set('Zend_Locale', $locale);

// Setup Cache Settings
$config['cache']['cache_dir'] = realpath(SYSTEM . '/cache');

// Add Form Autoloader Resource
$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
    'basePath' => SYSTEM,
    'namespace' => '',
  ));
$resourceLoader->addResourceType('form', 'forms/', 'Form');
$resourceLoader->addResourceType('model', 'models/', 'Model');

error_reporting($config['debug']['error_reporting']);
ini_set('display_errors', $config['debug']['display_errors']);

if ($config['debug']['error_logging']) {
  // Turn on error logging
  ini_set('error_log', SYSTEM . '/logs/php_errors.log');
  ini_set('log_errors', 1);
}

// Setup Default Mail Transport
if ($config['mail']['type'] == 'smtp') {
  // Use SMTP
  // Unset any empty settings
  foreach ($config['mail']['smtp']['options'] as $key => $value) {
    if (is_null($value) || $value == '') {
      unset($config['mail']['smtp']['options'][$key]);
    }
  }
  Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Smtp($config['mail']['smtp']['server'], $config['mail']['smtp']['options']));
} elseif ($config['mail']['type'] == 'file') {
  // This transport is used to log sent mail to a file rather than sending it.
  Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_File(array(
      'path' => SYSTEM . '/logs/mail'
    )));
} else {
  // Use Sendmail
  if (isset($config['mail']['options']['forcereturn']) && $config['mail']['options']['forcereturn']) {
    Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Sendmail('-f' . $config['mail']['options']['forcereturn']));
  }
}

// Setup Email Template class
Model_EmailTemplates::setConfig($config['mail']);
Model_EmailTemplates::setTemplateDir(SYSTEM . '/configs/email_templates');

// Set Error Logger
ErrorLogger::setInstance(new Zend_Log(new Zend_Log_Writer_Stream(SYSTEM . '/logs/error.log')));

// Connect to database
$db = Zend_Db::factory($config['database']['adapter'], $config['database']['params']);
Zend_Db_Table::setDefaultAdapter($db);
if ($config['cache']['dbmetadata']['enabled']) {
  // Setup DB Cache
  Zend_Db_Table_Abstract::setDefaultMetadataCache(Cache::getCache('dbmetadata'));
}
// Save in registry
Zend_Registry::set('db', $db);

// Setup Zend_Currency
//$cache = Cache::getCache('currency');
//Zend_Currency::setCache($cache);
//$currency = new Zend_Currency();
//Zend_Registry::set('Zend_Currency', $currency);
// Setup Session Handler
if ($config['session']['handler']) {
  $config['session']['options']['hash_function'] = 'md5';
  $config['session']['options']['name'] = md5($config['site']['domain']);
  $config['session']['options']['use_only_cookies'] = true;
  $config['session']['options']['save_path'] = realpath(SYSTEM . "/cache/session");
  Zend_Session::setOptions($config['session']['options']);
  $handler = $config['session']['handler'];
  $handler = new $handler($config['session']['handler_options']);
  Zend_Session::setSaveHandler($handler);
}

// Initialize Auth Class
$auth = Zend_Auth::getInstance();
$auth->setStorage(new Zend_Auth_Storage_Session($config['site']['domain'] . '_auth'));

// Initialize Acl
$acl = require_once(SYSTEM . '/configs/acl.php');
Zend_Registry::set('Zend_Acl', $acl);

// Save config
Zend_Registry::set('config', $config);

/**
 * Recursive wrapper for stripslashes()
 *
 * @param mixed $value
 * @return mixed
 */
function stripslashes_deep(&$value) {
  $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
  return $value;
}

if (function_exists('get_magic_quotes_gpc')) {
  if (get_magic_quotes_gpc() or ( ini_get('magic_quotes_sybase') and ( strtolower(ini_get('magic_quotes_sybase')) != 'off' ) )) {
    stripslashes_deep($_GET);
    stripslashes_deep($_POST);
  }
} else {

  function get_magic_quotes_gpc() {
    return 0;
  }

}
