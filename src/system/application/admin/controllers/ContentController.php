<?php

/**
 * Admin dashboard
 *
 * @package App
 * @subpackage AdminController
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Admin_ContentController extends App_AdminController
{

    public function init()
    {
        parent::init();
        if (!$this->isAllowed('admin', 'general')) {
            // Save current url for later
            $session = new Zend_Session_Namespace();
            $session->login_destination = $this->view->url();
            return $this->routeRedirect('admin_login');
        }
    }

    public function indexAction()
    {
        if (!$this->isAllowed('admin:content', 'view')) {
            return $this->_forward('access-denied', 'error', 'default');
        }
        $page = $this->getRequest()->getParam('page', 1);
        $mgr = new Model_Content();
        $session = new Zend_Session_Namespace('admin_search');
        if (!isset($session->content)) {
            $session->content = Model_Content::$search;
        }
        $sort = $this->getRequest()->getParam('sort');
        if (!in_array($sort, Model_Content::$sortable)) {
            $sort = 'id';
        }
        $dir = $this->getRequest()->getParam('dir');
        $limit = 25;
        $form = new Form_Search_Content();
        $form->populate($session->content);
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('clear')) {
                // Set back to default search parameters
                $session->content = Model_Content::$search;
                // Send back to page 1
                return $this->routeRedirect('admin_content', array('page' => 1, 'sort' => $sort, 'dir' => $dir));
            }
            if ($form->isValid($this->getRequest()->getPost())) {
                // Save search parameters
                $session->content = array_merge($session->content, array_intersect_key($form->getValues(), Model_Content::$search));
                // Send back to page 1
                return $this->routeRedirect('admin_content', array('page' => 1, 'sort' => $sort, 'dir' => $dir));
            }
        }
        $form->populate($session->content);
        $results = $mgr->getPageList($session->content, $page, $limit, $sort, $dir);
        $this->view->results = $results;
        $this->view->sort = $sort;
        $this->view->dir = $dir;
        $this->view->form = $form;
        $this->view->search_expanded = isset($_COOKIE['bootstrap_accordion:admin-content-search']) ? $_COOKIE['bootstrap_accordion:admin-content-search'] : 'collapsed';
        // Set parameters for navigation
        $navpage = Zend_Registry::get('Zend_Navigation')->findOneByRoute('admin_content');
        $navpage->setParams(array(
          'page' => $page,
          'sort' => $sort,
          'dir' => $dir
        ));
    }

    public function addAction()
    {
        if (!$this->isAllowed('admin:content', 'add')) {
            return $this->_forward('access-denied', 'error', 'default');
        }
        $form = new Form_Content();
        $form->getElement('cancel')->setAttrib('href', $this->view->route('admin_content'));
        $form->addDbValidators();
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->getRequest()->getPost())) {
                try {
                    $values = $form->getValues();
                    $mgr = new Model_Content();
                    $id = $mgr->addPage($values);
                    $this->addMessage("Sucessfully added page.", 'success');
                    return $this->redirectSimple('index');
                } catch (Exception $e) {
                    $this->logError("Failed to add page - {$e->getMessage()}");
                    $this->addMessage("Failed to add page", 'error');
                    return $this->redirectSimple();
                }
            }
        }
        $this->view->form = $form;
    }

    public function editAction()
    {
        if (!$this->isAllowed('admin:content', 'edit')) {
            return $this->_forward('access-denied', 'error', 'default');
        }
        $id = $this->getRequest()->getParam('id');
        $mgr = new Model_Content();
        $result = $mgr->getPage($id);
        if (!$result) {
            return $this->_forward('not-found', 'error', 'default');
        }
        $form = new Form_Content();
        if (!$result['can_delete']) {
            $form->removeElement('active');
            $form->getElement('url')->setIgnore(true)->setAttrib('readonly', 'readonly');
        }
        $form->getElement('cancel')->setAttrib('href', $this->view->route('admin_content'));
        $form->addDbValidators($id);
        $form->populate($result);
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('cancel')) {
                return $this->routeRedirect('admin_content');
            }
            if ($form->isValid($this->getRequest()->getPost())) {
                try {
                    $values = $form->getValues();
                    $mgr->updatePage($id, $values);
                    $this->addMessage("Sucessfully updated page.", 'success');
                    return $this->routeRedirect('admin_content');
                } catch (Exception $e) {
                    $this->logError("Failed to update page - {$e->getMessage()}");
                    $this->addMessage("Failed to update page", 'error');
                    return $this->routeRedirect('admin_content_edit', array('id'=>$id));
                }
            }
        }
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        if (!$this->isAllowed('admin:content', 'delete')) {
            return $this->_forward('access-denied', 'error', 'default');
        }
        $id = $this->getRequest()->getParam('id');
        $mgr = new Model_Content();
        $result = $mgr->getPage($id);
        if (!$result) {
            return $this->_forward('not-found', 'error', 'default');
        }
        $form = new Form_Delete();
        $form->getElement('cancel')->setAttrib('href', $this->view->route('admin_content'));
        if (!$result['can_delete']) {
            $form->removeElement('delete');
            $form->getElement('cancel')->setDecorators($form->button);
        }
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('cancel') || !$result['can_delete']) {
                return $this->routeRedirect('admin_content');
            }
            if ($form->isValid($this->getRequest()->getPost())) {
                try {
                    $id = $mgr->deletePage($id);
                    $this->addMessage("Sucessfully deleted page.", 'success');
                    return $this->routeRedirect('admin_content');
                } catch (Exception $e) {
                    $this->logError("Failed to delete page - {$e->getMessage()}");
                    $this->addMessage("Failed to delete page", 'error');
                    return $this->redirectSimple();
                }
            }
        }
        $this->view->page = $result;
        $this->view->form = $form;
    }

}
