<?php

/**
 * Test class for Options_Countries.
 * Generated by PHPUnit on 2012-06-02 at 12:00:46.
 */
class Options_CountriesTest extends PHPUnit_Framework_TestCase
{

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    Options_Countries::setXML();
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
    Options_Countries::setXML();
  }

  /**
   * tests Options_Countries::setXML
   * @todo Implement testSetXML().
   */
  public function testSetXML() {
    $file = TEST_DATA . '/countries.xml';
    $countries = Options_Countries::getArray();
    $this->assertArrayHasKey('CA', $countries);
    Options_Countries::setXML($file);
    $countries = Options_Countries::getArray();
    $this->assertArrayNotHasKey('CA', $countries);
  }

  /**
   * tests Options_Countries::clearCache
   * @todo Implement testClearCache().
   */
  public function testClearCache() {
    $countries = Options_Countries::getPairs();
    $is_cached = Options_Countries::isCached();
    $this->assertTrue($is_cached);
    Options_Countries::clearCache();
    $is_cached = Options_Countries::isCached();
    $this->assertFalse($is_cached);
  }

  /**
   * tests Options_Countries::isCached
   * @todo Implement testIsCached().
   */
  public function testIsCached() {
    $is_cached = Options_Countries::isCached();
    $this->assertFalse($is_cached);
    $countries = Options_Countries::getPairs();
    $is_cached = Options_Countries::isCached();
    $this->assertTrue($is_cached);
  }

  /**
   * tests Options_Countries::getArray
   * @todo Implement testGetArray().
   */
  public function testGetArray() {
    $countries = Options_Countries::getArray();
    $this->assertInternalType('array', $countries);
    $this->assertArrayHasKey('US', $countries);
  }

  /**
   * tests Options_Countries::getPairs
   * @todo Implement testGetPairs().
   */
  public function testGetPairs() {
    $countries = Options_Countries::getPairs();
    $this->assertInternalType('array', $countries);
    $this->assertArrayHasKey('US', $countries);
    $this->assertEquals('United States', $countries['US']);
  }

  /**
   * tests Options_Countries::getCountry
   * @todo Implement testGetCountry().
   */
  public function testGetCountry() {
    $country = Options_Countries::getCountry('US');
    $this->assertEquals('United States', $country);
  }

}

