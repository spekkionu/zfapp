<?php

/**
 * Login form.
 *
 * Used in both admin and front end
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_Login extends App_Form
{

  public function loadDefaultDecorators() {
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag' => 'p', 'class' => 'form-help')),
      array('Fieldset', array('legend' => 'Login')),
      array('Form', array('id' => 'form-login', 'class' => 'validate form-horizontal', 'accept-charset' => 'utf-8'))
    ));
  }

  public function init() {
    parent::init();

    $element = new Zend_Form_Element_Text('username');
    $element->setLabel('Username:');
    $element->setDescription("Enter your username.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Username is required."
      )));
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'maxlength' => 20,
      'autofocus' => 'autofocus',
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'alphanumeric' => true,
          'messages' => array(
            'required' => 'Username is required.',
            'alphanumeric' => 'May only contain letters and numbers.'
          )
        )))
    ));
    $this->addElement($element);

    $element = new Zend_Form_Element_Password('password');
    $element->setLabel('Password:');
    $element->setDescription("Enter your password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Password is required."
      )));
    $element->setFilters(array('StringTrim'));
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'maxlength' => 20,
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'nowhitespace' => true,
          'messages' => array(
            'required' => 'Password is required.',
            'nowhitespace' => 'Cannot contain spaces.'
          )
        )))
    ));
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('login');
    $element->setLabel('Login');
    $element->setDecorators($this->buttonOpen);
    $element->setIgnore(true);
    $element->setAttrib('class', 'btn btn-primary');
    $this->addElement($element);

    $element = new Form_Element_LinkButton('forgot_password');
    $element->setLabel('Forgot Password?');
    $element->setDecorators($this->buttonClose);
    $element->setAttrib('class', 'btn cancel');
    $element->setIgnore(true);
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
  }

}