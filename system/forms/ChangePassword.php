<?php

/**
 * Change password form
 *
 * Used in both front end and admin
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_ChangePassword extends App_Form
{

    public function loadDefaultDecorators()
    {
        $this->setDecorators(array(
          'FormElements',
          array('Description', array('tag' => 'p', 'class' => 'form-help')),
          array('Fieldset', array()),
          array('Form', array('id' => 'form-change-password', 'class' => 'validate form-horizontal', 'accept-charset' => 'utf-8'))
        ));
    }

    public function init()
    {
        parent::init();

        $element = new Zend_Form_Element_Password('old_password');
        $element->setLabel('Current Password:');
        $element->setDescription("Enter your current password.");
        $element->setRequired(true);
        $element->setFilters(array('StringTrim'));
        $element->addValidator('NotEmpty', true, array('messages' => array(
            Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_NotEmpty::IS_EMPTY => "Current password is required."
          )));
        $element->setAttribs(array(
          'maxlength' => 20,
          'data' => Zend_Json::encode(array('validate' => array(
              'required' => true,
              'nowhitespace' => true,
              'messages' => array(
                'required' => 'Current password is required.',
                'nowhitespace' => 'Cannot contain spaces.'
              )
            )))
        ));
        $element->setDecorators($this->field);
        $this->addElement($element);

        $element = new Zend_Form_Element_Password('password');
        $element->setLabel('New Password:');
        $element->setDescription("Enter your desired new password.");
        $element->setRequired(true);
        $element->addValidator('NotEmpty', true, array('messages' => array(
            Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_NotEmpty::IS_EMPTY => "New password is required."
          )));
        $element->addValidator('Regex', true, array('pattern' => '/^\S*$/i', 'messages' => array(
            Zend_Validate_Regex::NOT_MATCH => "Cannot contain spaces."
          )));
        $element->addValidator('StringLength', true, array('min' => 4, 'max' => 20, 'messages' => array(
            Zend_Validate_StringLength::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_StringLength::TOO_SHORT => "Must be at least %min% characters.",
            Zend_Validate_StringLength::TOO_LONG => "Must be no more than %max% characters."
          )));
        $element->setAttribs(array(
          'maxlength' => 20,
          'data' => Zend_Json::encode(array('validate' => array(
              'required' => true,
              'nowhitespace' => true,
              'maxlength' => 20,
              'minlength' => 4,
              'password' => true,
              'messages' => array(
                'required' => 'New password is required.',
                'nowhitespace' => 'Cannot contain spaces.'
              )
            )))
        ));
        $element->setDecorators($this->password_strength);
        $this->addElement($element);

        $element = new Zend_Form_Element_Password('confirm_password');
        $element->setLabel('Repeat Password:');
        $element->setDescription("Repeat your desired new password.");
        $element->setRequired(true);
        $element->setFilters(array('StringTrim'));
        $element->addValidator('NotEmpty', true, array('messages' => array(
            Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
            Zend_Validate_NotEmpty::IS_EMPTY => "You must repeat your password"
          )));
        $element->addValidator('Identical', true, array('token' => 'password', 'messages' => array(
            Zend_Validate_Identical::NOT_SAME => 'Passwords do not match',
            Zend_Validate_Identical::MISSING_TOKEN => 'Passwords do not match'
          )));
        $element->setAttribs(array(
          'maxlength' => 20,
          'data' => Zend_Json::encode(array('validate' => array(
              'required' => true,
              'nowhitespace' => true,
              'equalTo' => "#password",
              'messages' => array(
                'required' => 'You must repeat your password.',
                'nowhitespace' => 'Cannot contain spaces.',
                'equalTo' => 'Passwords do not match'
              )
            )))
        ));
        $element->setDecorators($this->field);
        $this->addElement($element);

        $element = new Zend_Form_Element_Submit('save');
        $element->setLabel('Change Password');
        $element->setDecorators($this->buttonOpen);
        $element->setIgnore(true);
        $element->setAttrib('class', 'btn btn-primary');
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
