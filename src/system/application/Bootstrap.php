<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    public function _initEncoding()
    {
        // Set Unicode settings
        ini_set('default_charset', 'UTF-8');
        iconv_set_encoding("internal_encoding", "UTF-8");
        iconv_set_encoding("output_encoding", "UTF-8");
        mb_internal_encoding('UTF-8');
    }

    public function _initConfig()
    {
        if (Zend_Registry::isRegistered('config')) {
            $config = Zend_Registry::get('config');
        } else {
            $config = require_once(SYSTEM . '/configs/config.php');
        }
        $config['system'] = SYSTEM;
        if (!defined('WEBROOT')) {
            define('WEBROOT', $config['site']['webroot']);
        }
        if (defined('DISABLE_CACHES') && DISABLE_CACHES) {
            foreach ($config['cache'] as $key => $value) {
                if (isset($value['enabled'])) {
                    $config['cache'][$key]['enabled'] = false;
                }
            }
        }
        $config['cache']['cache_dir'] = SYSTEM . DIRECTORY_SEPARATOR . 'cache';
        Zend_Registry::set('config', $config);
        return $config;
    }

    public function _initErrorReporting()
    {
        $this->bootstrap('config');
        $config = $this->getResource('config');
        error_reporting($config['debug']['error_reporting']);
        ini_set('display_errors', $config['debug']['display_errors'] ? 1 : 0);
         if (!is_dir(SYSTEM . DIRECTORY_SEPARATOR . 'logs')) {
            // Log directory does not exist, create it
            @mkdir(SYSTEM . DIRECTORY_SEPARATOR . 'logs', 0777, true);
        }
        if ($config['debug']['error_logging']) {
            // Turn on error logging
            ini_set('error_log', SYSTEM . '/logs/php_errors.log');
            ini_set('log_errors', 1);
        } else {
            ini_set('log_errors', 0);
        }
    }

    public function _initLog()
    {
        $this->bootstrap('config');
        $this->bootstrap('errorReporting');
        $config = $this->getResource('config');
        if (defined('DISABLE_LOGS') && DISABLE_LOGS) {
            // Set Error Logger
            $logger = new Zend_Log(new Zend_Log_Writer_Null());
            ErrorLogger::setInstance($logger);
        } else {
            // Set Error Logger
            $logger = new Zend_Log(new Zend_Log_Writer_Stream(SYSTEM . '/logs/error.log'));
            ErrorLogger::setInstance($logger);
            if ($config['debug']['firebug']) {
                $firebug = new Zend_Log( new Zend_Log_Writer_Firebug());
            } else {
                $firebug = new Zend_Log( new Zend_Log_Writer_Null());
            }
            Zend_Registry::set('firebug', $firebug);
            $logger = new Zend_Log(new Zend_Log_Writer_Stream(SYSTEM . '/logs/application.log'));
        }
        Zend_Registry::set('Zend_Log', $logger);
        return $logger;
    }

    

    public function _initCache()
    {
        $this->bootstrap('config');
        $config = $this->getResource('config');
        if (!is_dir(SYSTEM . DIRECTORY_SEPARATOR . 'cache')) {
            // Cache dir does not exist, create it
            @mkdir(SYSTEM . DIRECTORY_SEPARATOR . 'cache', 0777, true);
        }
        Cache::setConfig($config['cache']);
        Cache::setCacheDir(SYSTEM . DIRECTORY_SEPARATOR . 'cache');
        Cache::setPublicCacheDir(WEBROOT . DIRECTORY_SEPARATOR . 'cached');
        if ($config['cache']['plugin']['enabled']) {
            if (file_exists(SYSTEM . '/cache/plugin/pluginLoaderCache.php')) {
                // Include the plugin loader cache
                include_once SYSTEM . '/cache/plugin/pluginLoaderCache.php';
            } elseif(!is_dir(SYSTEM . '/cache/plugin/')) {
                // Create the plugin cache directory
                @mkdir(SYSTEM . '/cache/plugin/', 0777, true);
            }
            Zend_Loader_PluginLoader::setIncludeFileCache(SYSTEM . '/cache/plugin/pluginLoaderCache.php');
        }

        Cache::initPageCache();
        return Cache::getManager();
    }

    public function _initLocale()
    {
        $this->bootstrap('config');
        $this->bootstrap('cache');
        $config = $this->getResource('config');
        date_default_timezone_set($config['locale']['timezone']);
        Zend_Locale::setDefault($config['locale']['locale']);
        $locale = new Zend_Locale($config['locale']['locale']);
        Zend_Locale::setCache(Cache::getCache('locale'));
        Zend_Registry::set('Zend_Locale', $locale);
        return $locale;
    }

    public function _initCrypt()
    {
        $this->bootstrap('config');
        $config = $this->getResource('config');
        Crypt::setDefaults($config['security']['crypt']);
    }

    public function _initDb()
    {
        $this->bootstrap('config');
        $this->bootstrap('cache');
        $config = $this->getResource('config');
        if (APPLICATION_ENV == 'testing') {
            $db = Zend_Db::factory('Pdo_SQLite', array(
                'dbname'   => SYSTEM.'/userfiles/database/database.sqlite'
            ));
        } else {
            // Connect to database
            $db = Zend_Db::factory($config['database']['adapter'], $config['database']['params']);
        }
        Zend_Db_Table::setDefaultAdapter($db);
        if ($config['cache']['dbmetadata']['enabled']) {
            // Setup DB Cache
            Zend_Db_Table_Abstract::setDefaultMetadataCache(Cache::getCache('dbmetadata'));
        }
        // Save in registry
        Zend_Registry::set('db', $db);
        return $db;
    }



    public function _initMail()
    {
        $this->bootstrap('config');
        $config = $this->getResource('config');
        if (APPLICATION_ENV == 'testing') {
            if (!is_dir(SYSTEM . '/logs/mail')) {
                // Mail dir does not exist, create it
                @mkdir(SYSTEM . '/logs/mail', 0777, true);
            }
            Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_File(array(
                'path' => SYSTEM . '/logs/mail'
            )));
        } else {
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
                if (!is_dir(SYSTEM . '/logs/mail')) {
                    // Mail dir does not exist, create it
                    @mkdir(SYSTEM . '/logs/mail', 0777, true);
                }
                Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_File(array(
                    'path' => SYSTEM . '/logs/mail'
                )));
            } else {
                // Use Sendmail
                if (isset($config['mail']['options']['forcereturn']) && $config['mail']['options']['forcereturn']) {
                    Zend_Mail::setDefaultTransport(new Zend_Mail_Transport_Sendmail('-f' . $config['mail']['options']['forcereturn']));
                }
            }
        }
        // Setup Email Template class
        Model_EmailTemplates::setConfig($config['mail']);
        Model_EmailTemplates::setTemplateDir(SYSTEM . '/configs/email_templates');
    }

    public function _initTranslate()
    {
        $this->bootstrap('cache');
        /*
        $translate = new Zend_Translate(
          array(
            'adapter' => 'array',
            'content' => SYSTEM.'/configs/language/en_US.php',
            'locale'  => 'en_US',
            'disableNotices' => true,
            'cache' => Cache::getCache('translate'),
            'log' => new Zend_Log(new Zend_Log_Writer_Stream(SYSTEM . '/logs/translate.log'))
          )
        );
        Zend_Validate_Abstract::setDefaultTranslator($translate);
        Zend_Form::setDefaultTranslator($translate);
        Zend_Registry::set('Zend_Translate', $translate);
        return $translate;
        */
    }

    public function _initSession()
    {
        $this->bootstrap('config');
        $this->bootstrap('crypt');
        $config = $this->getResource('config');
        if (APPLICATION_ENV == 'testing') {
            $options['hash_function'] = 'md5';
            $options['name'] = md5($config['site']['domain']);
            $options['use_only_cookies'] = false;
            $options['use_cookies'] = false;
            $options['strict'] = false;
            Zend_Session::setOptions($options);
            $handler = new Session_SaveHandler_Mock();
            Zend_Session::setSaveHandler($handler);
        } else {
            // Setup Session Handler
            $config['session']['options']['hash_function'] = 'md5';
            $config['session']['options']['name'] = md5($config['site']['domain']);
            $config['session']['options']['use_only_cookies'] = true;
            if (!is_dir(SYSTEM . "/cache/session")) {
              @mkdir(SYSTEM . "/cache/session", 0777, true);
            }
            $config['session']['options']['save_path'] = SYSTEM.DIRECTORY_SEPARATOR."cache".DIRECTORY_SEPARATOR."session";
            Zend_Session::setOptions($config['session']['options']);
            if ($config['session']['handler']) {
                $handler = $config['session']['handler'];
                $handler = new $handler($config['session']['handler_options']);
                Zend_Session::setSaveHandler($handler);
            }
        }
        
    }

    public function _initAcl()
    {
        $this->bootstrap('config');
        $config = $this->getResource('config');
        $acl = require(SYSTEM . '/configs/acl.php');
        Zend_Registry::set('Zend_Acl', $acl);
        return $acl;
    }

    public function _initAuth()
    {
        $this->bootstrap('config');
        $this->bootstrap('acl');
        $this->bootstrap('crypt');
        $config = $this->getResource('config');
        $auth = Zend_Auth::getInstance();
        if (APPLICATION_ENV != 'testing') {
            $auth->setStorage(new Zend_Auth_Storage_Session($config['site']['domain'] . '_auth'));
        } else {
            $auth->setStorage(new Zend_Auth_Storage_NonPersistent());
        }
        return $auth;
    }

    public function _initNavigation() 
    {
        $this->bootstrap('config');
        $this->bootstrap('acl');
        $config = $this->getResource('config');
        $acl = $this->getResource('acl');
        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
        if (Zend_Auth::getInstance()->hasIdentity()) {
          Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole(Zend_Auth::getInstance()->getIdentity()->accesslevel);
        } else {
          Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole('guest');
        }

    }

    public function _initFrontController()
    {
        $this->bootstrap('config');
        $this->bootstrap('view');
        $config = $this->getResource('config');
        $controller = Zend_Controller_Front::getInstance();
        $controller->setControllerDirectory(array(
          'default' => SYSTEM . '/application/default/controllers',
          'admin' => SYSTEM . '/application/admin/controllers'
        ));
        // Disable Output buffering
        $controller->setParam('disableOutputBuffering', true);
        // Set Error Reporting
        $controller->throwExceptions($config['debug']['display_errors']);
        // Set Base Url
        $controller->setBaseUrl($config['site']['base_url']);
        if(APPLICATION_ENV != 'testing') {
            $controller->returnResponse(true);
            $controller->setParam('disableOutputBuffering', false);
        }
        // register the default action helpers
        Zend_Controller_Action_HelperBroker::addPath(SYSTEM . '/application/default/helpers', 'Zend_Controller_Action_Helper');
        if (APPLICATION_ENV != 'testing' && $config['debug']['debug_bar']) {
            $view = $this->getResource('view');
            $debug = new ZFDebug_Controller_Plugin_Debug(array(
                'jquery_path' => $view->baseUrl('assets/vendor/jquery/jquery.js'),
                'plugins' => array(
                    'Variables',
                    'Html',
                    'Log',
                    'File' => array('base_path' => SYSTEM),
                    'Database',
                    'Memory',
                    'Time',
                    'ZFDebug_Controller_Plugin_Debug_Plugin_Auth' => array('user' => 'username', 'role' => 'accesslevel'),
                    'Exception'
                )
            ));
            $controller->registerPlugin($debug);
        }
        return $controller;
    }

    public function _initRouter()
    {
        $this->bootstrap('config');
        $this->bootstrap('frontController');
        $this->bootstrap('db');
        $config = $this->getResource('config');
        $controller = $this->getResource('FrontController');
        $router = $controller->getRouter();
        // Load CMS Routes
        $mgrContent = new Model_Content();
        $router->addRoutes($mgrContent->getRoutes());
        unset($mgrContent);
        // Add application routes
        $router->addRoutes(require(SYSTEM . '/configs/routes.php'));
        return $router;

    }

}
