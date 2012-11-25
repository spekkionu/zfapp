<?php
// Set Unicode settings
ini_set('default_charset', 'UTF-8');
iconv_set_encoding("internal_encoding", "UTF-8");
iconv_set_encoding("output_encoding", "UTF-8");
mb_internal_encoding( 'UTF-8' );

define('SYSTEM', realpath(dirname(dirname(__FILE__)).'/src/system'));
define('TEST_DIR', dirname(__FILE__));
define('TEST_DATA', TEST_DIR.DIRECTORY_SEPARATOR.'_data');
define('PROJECT_PATH', dirname(dirname(SYSTEM)));
define('SANDBOX_PATH', PROJECT_PATH.'/scripts/doctrine');
define('DOCTRINE_PATH', SYSTEM . '/library/vendor/doctrine/doctrine1/lib');
define('DATA_FIXTURES_PATH', PROJECT_PATH . '/data/fixtures');
define('MODELS_PATH', PROJECT_PATH . '/data/models');
define('MIGRATIONS_PATH', PROJECT_PATH . '/data/migrations');
define('SQL_PATH', PROJECT_PATH . '/data/sql');
define('YAML_SCHEMA_PATH', PROJECT_PATH . '/data/schema');
define('HTMLPURIFIER_PREFIX', SYSTEM.'/library/vendor/spekkionu/htmlpurifier');

// Set up autoload.
require_once(SYSTEM . '/library/vendor/autoload.php');

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

require_once(DOCTRINE_PATH . DIRECTORY_SEPARATOR . 'Doctrine.php');
spl_autoload_register(array('Doctrine_Core', 'autoload'));
spl_autoload_register(array('Doctrine_Core', 'modelsAutoload'));
spl_autoload_register(array('Doctrine_Core', 'extensionsAutoload'));

Doctrine_Core::setExtensionsPath(SYSTEM.'/library/vendor/DoctrineExtensions');
Doctrine_Core::setModelsDirectory(MODELS_PATH);

$manager = Doctrine_Manager::getInstance();
$manager->setAttribute(Doctrine_Core::ATTR_QUOTE_IDENTIFIER, true);
$manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_NONE);
$manager->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true );
$manager->setAttribute(Doctrine_Core::ATTR_MODEL_LOADING, Doctrine_Core::MODEL_LOADING_PEAR);
$manager->setAttribute(Doctrine_Core::ATTR_TABLE_CLASS_FORMAT, 'Table_%s');
$manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES,true);
$manager->setAttribute(Doctrine_Core::ATTR_AUTO_ACCESSOR_OVERRIDE, true);
$manager->setCharset( 'utf8' );
$manager->setCollate( 'utf8_unicode_ci' );
