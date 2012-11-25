<?php

/**
 * Anchor Tag Helper Class
 *
 * @package App
 * @subpackage View_Helper
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Zend_View_Helper_LinkButton extends Zend_View_Helper_FormElement
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
    public function linkButton($name, $value = null, $attribs = null, $escape = true)
    {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, disable, id
        if ($id) {
            $id = ' id="' . $this->view->escape($id) . '"';
        }
        // check if disabled
        $disabled = '';
        if ($disable) {
            if (isset($attribs['class'])) {
                $class = explode(' ', $attribs['class']);
                $found = array_search('disabled', $class);
                if ($found === false) {
                    $attribs['class'] .= ' disabled';
                }
            } else {
                $attribs['class'] = 'disabled';
            }
        }
        $xhtml = '<a '
          . $id
          . ' ' . $this->_htmlAttribs($attribs) . '>' . $this->view->escape($value) . '</a>';
        return $xhtml;
    }

}