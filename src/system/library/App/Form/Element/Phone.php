<?php

/**
 * HTML5 tel input
 *
 * @package    Simplecart
 * @subpackage Zend_Form_Element
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Form_Element_Phone extends Zend_Form_Element_Text
{

    /**
     * Use ValidationTextBox dijit view helper
     * @var string
     */
    public $helper = 'PhoneTextBox';
}
