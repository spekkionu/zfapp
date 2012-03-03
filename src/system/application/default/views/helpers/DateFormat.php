<?php

/**
 * Date Format Helper Class
 *
 * @package App
 * @subpackage View_Helper
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Zend_View_Helper_DateFormat extends Zend_View_Helper_Abstract
{

  /**
   * Formats a date and returns it as a string
   * Uses Zend_Date
   *
   * @param Zend_Date|DateTime|string $date
   * @param string $format format matching date()
   * @return string
   */
  public function dateFormat($date, $format = "m/d/Y h:i:s A") {
    if (empty($date)) {
      return null;
    }
    if ($date instanceof Zend_Date) {
      return $zdate->toString($format);
    } elseif ($date instanceof DateTime) {
      return $date->format($format);
    } else {
      $date = date_create($date);
      if ($date === false) {
        return null;
      }
      return $date->format($format);
    }
  }

}