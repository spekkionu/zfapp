<?php

/**
 * Wrapper for Zend_View_Helper_Translate
 *
 * @package App
 * @subpackage View_Helper
 * @see Zend_View_Helper_Translate
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Zend_View_Helper_T extends Zend_View_Helper_Abstract
{

  /**
   * Translate a message
   * You can give multiple params or an array of params.
   * If you want to output another locale just set it as last single parameter
   * Example 1: translate('%1\$s + %2\$s', $value1, $value2, $locale);
   * Example 2: translate('%1\$s + %2\$s', array($value1, $value2), $locale);
   *
   * @param  string $messageid Id of the message to be translated
   * @return string|Zend_View_Helper_Translate Translated message
   */
  public function t($messageid = null)
  {
    return $this->view->translate($messageid);
  }

}