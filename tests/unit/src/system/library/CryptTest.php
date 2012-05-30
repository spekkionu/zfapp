<?php

/**
 * Test class for Crypt.
 * Generated by PHPUnit on 2011-12-15 at 10:29:02.
 */
class CryptTest extends PHPUnit_Framework_TestCase
{

  /**
   * test Crypt::getAlgorithm
   */
  public function testKey() {
    $key = "dtgjhGGFDStregfdgfJRTHrdbf";
    $crypt = new Crypt($key, MCRYPT_RIJNDAEL_256);
    $crypt->setAlgorithm(MCRYPT_BLOWFISH);
    $algorithm = $crypt->getAlgorithm();

    $this->assertEquals($algorithm, MCRYPT_BLOWFISH);
  }

  /**
   * test Crypt::encrypt
   */
  public function testEncrypt() {
    $text = "Raw Text";
    $key = "dtgjhGGFDStregfdgfJRTHrdbf";
    $crypt = new Crypt($key, MCRYPT_RIJNDAEL_256);
    $encrypted = $crypt->encrypt($text);
    $this->assertNotEquals($text, $encrypted);
  }

  /**
   * test Crypt::decrypt
   */
  public function testDecrypt() {
    $text = "Raw Text";
    $key = "dtgjhGGFDStregfdgfJRTHrdbf";
    $crypt = new Crypt($key, MCRYPT_RIJNDAEL_256);
    $encrypted = $crypt->encrypt($text);
    $decrypted = $crypt->decrypt($encrypted);
    $this->assertEquals($decrypted, $text);
  }

}

