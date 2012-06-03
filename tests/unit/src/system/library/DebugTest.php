<?php

/**
 * Test class for Debug.
 * Generated by PHPUnit on 2012-03-05 at 21:15:32.
 */
class DebugTest extends PHPUnit_Framework_TestCase
{

  /**
   * test Debug::dump
   * @todo Implement testDump().
   */
  public function testDump() {
    Debug::setSapi('cli');
    $data = 'string';
    $result = Debug::Dump($data, null, false);
    $search = stristr($result, $data);
    $this->assertNotEquals(false, $search);
  }

}

