<?php

/**
 * View Helper to display flash messages
 *
 * @package Simplecart
 * @subpackage View_Helper
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Zend_View_Helper_FlashMessages extends Zend_View_Helper_Abstract
{

    public function flashMessages($namespace = null)
    {
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        if ($namespace) {
            $flashMessenger->setNamespace($namespace);
        }
        $messages = $flashMessenger->getMessages();
        $string = "";
        if (!$messages) {
            return $string;
        }
        $string .= '<div class="flash-messages">';
        foreach ($messages as $message_item) {
            foreach ($message_item as $class => $message) {
                if ($class) {
                    $class = 'alert-' . $class;
                }
                $string .= '<div data-alert="true" class="alert ' . $this->view->escape($class) . '"><a class="close" data-dismiss="alert" href="#">&times;</a>' . $this->view->escape($message) . '</div>';
            }
        }
        $string .= '</div>';
        return $string;
    }
}
