<?php

/**
 * Anchor Tag Helper Class
 *
 * @package App
 * @subpackage View_Helper
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Zend_View_Helper_Anchor extends Zend_View_Helper_HtmlElement
{

    /**
     * Returns an anchor tag as a string
     *
     * @param string $label The text to display inside the link tag
     * @param string $href The href to link to
     * @param array $attribs Attributes to set on the html tag
     * @param boolean $escape If false label will not be escaped
     * @return string
     */
    public function anchor($label, $href = "#", $attribs = array(), $escape = true)
    {
        if ($href) {
            $attribs['href'] = $href;
        }
        if ($escape) {
            $label = $this->view->escape($label);
        }
        return '<a' . $this->_htmlAttribs($attribs) . '>' . $label . '</a>';
    }
}
