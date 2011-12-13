<?php
/**
 * Generic delete form
 *
 * Used for all deletes
 *
 * @package    App
 * @subpackage Form
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_Delete extends App_Form
{

  public function loadDefaultDecorators() {
    $this->setDecorators(array(
      'FormElements',
      array('Description', array('tag' => 'p', 'class' => 'form-help')),
      array('Fieldset', array()),
      array('Form', array('id' => 'form-category', 'class' => 'form-stacked', 'accept-charset' => 'utf-8'))
    ));
  }

  public function init() {
    parent::init();

    $element = new Zend_Form_Element_Submit('delete');
    $element->setLabel('Confirm Delete');
    $element->setDecorators($this->buttonOpen);
    $element->setIgnore(TRUE);
    $element->setAttrib('class', 'btn primary');
    $this->addElement($element);

    $element = new Zend_Form_Element_Submit('cancel');
    $element->setLabel('Cancel');
    $element->setDecorators($this->buttonClose);
    $element->setAttrib('class', 'btn cancel');
    $this->addElement($element);

    $this->addElement('hash', 'csrf', array('ignore' => true, 'decorators' => $this->hidden));
  }

}