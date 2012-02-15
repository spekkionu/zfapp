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
    array('Description', array('tag' => 'div', 'class'=>'help-block', 'placement'=>'append', 'escape'=>false)),
    array('Errors', array()),
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'controls')),
    array('Label', array('placement'=>'prepend', 'class' => 'control-label')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'control-group'))
  );

  public $option_list = array(
    'ViewHelper',
    array('Description', array('tag' => 'div', 'class'=>'help-block', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'controls')),
    array('Label', array('placement'=>'prepend', 'class' => 'control-label')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'control-group'))
  );

  public $column_left = array(
    'ViewHelper',
    array('Description', array('tag' => 'div', 'class'=>'help-block', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'controls')),
    array('Label', array('placement'=>'prepend')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'control-group span5')),
    array(array('wrapper'=>'HtmlTag'), array('tag' => 'div', 'class'=>'row', 'openOnly'=>true))
  );

  public $column_right = array(
    'ViewHelper',
    array('Description', array('tag' => 'div', 'class'=>'help-block', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'controls')),
    array('Label', array('placement'=>'prepend')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'control-group span5')),
    array(array('wrapper'=>'HtmlTag'), array('tag' => 'div', 'class'=>'row', 'closeOnly'=>true))
  );

  public $password_strength = array(
    array(array('password_meter_bar'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-bar')),
    array(array('password_meter_bg'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-bg')),
    array(array('password_meter_message'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter-message', 'placement'=>'prepend')),
    array(array('password_meter'=>'HtmlTag'), array('tag' => 'div', 'class'=>'password-meter')),
    array('ViewHelper', array('placement'=>'prepend')),
    array('Description', array('tag' => 'div', 'class'=>'help-block', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'controls form-element-password')),
    array('Label', array('placement'=>'prepend')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'control-group'))
  );

  public $inline = array(
    'ViewHelper',
    array('Description', array('tag' => 'div', 'class'=>'help-block', 'placement'=>'append', 'escape'=>false)),
    'Errors',
    array('Label', array('placement'=>'prepend', 'class'=>'inline')),
    array(array('element'=>'HtmlTag'), array('tag' => 'div', 'class'=>'controls')),
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'control-group'))
  );

  public $hidden = array(
    'ViewHelper'
  );

  public $button = array(
    'ViewHelper',
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-actions'))
  );

  public $buttonOpen = array(
    'ViewHelper',
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-actions', 'openOnly'=>true))
  );

  public $buttonMiddle = array(
    'ViewHelper'
  );

  public $buttonClose = array(
    'ViewHelper',
    array(array('row'=>'HtmlTag'), array('tag' => 'div', 'class'=>'form-actions', 'closeOnly'=>true))
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
        $decorator = $this->getElement($element)->getDecorator('row');
        if(!$decorator){
          continue;
        }
        $class = $decorator->getOption('class');
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
        $this->getElement($element)->getDecorator('row')->setOption('class', $class);
      }
    }
    return $valid;
  }

}