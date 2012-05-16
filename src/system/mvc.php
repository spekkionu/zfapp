<?php

require(dirname(__FILE__) . '/application.php');

if ($config['cache']['plugin']['enabled']) {
  if (file_exists(SYSTEM . '/cache/plugin/pluginLoaderCache.php')) {
    // Include the plugin loader cache
    include_once SYSTEM . '/cache/plugin/pluginLoaderCache.php';
  }elseif(!is_dir(SYSTEM . '/cache/plugin/')){
    // Create the plugin cache directory
    mkdir(SYSTEM . '/cache/plugin/', 0777, true);
  }
  Zend_Loader_PluginLoader::setIncludeFileCache(SYSTEM . '/cache/plugin/pluginLoaderCache.php');
}

// Set Controller Paths
$controller = Zend_Controller_Front::getInstance();
$controller->setControllerDirectory(array(
  'default' => SYSTEM . '/application/default/controllers',
  'admin' => SYSTEM . '/application/admin/controllers'
));

// Setup Router with custom routes
$router = $controller->getRouter();
// Load CMS Routes
$mgrContent = new Model_Content();
$router->addRoutes($mgrContent->getRoutes());
unset($mgrContent);
// Add application routes
$router->addRoutes(require(SYSTEM . '/configs/routes.php'));

// Set Error Reporting
$controller->throwExceptions($config['debug']['display_errors']);
// Set Base Url
if ($config['ssl']['enable'] && $_SERVER['REMOTE_PORT'] == $config['ssl']['port']) {
  // This is a ssl request
  $controller->setBaseUrl($config['ssl']['base_url']);
} else {
  // This is not an ssl request
  $controller->setBaseUrl($config['site']['base_url']);
}
$controller->setParam('disableOutputBuffering', true);

// register the default action helpers
Zend_Controller_Action_HelperBroker::addPath(SYSTEM . '/application/default/helpers', 'Zend_Controller_Action_Helper');

// Init Layout
$layout = Zend_Layout::startMvc();
$layout->setLayoutPath(SYSTEM . '/application/default/views/layout');
$view = $layout->getView();
$view->addHelperPath(SYSTEM . '/application/default/views/helpers', 'Zend_View_Helper');
ZendX_JQuery::enableView($view);
$view->jQuery()->enable();

// Setup Navidation Defaults
Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($acl);
if (Zend_Auth::getInstance()->hasIdentity()) {
  Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole(Zend_Auth::getInstance()->getIdentity()->accesslevel);
} else {
  Zend_View_Helper_Navigation_HelperAbstract::setDefaultRole('guest');
}

if ($config['debug']['debug_bar']) {
  $debug = new ZFDebug_Controller_Plugin_Debug(array(
      'jquery_path' => $view->baseUrl('assets/scripts/jquery/jquery-1.7.1.min.js'),
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


// distpatch controller
$controller->dispatch();