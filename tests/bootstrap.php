<?php
// Set Unicode settings
ini_set('default_charset', 'UTF-8');
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
mb_internal_encoding( 'UTF-8' );

define('SYSTEM', dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'system');
define('TEST_DIR', dirname(__FILE__));
define('TEST_DATA', TEST_DIR.DIRECTORY_SEPARATOR.'data');

// Set Include Path
set_include_path(
  // Application Library Files
  SYSTEM . DIRECTORY_SEPARATOR . 'library' . PATH_SEPARATOR .
  realpath(SYSTEM . DIRECTORY_SEPARATOR . 'library/vendor') . PATH_SEPARATOR . 
  get_include_path()
);

// Set up autoload.
require_once(SYSTEM . '/library/vendor/ZendW/Loader/AutoloaderFactory.php');
ZendW_Loader_AutoloaderFactory::factory(array(
  'ZendW_Loader_ClassMapAutoloader' => array(
    SYSTEM . '/library/.classmap.php',
  ),
  'ZendW_Loader_StandardAutoloader' => array(
    'prefixes' => array(
      'Zend' => SYSTEM . '/library/vendor/Zend',
      'ZendX' => SYSTEM . '/library/vendor/ZendX',
      'ZendW' => SYSTEM . '/library/vendor/ZendW',
      'HTMLPurifier' => SYSTEM . '/library/vendor/HTMLPurifier/HTMLPurifier',
      'WideImage' => SYSTEM . '/library/vendor/WideImage',
      'ZFDebug' => SYSTEM . '/library/vendor/ZFDebug',
      'Cache' => SYSTEM . '/library/Cache',
      'Form' => SYSTEM . '/library/Form',
      'Options' => SYSTEM . '/library/Options',
      'Session' => SYSTEM . '/library/Session',
      'Validate' => SYSTEM . '/library/Validate',
      'App' => SYSTEM . '/library/App'
    ),
    'namespaces' => array(
      'Assetic' => SYSTEM . '/library/vendor/Assetic/src/Assetic',
      'Symfony' => SYSTEM . '/library/vendor/Symfony'
    ),
    'fallback_autoloader' => true,
  ),
));

// Add Form Autoloader Resource
$resourceLoader = new Zend_Loader_Autoloader_Resource(array(
    'basePath' => SYSTEM,
    'namespace' => '',
  ));
$resourceLoader->addResourceType('form', 'forms/', 'Form');
$resourceLoader->addResourceType('model', 'models/', 'Model');