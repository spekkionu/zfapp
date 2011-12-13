<?php

/**
 * Admin profile form.
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_AdminProfile extends App_Form
{

  public function loadDefaultDecorators() {
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag' => 'p', 'class' => 'form-help')),
      array('Fieldset', array()),
      array('Form', array('id' => 'form-profile', 'class' => 'form-stacked validate', 'accept-charset' => 'utf-8'))
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
    $element->addValidator('Alnum', true, array('messages' => array(
        Zend_Validate_Alnum::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_Alnum::NOT_ALNUM => "May only contain letters and numbers.",
        Zend_Validate_Alnum::STRING_EMPTY => "'%value%' is an empty string"
      )));
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'size' => 20,
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
    $element->setDescription("Enter your desired password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "New password is required."
      )));
     $element->addValidator('Regex', true, array('pattern'=>'/^\S*$/i','messages'=>array(
      Zend_Validate_Regex::NOT_MATCH => "Cannot contain spaces."
    )));
    $element->addValidator('StringLength', true, array('messages' => array(
        Zend_Validate_StringLength::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_StringLength::TOO_SHORT => "Must be at least %min% characters.",
        Zend_Validate_StringLength::TOO_LONG => "Must be no more than %max% characters."
      )));
    $element->setAttribs(array(
      'size' => 20,
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
      'size' => 20,
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

    $element = new Zend_Form_Element_Text('firstname');
    $element->setLabel('First Name:');
    $element->setDescription("Enter your first name.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "First name is required."
      )));
    $element->addValidator('StringLength', true, array('max' => 32, 'messages' => array(
        Zend_Validate_StringLength::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_StringLength::TOO_LONG => "Must be no more than %max% characters."
      )));
    $element->setAttribs(array(
      'size' => 32,
      'maxlength' => 32,
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'messages' => array(
            'required' => 'First name is required.',
            'maxlength' => 'Must be no more than 32 characters.'
          )
        )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Text('lastname');
    $element->setLabel('Last Name:');
    $element->setDescription("Enter your last name.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Last name is required."
      )));
    $element->addValidator('StringLength', true, array('max' => 64, 'messages' => array(
        Zend_Validate_StringLength::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_StringLength::TOO_LONG => "Must be no more than %max% characters."
      )));
    $element->setAttribs(array(
      'size' => 40,
      'maxlength' => 64,
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'messages' => array(
            'required' => 'Last name is required.',
            'maxlength' => 'Must be no more than 64 characters.'
          )
        )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Form_Element_Email('email');
    $element->setLabel('Email Address:');
    $element->setDescription("Enter your email address.");
    $element->setRequired(true);
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Email Address is required."
      )));
    $element->addValidator('StringLength', true, array('max' => 127, 'messages' => array(
        Zend_Validate_StringLength::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_StringLength::TOO_LONG => "Must be no more than %max% characters."
      )));
    $element->addValidator('EmailAddress', true, array('allow'=>Zend_Validate_Hostname::ALLOW_ALL, 'messages'=>array(
      Zend_Validate_EmailAddress::INVALID => 'Not a valid email address.',
      Zend_Validate_EmailAddress::INVALID_FORMAT => 'Not a valid email address.',
      Zend_Validate_EmailAddress::INVALID_HOSTNAME => 'Not a valid email address.',
      Zend_Validate_EmailAddress::INVALID_MX_RECORD => 'Not a valid email address.',
      Zend_Validate_EmailAddress::DOT_ATOM => 'Not a valid email address.',
      Zend_Validate_EmailAddress::QUOTED_STRING => 'Not a valid email address.',
      Zend_Validate_EmailAddress::INVALID_LOCAL_PART => 'Not a valid email address.',
      Zend_Validate_EmailAddress::LENGTH_EXCEEDED => 'Not a valid email address.'
    )));
    $element->setAttribs(array(
      'size' => 50,
      'maxlength' => 127,
      'data' => Zend_Json::encode(array('validate' => array(
          'required' => true,
          'email' => true,
          'messages' => array(
            'required' => 'Email Address is required.',
            'maxlength' => 'Must be no more than 127 characters.'
          )
        )))
    ));
    $element->setDecorators($this->field);
    $this->addElement($element);

    $element = new Zend_Form_Element_Checkbox('active');
    $element->setLabel('Active:');
    $element->setDescription("Only active users may log in.");
    $element->setFilters(array('StringTrim', 'StripTags'));
    $element->setDecorators($this->inline);
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('save');
    $element->setLabel('Save');
    $element->setDecorators($this->buttonOpen);
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'btn primary');
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('cancel');
    $element->setLabel('Cancel');
    $element->setDecorators($this->buttonClose);
    $element->setAttrib('class', 'btn');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
  }

  /**
   * Adds database validators
   * @param int $id Account to exclude from unique requirements
   * @return Form_AdminProfile
   */
  public function addDbValidators($id = null) {
    if($this->getElement('email')){
      $validator = new Zend_Validate_Db_NoRecordExists(array(
          'table' => 'users',
          'field' => 'email'
        ));
      if ($id) {
        $validator->setExclude(array('field' => 'id', 'value' => $id));
      }
      $validator->setMessages(array(
        Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => "Another account exists with this email address."
      ));
      $this->getElement('email')->addValidator($validator, true);
    }
    if($this->getElement('username')){
      $validator = new Zend_Validate_Db_NoRecordExists(array(
          'table' => 'users',
          'field' => 'username'
        ));
      if ($id) {
        $validator->setExclude(array('field' => 'id', 'value' => $id));
      }
      $validator->setMessages(array(
        Zend_Validate_Db_NoRecordExists::ERROR_RECORD_FOUND => "Another account exists with this username."
      ));
      $this->getElement('username')->addValidator($validator, true);
    }
    return $this;
  }

}