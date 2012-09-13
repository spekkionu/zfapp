<?php

/**
 * Admin dashboard
 *
 * @package App
 * @subpackage AdminController
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Admin_AdministratorController extends App_AdminController
{

  public function init() {
    parent::init();
    if (!$this->isAllowed('admin', 'general')) {
      // Save current url for later
      $session = new Zend_Session_Namespace();
      $session->login_destination = $this->view->url();
      return $this->routeRedirect('admin_login');
    }
  }

  public function indexAction() {
    if (!$this->isAllowed('admin:administrator', 'view')) {
      return $this->_forward('access-denied', 'error', 'default');
    }
    $page = $this->getRequest()->getParam('page', 1);
    $mgr = new Model_Admin();
    $session = new Zend_Session_Namespace('admin_search');
    if (!isset($session->administrator)) {
      $session->administrator = Model_Admin::$search;
    }
    $sort = $this->getRequest()->getParam('sort');
    if (!in_array($sort, Model_Admin::$sortable)) {
      $sort = 'id';
    }
    $dir = $this->getRequest()->getParam('dir');
    $limit = 25;
    $form = new Form_Search_Administrator();
    $form->populate($session->administrator);
    if ($this->getRequest()->isPost()) {
      if ($this->getRequest()->getPost('clear')) {
        // Set back to default search parameters
        $session->administrator = Model_Admin::$search;
        // Send back to page 1
        return $this->routeRedirect('admin_administrator', array('page' => 1, 'sort' => $sort, 'dir' => $dir));
      }
      if ($form->isValid($this->getRequest()->getPost())) {
        // Save search parameters
        $session->administrator = array_merge($session->administrator, array_intersect_key($form->getValues(), Model_Admin::$search));
        // Send back to page 1
        return $this->routeRedirect('admin_administrator', array('page' => 1, 'sort' => $sort, 'dir' => $dir));
      }
    }
    $form->populate($session->administrator);
    $results = $mgr->getAdministratorList($session->administrator, $page, $limit, $sort, $dir);
    $this->view->results = $results;
    $this->view->sort = $sort;
    $this->view->dir = $dir;
    $this->view->form = $form;
    $this->view->search_expanded = isset($_COOKIE['bootstrap_accordion:admin-administrator-search']) ? $_COOKIE['bootstrap_accordion:admin-administrator-search'] : 'collapsed';
    // Set parameters for navigation
    $navpage = Zend_Registry::get('Zend_Navigation')->findOneByRoute('admin_administrator');
    $navpage->setParams(array(
      'page' => $page,
      'sort' => $sort,
      'dir' => $dir
    ));
  }

  public function addAction() {
    if (!$this->isAllowed('admin:administrator', 'add')) {
      return $this->_forward('access-denied', 'error', 'default');
    }
    $form = new Form_AdminProfile();
    $form->getElement('cancel')->setAttrib('href', $this->view->route('admin_administrator'));
    $form->addDbValidators();
    if ($this->getRequest()->isPost()) {
      if ($this->getRequest()->getPost('cancel')) {
        return $this->routeRedirect('admin_administrator');
      }
      if ($form->isValid($this->getRequest()->getPost())) {
        try {
          $values = $form->getValues();
          $mgr = new Model_Admin();
          $id = $mgr->addAdministrator($values);
          $this->addMessage("Sucessfully added administrator.", 'success');
          return $this->redirectSimple('index');
        } catch (Exception $e) {
          $this->logError("Failed to add administrator - {$e->getMessage()}");
          $this->addMessage("Failed to add administrator", 'error');
          return $this->redirectSimple();
        }
      }
    }
    $this->view->form = $form;
  }

  public function editAction() {
    if (!$this->isAllowed('admin:administrator', 'edit')) {
      return $this->_forward('access-denied', 'error', 'default');
    }
    $id = $this->getRequest()->getParam('id');
    $mgr = new Model_Admin();
    $user = $mgr->getProfile($id);
    if (!$user) {
      return $this->_forward('not-found', 'error', 'default');
    }
    if ($this->identity->id == $id) {
      return $this->routeRedirect('admin_account_profile');
    }
    $form = new Form_AdminProfile();
    $form->removeElement('password');
    $form->removeElement('confirm_password');
    $form->getElement('cancel')->setAttrib('href', $this->view->route('admin_administrator'));
    $form->addDbValidators($id);
    $form->populate($user);
    if ($this->getRequest()->isPost()) {
      if ($this->getRequest()->getPost('cancel')) {
        return $this->routeRedirect('admin_administrator');
      }
      if ($form->isValid($this->getRequest()->getPost())) {
        try {
          $values = $form->getValues();
          $mgr = new Model_Admin();
          $id = $mgr->updateAdministrator($id, $values);
          $this->addMessage("Sucessfully updated administrator.", 'success');
          return $this->redirectSimple('index');
        } catch (Exception $e) {
          $this->logError("Failed to update administrator - {$e->getMessage()}");
          $this->addMessage("Failed to update administrator", 'error');
          return $this->redirectSimple();
        }
      }
    }
    $this->view->form = $form;
  }

  public function deleteAction() {
    if (!$this->isAllowed('admin:administrator', 'delete')) {
      return $this->_forward('access-denied', 'error', 'default');
    }
    $id = $this->getRequest()->getParam('id');
    $mgr = new Model_Admin();
    $user = $mgr->getProfile($id);
    if (!$user) {
      return $this->_forward('not-found', 'error', 'default');
    }
    $allow_delete = ($id != $this->identity->id);
    $form = new Form_Delete();
    $form->getElement('cancel')->setAttrib('href', $this->view->route('admin_administrator'));
    if (!$allow_delete) {
      $form->removeElement('delete');
      $form->getElement('cancel')->setDecorators($form->button);
    }
    if ($this->getRequest()->isPost()) {
      if ($this->getRequest()->getPost('cancel') || !$allow_delete) {
        return $this->routeRedirect('admin_administrator');
      }
      if ($form->isValid($this->getRequest()->getPost())) {
        try {
          $mgr = new Model_Admin();
          $id = $mgr->deleteAdministrator($id);
          $this->addMessage("Sucessfully deleted administrator.", 'success');
          return $this->redirectSimple('index');
        } catch (Exception $e) {
          $this->logError("Failed to delete administrator - {$e->getMessage()}");
          $this->addMessage("Failed to delete administrator", 'error');
          return $this->redirectSimple();
        }
      }
    }
    $this->view->user = $user;
    $this->view->form = $form;
    $this->view->allow_delete = $allow_delete;
  }

}