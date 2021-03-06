<?php

/**
 * Test class for Form_ForgotPassword.
 * Generated by PHPUnit on 2012-06-02 at 16:41:41.
 */
class Form_ForgotPasswordTest extends Test_DbTestCase
{

  public function testValidation() {
    $form = new Form_ForgotPassword();
    // Remove csrf token for testing
    $form->removeElement('csrf');
    $data = array(
      'email' => 'email@address.com'
    );
    $valid = $form->isValid($data);
    $this->assertTrue($valid);
  }

  public function testMissingEmail() {
    $form = new Form_ForgotPassword();
    // Remove csrf token for testing
    $form->removeElement('csrf');
    $data = array(
      'email' => null
    );
    $valid = $form->isValid($data);
    $this->assertFalse($valid);
    $errors = $form->getElement('email')->getErrors();
    $this->assertContains('isEmpty', $errors);
  }

  public function testInvalidEmail() {
    $form = new Form_ForgotPassword();
    // Remove csrf token for testing
    $form->removeElement('csrf');
    $data = array(
      'email' => 'imvalid email'
    );
    $valid = $form->isValid($data);
    $this->assertFalse($valid);
    $errors = $form->getErrors();
    $this->assertArrayHasKey('email', $errors);
  }

  public function testInvalidAccount() {
    $form = new Form_ForgotPassword();
    // Remove csrf token for testing
    $form->removeElement('csrf');
    $data = array(
      'email' => 'notfound@address.com'
    );
    $valid = $form->isValid($data);
    $this->assertFalse($valid);
    $errors = $form->getElement('email')->getErrors();
    $this->assertContains(Zend_Validate_Db_RecordExists::ERROR_NO_RECORD_FOUND, $errors);
  }

}

