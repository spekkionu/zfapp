<?php

/**
 * Admin Controller base class
 *
 * @package    App
 * @subpackage Controller
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
abstract class App_AdminController extends App_Controller
{

    /**
     * Controller init
     */
    public function init()
    {
        parent::init();
        if ($this->config['ssl']['enable'] && $_SERVER["SERVER_PORT"] != $this->config['ssl']['port']) {
            $protocol = ($this->config['ssl']['port'] == 443) ? 'https://' : 'http://';
            header("location: " . $protocol . $this->config['ssl']['domain'] . $_SERVER["REQUEST_URI"]);
            exit;
        }
        // Set Admin Logger
        $this->logger = new Zend_Log(new Zend_Log_Writer_Stream(SYSTEM . '/logs/admin.log'));
        // Set Admin Layout
        $this->_helper->layout->setLayoutPath(SYSTEM . '/application/admin/views/layout');
        $this->view->pageHeader = null;
        // Setup Admin Navigation
        $pages = require(SYSTEM . '/configs/navigation.php');
        $container = new Zend_Navigation($pages);
        // Save Navigation
        Zend_Registry::set('Zend_Navigation', $container);
    }
}
