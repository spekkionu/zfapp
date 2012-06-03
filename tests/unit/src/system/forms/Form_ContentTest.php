<?php

/**
 * Test class for Form_Content.
 * Generated by PHPUnit on 2012-06-02 at 15:21:25.
 */
class Form_ContentTest extends PHPUnit_Framework_TestCase
{

  public function testValidation() {
    $form = new Form_Content();
    // Remove csrf token for testing
    $form->removeElement('csrf');
    $data = array(
      'url' => 'pageurl',
      'title' => 'Page Title',
      'active' => '1',
      'content' => '<p>Page Content</p>'
    );
    $valid = $form->isValid($data);
    $this->assertTrue($valid);
  }

  public function testMissingUrl() {
    $form = new Form_Content();
    // Remove csrf token for testing
    $form->removeElement('csrf');
    $data = array(
      'url' => null,
      'title' => 'Page Title',
      'active' => '1',
      'content' => '<p>Page Content</p>'
    );
    $valid = $form->isValid($data);
    $this->assertFalse($valid);
    $errors = $form->getElement('url')->getErrors();
    $this->assertContains('isEmpty', $errors);
  }

  public function testInvalidUrl() {
    $form = new Form_Content();
    // Remove csrf token for testing
    $form->removeElement('csrf');
    $data = array(
      'url' => 'invalid url &^%',
      'title' => 'Page Title',
      'active' => '1',
      'content' => '<p>Page Content</p>'
    );
    $valid = $form->isValid($data);
    $this->assertFalse($valid);
    $errors = $form->getElement('url')->getErrors();
    $this->assertContains(Zend_Validate_Regex::NOT_MATCH, $errors);
  }

  public function testMissingTitle() {
    $form = new Form_Content();
    // Remove csrf token for testing
    $form->removeElement('csrf');
    $data = array(
      'url' => 'url',
      'title' => '',
      'active' => '1',
      'content' => '<p>Page Content</p>'
    );
    $valid = $form->isValid($data);
    $this->assertFalse($valid);
    $errors = $form->getElement('title')->getErrors();
    $this->assertContains('isEmpty', $errors);
  }

}

