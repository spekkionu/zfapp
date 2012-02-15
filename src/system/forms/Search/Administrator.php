<?php

/**
 * Admin profile form.
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_Search_Administrator extends App_Form
{

  public function loadDefaultDecorators() {
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag' => 'p', 'class' => 'form-help')),
      array('Fieldset', array()),
      array('Form', array('id' => 'form-search-profile', 'class' => 'search form-horizontal', 'accept-charset' => 'utf-8'))
    ));
  }

  public function init() {
    parent::init();

    $element = new Zend_Form_Element_Text('username');
    $element->setLabel('Username:');
    $element->setDescription("Search by username.");
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setDecorators($this->column_left);
    $element->setAttribs(array(
      'size' => 20,
      'maxlength' => 20,
      'autofocus' => 'autofocus'
    ));
    $this->addElement($element);

    $element = new Zend_Form_Element_Text('name');
    $element->setLabel('Name:');
    $element->setDescription("Search by name.");
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setAttribs(array(
      'size' => 32,
      'maxlength' => 32
    ));
    $element->setDecorators($this->column_right);
    $this->addElement($element);

    $element = new Form_Element_Email('email');
    $element->setLabel('Email Address:');
    $element->setDescription("Search by email address.");
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setAttribs(array(
      'size' => 50,
      'maxlength' => 127
    ));
    $element->setDecorators($this->column_left);
    $this->addElement($element);

    $element = new Zend_Form_Element_Select('active');
    $element->setLabel('Status:');
    $element->setDescription("Search by account status.");
    $element->setMultiOptions(array(
      '' => 'All',
      '1' => 'Active',
      '0' => 'Inactive'
    ));
    $element->setDecorators($this->column_right);
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('search');
    $element->setLabel('Search');
    $element->setDecorators($this->buttonOpen);
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'btn btn-primary');
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('clear');
    $element->setLabel('Clear');
    $element->setDecorators($this->buttonClose);
    $element->setAttrib('class', 'btn cancel');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
  }


}