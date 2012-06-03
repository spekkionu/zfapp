<?php
// Set Unicode settings
ini_set('default_charset', 'UTF-8');
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
mb_internal_encoding( 'UTF-8' );

define('SYSTEM', realpath(dirname(dirname(__FILE__)).'/src/system'));
define('TEST_DIR', dirname(__FILE__));
define('TEST_DATA', TEST_DIR.DIRECTORY_SEPARATOR.'data');
define('PROJECT_PATH', dirname(dirname(SYSTEM)));
define('SANDBOX_PATH', PROJECT_PATH.'/scripts/doctrine');
define('DOCTRINE_PATH', SYSTEM . '/library/vendor/doctrine-orm/lib');
define('DATA_FIXTURES_PATH', PROJECT_PATH . '/data/fixtures');
define('MODELS_PATH', PROJECT_PATH . '/data/models');
define('MIGRATIONS_PATH', PROJECT_PATH . '/data/migrations');
define('SQL_PATH', PROJECT_PATH . '/data/sql');
define('YAML_SCHEMA_PATH', PROJECT_PATH . '/data/schema');
define('HTMLPURIFIER_PREFIX', SYSTEM.'/library/vendor/HTMLPurifier');

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
      'App' => SYSTEM . '/library/App',
      'Doctrine' => SYSTEM . '/library/vendor/doctrine-orm/lib/Doctrine',
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

$cache_config = array(
  // Cache id prefix, can usually be left alone, needed if multiple applications share the same cache
  'prefix' => 'zfapptest',
  // Caching for general data
  'general' => array(
    // If true caching will be used
    'enabled' => false,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => null,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for database schema and metadata
  'dbmetadata' => array(
    // If true caching will be used
    'enabled' => false,
    // The caching method
    'type' => 'File',
    // Set to true if using something other than a default Zend_Cache Backend
    'custom' => false,
    // The maximum lifetime of a cached entry, set to null for no expiration
    "lifetime" => null,
    // Any options for the cache backend go here.  Most will not need any
    'options' => array()
  ),
  // Caching for HTMLPurifier
  'htmlpurifier' => array(
    // If true caching will be used
    'enabled' => false
  ),
);
if(is_dir(TEST_DATA . '/cache/cache')){
  // Recursively delete cache directory
  $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(TEST_DATA . '/cache/cache'),RecursiveIteratorIterator::CHILD_FIRST);
  foreach ($iterator as $path) {
    if ($path->isDir()) {
        @rmdir($path->__toString());
    } else {
        @unlink($path->__toString());
    }
  }
}
@unlink(TEST_DATA.'/cache/testdb.sqlite');
@mkdir(TEST_DATA . '/cache/cache', 0777, true);
Cache::setCacheDir(TEST_DATA . '/cache/cache');
Cache::setConfig($cache_config);

Zend_Session::start();

spl_autoload_register(array('Doctrine_Core', 'autoload'));
spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));
spl_autoload_register(array('Doctrine_Core', 'extensionsAutoload'));

Doctrine_Core::setExtensionsPath(SYSTEM.'/library/vendor/DoctrineExtensions');
Doctrine_Core::setModelsDirectory(MODELS_PATH);

$manager = Doctrine_Manager::getInstance();
$manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
$manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL);
$manager->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true );
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);
$manager->setAttribute(Doctrine_Core::ATTR_TABLE_CLASS_FORMAT, 'Table_%s');
$manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES,true);
$manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
$manager->setCharset( 'utf8' );
$manager->setCollate( 'utf8_unicode_ci' );