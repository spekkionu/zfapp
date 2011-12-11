<?php
/**
 * Base form class
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class App_Form extends ZendX_JQuery_Form {

  public $field = array(
    'ViewHelper',
    array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('label-container-open'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'closeOnly'=>true)),
    array('Label', array('placement'=>'prepend')),
    array(array('label-container-close'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'openOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $password_strength = array(
    array(array('password_meter_bar'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-bar')),
    array(array('password_meter_bg'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-bg')),
    array(array('password_meter_message'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-message', 'placement'=>'prepend')),
    array(array('password_meter'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter')),
    array('ViewHelper', array('placement'=>'prepend')),
    array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element form-element-password')),
    array(array('label-container-open'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'closeOnly'=>true)),
    array('Label', array('placement'=>'prepend')),
    array(array('label-container-close'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-label', 'placement'=>'prepend', 'openOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $inline = array(
    'ViewHelper',
    array('Description', array('tag' => 'div', 'class'=>'form-description', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array('Label', array('placement'=>'prepend', 'class'=>'form-label')),
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $hidden = array(
    'ViewHelper'
  );

  public $button = array(
    'ViewHelper',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-element')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row'))
  );

  public $buttonOpen = array(
    'ViewHelper',
    array('element'=>'HtmlTag', array('tag' => 'div', 'class'=>'form-element', 'openOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row', 'openOnly'=>true))
  );

  public $buttonMiddle = array(
    'ViewHelper'
  );

  public $buttonClose = array(
    'ViewHelper',
    array('HtmlTag', array('tag' => 'div', 'class'=>'form-element',  'closeOnly'=>true)),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-row', 'closeOnly'=>true))
  );



  public function init(){
    if($this->getView()){
      $this->setAction($this->getView()->url());
    }
    $this->setMethod('post');
    ZendX_JQuery::enableForm($this);
    $this->addElementPrefixPath('Form_Decorator', 'Form/Decorator/', 'decorator');
  }

  /**
   * Returns array of form validation error messages for use in json response
   * If an element has multiple errors they are returned as a string separated by newlines
   * @return array
   */
  public function getJsonErrors($allow_array = true, $separator = "\n"){
    $array = array();
    foreach($this->getMessages() as $element=>$messages){
      $array[$element] = ($allow_array) ? $messages : implode($separator, $messages);
    }
    unset($element, $messages);
    return $array;
  }

  /**
   * Add error class to invalid elements
   * @param array $data
   * @return boolean
   */
  public function isValid($data){
    $valid = parent::isValid($data);
    if(!$valid){
      foreach($this->getMessages() as $element=>$messages){
        $class = $this->getElement($element)->getAttrib('class');
        if(!$class){
          $class = 'error';
        }else{
          // Check if already has error class
          $classes = explode(' ', $class);
          if(!in_array('error', $classes)){
            // Does not already have error class
            $class .= ' error';
          }
        }
        $this->getElement($element)->setAttrib('class', $class);
      }
    }
    return $valid;
  }

}