<?php

/**
 * Forgot password form
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_ForgotPassword extends App_Form
{

    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
          'FormElements',
          array('Description', array('tag' => 'p', 'class' => 'form-help')),
          array('Fieldset', array()),
          array('Form', array('id' => 'form-forgot-password', 'class' => 'validate form-horizontal', 'accept-charset' => 'utf-8'))
        ));
    }

    public function init()
    {
        parent::init();

        $validator = new Zend_Validate_Db_RecordExists(array(
            'table' => 'users',
            'field' => 'email'
          ));
        $validator->setMessages(array(
          Zend_Validate_Db_RecordExists::ERROR_NO_RECORD_FOUND => "Account with email address %value% not found."
        ));
        $element = new App_Form_Element_Email('email');
        $element->setLabel('Email Address:');
        $element->setDescription("Enter your email address.");
        $element->setRequired(true);
        $element->setFilters(array('StringTrim', 'StripTags'));
        $element->addValidator('NotEmpty', true, array('messages' => array(
            Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_NotEmpty::IS_EMPTY => "Email Address is required."
          )));
        $element->addValidator('EmailAddress', true, array('allow' => Zend_Validate_Hostname::ALLOW_ALL, 'messages' => array(
            Zend_Validate_EmailAddress::INVALID => 'Not a valid email address.',
            Zend_Validate_EmailAddress::INVALID_FORMAT => 'Not a valid email address.',
            Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Not a valid email address.',
            Zend_Validate_EmailAddress::INVALID_MX_RECORD => 'Not a valid email address.',
            Zend_Validate_EmailAddress::DOT_ATOM => 'Not a valid email address.',
            Zend_Validate_EmailAddress::QUOTED_STRING => 'Not a valid email address.',
            Zend_Validate_EmailAddress::INVALID_LOCAL_PART => 'Not a valid email address.',
            Zend_Validate_EmailAddress::LENGTH_EXCEEDED => 'Not a valid email address.'
          )));
        $element->addValidator($validator, true);
        $element->setAttribs(array(
          'size' => 50,
          'maxlength' => 127,
          'autofocus' => 'autofocus',
          'data' => Zend_Json::encode(array('validate' => array(
              'required' => true,
              'email' => true,
              'messages' => array(
                'required' => 'Email Address is required.'
              )
            )))
        ));
        $element->setDecorators($this->field);
        $this->addElement($element);

        $element = new Zend_Form_Element_Submit('save');
        $element->setLabel('Send Password Request');
        $element->setDecorators($this->buttonOpen);
        $element->setAttrib('class', 'btn btn-primary');
        $element->setIgnore(true);
        $this->addElement($element);

        $element = new App_Form_Element_LinkButton('cancel');
        $element->setLabel('Cancel');
        $element->setDecorators($this->buttonClose);
        $element->setAttrib('class', 'btn cancel');
        $element->setIgnore(true);
        $this->addElement($element);

        $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
    }

}