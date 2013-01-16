<?php

/**
 * Form decorator for time to be inserted by select inputs for hours, mintutes, am/pm
 *
 * @package    App
 * @subpackage Zend_Form_Decorator
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class App_Form_Decorator_Addon extends Zend_Form_Decorator_HtmlTag
{

    public function render($content)
    {
        $tag = $this->getTag();
        $addon = $this->getOption('content');
        $noAttribs = $this->getOption('noAttribs');
        $openOnly = $this->getOption('openOnly');
        $closeOnly = $this->getOption('closeOnly');
        $this->removeOption('noAttribs');
        $this->removeOption('openOnly');
        $this->removeOption('closeOnly');
        $this->removeOption('content');
        $this->removeOption('placement');

        $attribs = null;
        if (!$noAttribs) {
            $attribs = $this->getOptions();
        }

        return $this->_getOpenTag($tag, $attribs)
          . $addon
          . $this->_getCloseTag($tag)
          . $content;
    }
}
