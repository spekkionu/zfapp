<?php

/**
 * Controls logging in and resetting passwords
 *
 * @package App
 * @subpackage AdminController
 * @author spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Admin_AccessController extends App_AdminController
{

  public function init() {
    parent::init();
  }

  public function indexAction() {
    $form = new Form_Login();
    if ($this->getRequest()->isPost()) {
      if($this->getRequest()->getPost('forgot_password')){
        return $this->routeRedirect('admin_forgot_password');
      }
      if ($form->isValid($this->getRequest()->getPost())) {
        try {
          try {
            $values = $form->getValues();
            $mgr = new Model_User();
            $userinfo = $mgr->login($values['username'], $values['password']);
            $this->addMessage("You have logged in successfully.", 'success');
            $session = new Zend_Session_Namespace();
            if (isset($session->login_destination) && $session->login_destination) {
              // Redirect to destination
              return $this->_redirect($session->login_destination);
            } else {
              // Send to index
              return $this->routeRedirect('admin_home');
            }
          } catch (Validate_Exception $e) {
            $form->getElement('username')->addError($e->getMesage());
          }
        } catch (Exception $e) {
          $this->logError("Error logging in admin. - {$e->getMessage()}");
          $this->addMessage("Error logging in.");
          return $this->routeRedirect('admin_login');
        }
      }
    }
    $this->view->form = $form;
  }

  public function logoutAction() {
    Zend_Auth::getInstance()->clearIdentity();
    return $this->routeRedirect('admin_login');
  }

  public function resetPasswordAction() {
    $form = new Form_ForgotPassword();
    if ($this->getRequest()->isPost()) {
      if($this->getRequest()->getPost('cancel')){
        return $this->routeRedirect('admin_login');
      }
      if ($form->isValid($this->getRequest()->getPost())) {
        try {
          $values = $form->getValues();
          // Generate Token
          $mgr = new Model_User();
          $user = $mgr->resetPassword($values['email'], Model_User::ACCESS_ADMIN);
          try {
            // Send Email
            $mgr = new Model_EmailTemplates();
            $mail = $mgr->passwordReset($user);
            $mail->send();
            $this->addMessage("An email has been sent to {$user['email']} with instructions to complete the password reset. Your password has not yet been modified.");
            return $this->routeRedirect('admin_save_password', array('token' => $user['token']));
          } catch (Exception $e) {
            $this->logError("Failed to send password reset email. {$e->getMessage()}");
            $this->addMessage("Failed to send email", 'error');
            return $this->routeRedirect('admin_forgot_password');
          }
        } catch (Exception $e) {
          $this->logError("Error requesting password reset in admin. - {$e->getMessage()}");
          $this->addMessage("Failed to send password reset request.", 'error');
          return $this->routeRedirect('admin_forgot_password');
        }
      }
    }
    $this->view->form = $form;
  }

  public function savePasswordAction() {
    $token = $this->getRequest()->getParam('token');
    $form = new Form_ChangePassword();
    $form->removeElement('old_password');
    if ($this->getRequest()->isPost()) {
      if ($form->isValid($this->getRequest()->getPost())) {
        try {
          try {
            $mgr = new Model_User();
            $mgr->confirmPasswordReset($token, $form);
            $this->addMessage("Successfully changed account password.", 'success');
            return $this->routeRedirect('admin_home');
          } catch (Validate_Exception $e) {
            // Failed validation, do nothing, redisplay form
          }
        } catch (Exception $e) {
          $this->logError("Failed to confirm password reset. - {$e->getMessage()}");
          $this->addMessage("Failed to change password.", 'error');
          return $this->routeRedirect('admin_save_password', array('token' => $token));
        }
      }
    }
    $this->view->form = $form;
  }

}