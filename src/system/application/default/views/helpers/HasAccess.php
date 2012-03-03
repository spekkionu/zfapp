<?php

/**
 * Checks if logged in user has access to the requested resource
 *
 * @package Simplecart
 * @subpackage View_Helper
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Zend_View_Helper_HasAccess extends Zend_View_Helper_Abstract
{

  /**
   * Checks if logged in user has access to the requested resource.
   * @param string $resource
   * @param string $privilege
   * @return bool
   */
  public function hasAccess($resource = 'general', $privilege = null) {
    $auth = Zend_Auth::getInstance();
    $acl = Zend_Registry::get('Zend_Acl');
    if (!$auth->hasIdentity()) {
      return false;
    }
    $identity = $auth->getIdentity();
    return $acl->isAllowed($identity->accesslevel, $resource, $privilege);
  }

}