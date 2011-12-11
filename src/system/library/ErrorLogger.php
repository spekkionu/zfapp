<?php
/**
 * Error Logger
 *
 * @package    App
 * @subpackage Log
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class ErrorLogger {

  /**
   * Zend_Log Instance
   * @var Zend_Log
   */
  private static $log = null;
  /**
   * Class is a static class
   */
  private function __construct(){}

  /**
   * Sets Zend_Log instance
   * @param Zend_Log $log
   * @return void
   */
  public static function setInstance(Zend_Log $log){
    self::$log = $log;
  }
  
  /**
   * Returns logger
   * @return Zend_Log
   */
  public static function getInstance(){
    return self::$log;
  }

  /**
   * Logs a message
   * @param string $message
   * @param int $priority
   */
  public static function log($message, $priority = Zend_Log::ERR){
    self::checkInstance();
    return self::$log->log($message, $priority);
  }

  /**
   * Checks if there is an existing instance
   * @throws Exception
   */
  private static function checkInstance(){
    if(is_null(self::$log)) throw new Exception('No log instance set.');
  }
}