<?php

/**
 * Test class for ErrorLogger.
 * Generated by PHPUnit on 2012-03-06 at 10:22:05.
 */
class ErrorLoggerTest extends PHPUnit_Framework_TestCase
{

  /**
   * @var ErrorLogger
   */
  protected $logger;
  protected $writer;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    // Setup Logger
    $this->writer = new Zend_Log_Writer_Mock;
    $this->logger = new Zend_Log($this->writer);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
    ErrorLogger::clearInstance();
  }

  /**
   * test ErrorLogger::setInstance
   */
  public function testSetInstance() {
    ErrorLogger::setInstance($this->logger);
    $logger = ErrorLogger::getInstance();
    $this->assertEquals($logger, $this->logger);
  }

  /**
   * test ErrorLogger::getInstance
   */
  public function testGetInstance() {
    ErrorLogger::setInstance($this->logger);
    $logger = ErrorLogger::getInstance();
    $this->assertEquals($logger, $this->logger);
  }

  /**
   * test ErrorLogger::log
   */
  public function testLog() {
    ErrorLogger::setInstance($this->logger);
    $message = "Log Message";
    ErrorLogger::log($message);
    $log = $this->writer->events[0];
    $this->assertEquals($log['message'], $message);
  }

}

