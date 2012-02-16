<?php

/**
 * Admin cms search form.
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_Search_Content extends App_Form
{

  public function loadDefaultDecorators() {
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag' => 'p', 'class' => 'form-help')),
      array('Fieldset', array()),
      array('Form', array('id' => 'form-search-content', 'class' => 'search form-horizontal', 'accept-charset' => 'utf-8'))
    ));
  }

  public function init() {
    parent::init();

    $element = new Zend_Form_Element_Text('url');
    $element->setLabel('URL:');
    $element->setDescription("Search by url.");
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setDecorators($this->column_left);
    $element->setAttribs(array(
      'size' => 20,
      'maxlength' => 20,
      'autofocus' => 'autofocus'
    ));
    $this->addElement($element);

    $element = new Zend_Form_Element_Text('title');
    $element->setLabel('Title:');
    $element->setDescription("Search by title.");
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setAttribs(array(
      'size' => 32,
      'maxlength' => 32
    ));
    $element->setDecorators($this->column_right);
    $this->addElement($element);


    $element = new Zend_Form_Element_Select('active');
    $element->setLabel('Status:');
    $element->setDescription("Search by page status.");
    $element->setMultiOptions(array(
      '' => 'All',
      '1' => 'Active',
      '0' => 'Inactive'
    ));
    $element->setDecorators($this->field);
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