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
      array('Fieldset', array('legend'=>'Login')),
      array('Form', array('id' => 'form-login', 'class' => 'validate form-stacked', 'accept-charset' => 'utf-8'))
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
    $element->setDescription("Enter your password.");
    $element->setRequired(true);
    $element->addValidator('NotEmpty', true, array('messages' => array(
        Zend_Validate_NotEmpty::INVALID => "Invalid type given. String, integer or float expected",
        Zend_Validate_NotEmpty::IS_EMPTY => "Password is required."
      )));
    $element->addValidator('Regex', true, array('pattern'=>'/^\S*$/i','messages'=>array(
      Zend_Validate_Regex::NOT_MATCH => "Cannot contain spaces."
    )));
    $element->setFilters(array('StringTrim'));
    $element->setDecorators($this->field);
    $element->setAttribs(array(
      'size' => 20,
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
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'btn primary');
    $this->addElement($element);

    $element = new Form_Element_LinkButton('forgot_password');
    $element->setLabel('Forgot Password?');
    $element->setDecorators($this->buttonClose);
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'btn');
    $element->setAttrib('href', $this->getView()->route('admin_forgot_password'));
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
  }

}