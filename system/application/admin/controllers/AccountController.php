<?php

/**
 * Controls account profile and metadata
 *
 * @package App
 * @subpackage AdminController
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Admin_AccountController extends App_AdminController
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
        $mgr = new Model_User();
        $profile = $mgr->getProfile($this->identity->id);
        if (!$profile) {
            return $this->routeRedirect('not_found');
        }
        $this->view->profile = $profile;
    }

    public function editAction()
    {
        $form = new Form_AdminProfile();
        $form->removeElement('password');
        $form->removeElement('confirm_password');
        $form->removeElement('active');
        $form->getElement('cancel')->setAttrib('href', $this->view->route('admin_account'));
        $mgr = new Model_User();
        $profile = $mgr->getProfile($this->identity->id);
        $form->populate($profile);
        $form->addDbValidators($this->identity->id);
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('cancel')) {
                return $this->redirectSimple('index');
            }
            if ($form->isValid($this->getRequest()->getPost())) {
                try {
                    $values = $form->getValues();
                    $mgr->updateProfile($this->identity->id, $values);
                    $this->addMessage("Successfully updated profile.", 'success');
                    return $this->redirectSimple('index');
                } catch (Exception $e) {
                    $this->addMessage("Failed to update profile.", 'error');
                    return $this->redirectSimple('edit');
                }
            }
        }
        $this->view->form = $form;
    }

    public function passwordAction()
    {
        $form = new Form_ChangePassword();
        $form->removeElement('pin');
        $form->getElement('cancel')->setAttrib('href', $this->view->route('admin_account'));
        if ($this->getRequest()->isPost()) {
            if ($this->getRequest()->getPost('cancel')) {
                return $this->redirectSimple('index');
            }
            if ($form->isValid($this->getRequest()->getPost())) {
                try {
                    try {
                        $mgr = new Model_Admin();
                        $mgr->changePassword($this->identity->id, $form);
                        $this->addMessage("Successfully changed account password.", 'success');
                        return $this->redirectSimple('index');
                    } catch (Validate_Exception $e) {
                        // Failed validation, do nothing, redisplay form
                    }
                } catch (Exception $e) {
                    $this->addMessage("Failed to change password.", 'error');
                    return $this->redirectSimple('password');
                }
            }
        }
        $this->view->form = $form;
    }
}
