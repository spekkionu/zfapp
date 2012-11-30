<?php
// Here you can initialize variables that will for your tests
if(!defined('SYSTEM')){
    define('SYSTEM', realpath(dirname(dirname(__DIR__)).'/src/system'));
}
if(!defined('APPLICATION_PATH')){
    define('APPLICATION_PATH', SYSTEM.DIRECTORY_SEPARATOR.'application');
}
if(!defined('APPLICATION_ENV')){
    define('APPLICATION_ENV', 'testing');
}
if(!defined('TEST_DIR')){
    define('TEST_DIR', dirname(__DIR__));
}
if(!defined('TEST_DATA')){
    define('TEST_DATA', TEST_DIR.DIRECTORY_SEPARATOR.'_data');
}
if(!defined('PROJECT_PATH')){
    define('PROJECT_PATH', dirname(dirname(SYSTEM)));
}
if(!defined('SANDBOX_PATH')){
    define('SANDBOX_PATH', PROJECT_PATH.'/scripts/doctrine');
}
if(!defined('DOCTRINE_PATH')){
    define('DOCTRINE_PATH', SYSTEM . '/library/vendor/doctrine/doctrine1/lib');
}
if(!defined('DATA_FIXTURES_PATH')){
    define('DATA_FIXTURES_PATH', PROJECT_PATH . '/data/fixtures');
}
if(!defined('MODELS_PATH')){
    define('MODELS_PATH', PROJECT_PATH . '/data/models');
}
if(!defined('MIGRATIONS_PATH')){
    define('MIGRATIONS_PATH', PROJECT_PATH . '/data/migrations');
}
if(!defined('SQL_PATH')){
    define('SQL_PATH', PROJECT_PATH . '/data/sql');
}
if(!defined('YAML_SCHEMA_PATH')){
    define('YAML_SCHEMA_PATH', PROJECT_PATH . '/data/schema');
}
if(!defined('HTMLPURIFIER_PREFIX')){
    define('HTMLPURIFIER_PREFIX', SYSTEM.'/library/vendor/spekkionu/htmlpurifier');
}

// Set up autoload.
require_once(SYSTEM . '/library/vendor/autoload.php');

require_once(SYSTEM . '/application/Bootstrap.php');

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

// Enable Session Unit Testing
Zend_Session::$_unitTestEnabled = true;

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    SYSTEM . '/configs/application.ini'
);

$application->bootstrap(array('encoding', 'config', 'cache', 'locale', 'crypt', 'db', 'mail', 'session'));