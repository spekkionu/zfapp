<?php

/**
 * Encrypts sessions data before saving
 *
 * Stores session in db
 *
 * @uses Zend_Session_SaveHandler_DbTable
 * @uses Crypt
 */
class Session_SaveHandler_EncryptedDb extends Zend_Session_SaveHandler_DbTable implements Zend_Session_SaveHandler_Interface
{

  const CRYPT_KEY = "cryptKey";
  const CRYPT_ALGORYTHM = "cryptAlgorythm";

  protected $crypt_key = "fghfdHBGFhjtgdsHGRFGdsfnHGJDFGdsferfdGFJGFBxchgj";
  protected $algorithm = MCRYPT_RIJNDAEL_256;

  /**
   * Sets the encryption key.
   * @param string $key
   * @return void
   */
  public function setCryptKey($key) {
    $this->crypt_key = $key;
  }

  /**
   *
   * @param $algorithm
   * @return void
   */
  public function setAlgorithm($algorithm) {
    // Make sure algorythm is available
    if (!in_array($algorithm, mcrypt_list_algorithms())) {
      throw new Zend_Session_SaveHandler_Exception("MCRYPT Algorithm {$algorithm} is not available.");
    }
    $this->algorithm = $algorithm;
    return $this;
  }

  /**
   * Constructor
   *
   * $config is an instance of Zend_Config or an array of key/value pairs containing configuration options for
   * Zend_Session_SaveHandler_DbTable and Zend_Db_Table_Abstract. These are the configuration options for
   * Zend_Session_SaveHandler_DbTable:
   *
   * primaryAssignment => (string|array) Session table primary key value assignment
   *      (optional; default: 1 => sessionId) You have to assign a value to each primary key of your session table.
   *      The value of this configuration option is either a string if you have only one primary key or an array if
   *      you have multiple primary keys. The array consists of numeric keys starting at 1 and string values. There
   *      are some values which will be replaced by session information:
   *
   *      sessionId       => The id of the current session
   *      sessionName     => The name of the current session
   *      sessionSavePath => The save path of the current session
   *
   *      NOTE: One of your assignments MUST contain 'sessionId' as value!
   *
   * modifiedColumn    => (string) Session table last modification time column
   *
   * lifetimeColumn    => (string) Session table lifetime column
   *
   * dataColumn        => (string) Session table data column
   *
   * lifetime          => (integer) Session lifetime (optional; default: ini_get('session.gc_maxlifetime'))
   *
   * overrideLifetime  => (boolean) Whether or not the lifetime of an existing session should be overridden
   *      (optional; default: false)
   *
   * cryptKey          => (string) Encryption key (default: string in $this->$crypt_key)
   *
   * cryptAlgorythm    => (int) Encryption Algorythm (default: MCRYPT_RIJNDAEL_256)
   *
   * @param  Zend_Config|array $config      User-provided configuration
   * @return void
   * @throws Zend_Session_SaveHandler_Exception
   */
  public function __construct($config) {
    if ($config instanceof Zend_Config) {
      $config = $config->toArray();
    } else if (!is_array($config)) {
      /**
       * @see Zend_Session_SaveHandler_Exception
       */
      require_once 'Zend/Session/SaveHandler/Exception.php';

      throw new Zend_Session_SaveHandler_Exception("$config must be an instance of Zend_Config or array of key/value pairs containing configuration options for Zend_Session_SaveHandler_DbTable and Zend_Db_Table_Abstract.");
    }

    foreach ($config as $key => $value) {
      do {
        switch ($key) {
          case self::CRYPT_KEY:
            $this->setCryptKey($value);
            break;
          case self::CRYPT_ALGORYTHM:
            $this->setAlgorithm($value);
            break;
          default:
            // unrecognized options passed to parent::__construct()
            break 2;
        }
        unset($config[$key]);
      } while (false);
    }

    parent::__construct($config);
  }

  /**
   * Read session data
   *
   * @param string $id
   * @return string
   */
  public function read($id) {
    $return = parent::read($id);
    if ($return == '') {
      return $return;
    }
    $crypt = new Crypt($this->crypt_key, $this->algorithm);
    $return = $crypt->decrypt($return);
    return $return;
  }

  /**
   * Write session data
   *
   * @param string $id
   * @param string $data
   * @return boolean
   */
  public function write($id, $data) {
    $crypt = new Crypt($this->crypt_key, $this->algorithm);
    $data = $crypt->encrypt($data);
    return parent::write($id, $data);
  }

}
