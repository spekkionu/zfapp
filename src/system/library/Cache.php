<?php

/**
 * Cache manager
 *
 * @package    App
 * @subpackage Cache
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Cache
{

  private static $manager = null;
  private static $cachedir = null;
  private static $config;

  /**
   * Returns Zend Cache Manager Instance
   * @return Zend_Cache_Manager
   */
  public static function getManager() {
    if (is_null(self::$manager)) {
      self::$manager = new Zend_Cache_Manager;
    }
    return self::$manager;
  }

  /**
   * Sets Cache Config Options
   * @param Zend_Config|array $config
   */
  public static function setConfig($config) {
    if ($config instanceof Zend_Config) {
      self::$config = $config->toArray();
    } elseif (is_array($config)) {
      self::$config = $config;
    } else {
      throw new Exception("Cache config must be and instance of Zend_Config or an array");
    }
  }

  /**
   * Returns current cache config
   * @return array
   */
  public static function getConfig() {
    return self::$config;
  }

  /**
   * Removes config
   */
  public static function clearConfig() {
    self::$config = null;
  }

  /**
   * Sets the cache directory
   * @param string $path
   * @return void
   */
  public static function setCacheDir($path) {
    if (!is_dir($path)) {
      // If the cache dir doesn't exist, true to create it
      @mkdir($path, 0777, true);
    }
    if (!is_dir($path)) {
      throw new Exception('Cache directory does not exist and could not be created.');
    }
    self::$cachedir = realpath($path);
  }

  /**
   * Returns current cache dir
   */
  public static function getCacheDir() {
    return self::$cachedir;
  }

  /**
   * Creates a cache instance
   * @param array $config
   * @param string $cachedir
   * @return Zend_Cache_Core
   */
  private static function createCache(array $config, $cachedir, $key) {
    if (!is_dir($cachedir)) {
      // Directory does not exist, try to create it
      if (@mkdir($cachedir, 0777, true) === false) {
        throw new Exception('Cache directory does not exist and could not be created.');
      }
    }
    $front = array('automatic_serialization' => true, 'cache_id_prefix' => self::$config['prefix'] . '_' . $key . '_');
    if (isset($config['lifetime']) && $config['lifetime'] > 0) {
      $front['lifetime'] = intval($config['lifetime']);
    } else {
      $front['lifetime'] = null;
    }
    $front['caching'] = (bool) $config['enabled'];
    if ($config['type'] == 'File') {
      $cache = Zend_Cache::factory('Core', 'File', $front, array('cache_dir' => $cachedir));
    } else {
      // Use Two Level Cache
      $cache = Zend_Cache::factory('Core', 'Two Levels', $front, array(
          'slow_backend' => 'File',
          'slow_backend_options' => array(
            'cache_dir' => $cachedir
          ),
          'fast_backend' => $config['type'],
          'fast_backend_custom_naming' => (isset($config['custom']) && $config['custom']),
          'fast_backend_options' => $config['options']
        ));
    }
    return $cache;
  }

  /**
   * Returns the cache instance for the given key
   * @param string $key
   * @return Zend_Cache_Core
   */
  public static function getCache($key) {
    $key = preg_replace("/[^a-z0-9_]/i", '', strtolower($key));
    // This would otherwise break the config
    if ($key == 'prefix') {
      $key = 'prefixcache';
    }
    if (!$key) {
      throw new Exception('Must provide a valid key.');
    }
    $manager = self::getManager();
    if ($manager->hasCache($key)) {
      // Cache already set, return it
      return $manager->getCache($key);
    } else {
      // Create Cache and store it for later use
      $config = isset(self::$config[$key]) ? self::$config[$key] : self::$config['general'];
      $cache = self::createCache($config, self::$cachedir . DIRECTORY_SEPARATOR . $key, $key);
      $manager->setCache($key, $cache);
      return $cache;
    }
  }

  /**
   * Returns htmlpurifier cache directory
   * @param string $key The instance path
   * @return string
   */
  public static function getHtmlPurifierCache($key = 'default') {
    $key = preg_replace("/[^a-z0-9_]/i", '', strtolower($key));
    if (!$key) {
      $key = 'default';
    }
    if (is_null(self::$cachedir)) {
      throw new Exception('Cache directory not set.');
    }
    if (!is_dir(self::$cachedir . DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . $key)) {
      // Directory does not exist, try to create it
      if (@mkdir(self::$cachedir . DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . $key, 0777, true) === false) {
        throw new Exception('HTML Purifier cache directory does not exist and could not be created.');
      }
    }
    return self::$cachedir . DIRECTORY_SEPARATOR . 'htmlpurifier' . DIRECTORY_SEPARATOR . $key;
  }

}
