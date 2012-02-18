<?php

/**
 * Main index controller for front end
 *
 * @package App
 * @subpackage Controller
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class IndexController extends App_FrontController
{

  public function init() {
    parent::init();
  }

  public function indexAction() {

  }

  public function pageAction() {
    $id = $this->getRequest()->getParam('id');
    if(!$id){
      return $this->_forward('not-found', 'error', 'default');
    }
    $mgr = new Model_Content();
    $page = $mgr->getPageContent($id);
    if(!$page){
      return $this->_forward('not-found', 'error', 'default');
    }
    $this->view->page = $page;
  }

  public function contactAction() {

  }

}