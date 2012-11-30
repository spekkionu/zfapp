<?php

/**
 * Test class for Cache.
 * Generated by PHPUnit on 2012-03-05 at 21:45:46.
 */
class CacheTest extends PHPUnit_Framework_TestCase
{

  protected $config = array(
    // Cache id prefix, can usually be left alone, needed if multiple applications share the same cache
    'prefix' => 'zfapptest',
    // Caching for general data
    'general' => array(
      // If true caching will be used
      'enabled' => false,
      // The caching method
      'type' => 'File',
      // Set to true if using something other than a default Zend_Cache Backend
      'custom' => false,
      // The maximum lifetime of a cached entry, set to null for no expiration
      "lifetime" => null,
      // Any options for the cache backend go here.  Most will not need any
      'options' => array()
    ),
    // Caching for database schema and metadata
    'dbmetadata' => array(
      // If true caching will be used
      'enabled' => false,
      // The caching method
      'type' => 'File',
      // Set to true if using something other than a default Zend_Cache Backend
      'custom' => false,
      // The maximum lifetime of a cached entry, set to null for no expiration
      "lifetime" => null,
      // Any options for the cache backend go here.  Most will not need any
      'options' => array()
    ),
    // Caching for HTMLPurifier
    'htmlpurifier' => array(
      // If true caching will be used
      'enabled' => true
    ),
  );

  protected $default;

  /**
   * Sets up the fixture, for example, opens a network connection.
   * This method is called before a test is executed.
   */
  protected function setUp() {
    if (!is_dir(TEST_DATA . '/cache/cache')) {
      mkdir(TEST_DATA . '/cache/cache', 0777, true);
    }
    $this->default = Cache::getConfig();
    Cache::setCacheDir(TEST_DATA . '/cache/cache');
    Cache::setConfig($this->config);
  }

  /**
   * Tears down the fixture, for example, closes a network connection.
   * This method is called after a test is executed.
   */
  protected function tearDown() {
    Cache::setConfig($this->default);
    if(is_dir(TEST_DATA . '/cache/cache')){
      // Recursively delete cache directory
      $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(TEST_DATA . '/cache/cache'),RecursiveIteratorIterator::CHILD_FIRST);
      foreach ($iterator as $path) {
        if ($path->isDir()) {
            @rmdir($path->__toString());
        } else {
            @unlink($path->__toString());
        }
      }
    }
  }

  /**
   * test Cache::getManager
   */
  public function testGetManager() {
    $manager = Cache::getManager();
    $this->assertInstanceof('Zend_Cache_Manager', $manager);
  }

  /**
   * test Cache::setConfig
   */
  public function testSetConfig() {
    $config = array('cache' => 'this is a cache config array');
    Cache::setConfig($config);
    $value = Cache::getConfig();
    $this->assertEquals($value, $config);
  }

  /**
   * test Cache::setCacheDir
   */
  public function testSetCacheDir() {
    $path = TEST_DATA . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'test';
    Cache::setCacheDir($path);
    $value = Cache::getCacheDir();
    $this->assertEquals($value, $path);
  }

  /**
   * test Cache::getCache
   */
  public function testGetCache() {
    $key = 'test';
    $cache = Cache::getCache($key);
    $this->assertInstanceof('Zend_Cache_Core', $cache);
  }

  /**
   * test Cache::getHtmlPurifierCache
   */
  public function testGetHtmlPurifierCache() {
    $cachedir = Cache::getCacheDir();
    $key = 'test';
    $expected = $cachedir . DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . $key;
    $value = Cache::getHtmlPurifierCache($key);
    $this->assertEquals($value, $expected);
  }

}

