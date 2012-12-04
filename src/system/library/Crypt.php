<?php

/**
 * Encryption Class
 *
 * @package    App
 */
class Crypt
{

    /**
     * Default Encryption key
     * @var string $key
     */
    private static $key = null;

    /**
     * Encryption key
     * Used for instance
     * @var string $key
     */
    private $_key;

    /**
     * Default Encryption algorithm, defaults to MCRYPT_RIJNDAEL_256
     * @var string $algorithm
     */
    private static $algorithm = 'rijndael-256';

    /**
     * Encryption algorithm
     * Used for instance
     * @var string $_algorithm
     */
    private $_algorithm;

    /**
     * Default Encryption mode, defaults to MCRYPT_MODE_ECB
     * @var string $mode
     */
    private static $mode = 'ecb';

    /**
     * Encryption mode
     * Used for instance
     * @var string $mode
     */
    private $_mode;

    /**
     * Sets default options
     * @param array $options
     */
    public static function setDefaults(array $options)
    {
        if (isset($options['key'])) {
            self::$key = $options['key'];
        }
        if (isset($options['algorithm'])) {
            self::$algorithm = $options['algorithm'];
        }
        if (isset($options['mode'])) {
            self::$mode = $options['mode'];
        }
    }

    /**
     * Class constructor
     *
     * @param string $key Override default with this key
     * @param string $algorithm Override default algorithm with this one
     * @param string $mode Override default mode with this one
     * @throws Exception if mcrypt extension is not installed
     */
    public function __construct($key = null, $algorithm = null, $mode = null)
    {
        if (!extension_loaded('mcrypt')) {
            throw new Exception("Must have mcrypt extension installed");
        }
        if ($key) {
            $this->setKey($key);
        } else {
            $this->setKey(self::$key);
        }
        if ($algorithm) {
            $this->setAlgorithm($algorithm);
        } else {
            $this->setAlgorithm(self::$algorithm);
        }
        if ($mode) {
            $this->setMode($mode);
        } else {
            $this->setMode(self::$mode);
        }
    }

    /**
     * Sets the encryption key.
     * @param $key
     * @return Crypt
     */
    public function setKey($key)
    {
        if (empty($key)) {
            throw new Exception("Cannot set key to empty value");
        }
        $this->_key = $key;
        return $this;
    }

    /**
     * Returns the correct size key for the algorithm
     * @return string
     */
    private function _getKey()
    {
        $key_size = mcrypt_get_key_size($this->_algorithm, $this->_mode);
        $key = str_pad($this->_key, $key_size, '0');
        $key = substr($key, 0, $key_size);
        return $key;
    }

    /**
     * Generates a random IV
     * @return string
     */
    private function _generateIV()
    {
        // Generate an IV of the correct size.
        $iv_size = mcrypt_get_iv_size($this->_algorithm, $this->_mode);
        return mcrypt_create_iv($iv_size, MCRYPT_RAND);
    }

    /**
     * Sets encryption algorithm
     * @param $algorithm
     * @return Crypt
     */
    public function setAlgorithm($algorithm)
    {
        // Make sure algorythm is available
        if (!in_array($algorithm, mcrypt_list_algorithms())) {
            throw new Exception("MCRYPT Algorithm {$algorithm} is not available.");
        }
        $this->_algorithm = $algorithm;
        return $this;
    }

    /**
     * Algorithm getter
     * @return string
     */
    public function getAlgorithm()
    {
        return $this->_algorithm;
    }

    /**
     * Sets encryption mode
     * @param string $mode
     * @return Crypt
     * @throws Exception
     */
    public function setMode($mode)
    {
        if (!in_array($mode, array('cbc', 'cfb', 'ecb', 'nofb', 'ofb', 'stream'))) {
            throw new Exception("Invalid mode. Must be a valid mcrypt mode.");
        }
        $this->_mode = $mode;
        return $this;
    }

    /**
     * Returns encryption mode
     * @return string
     */
    public function getMode()
    {
        return $this->_mode;
    }

    /**
     * Encrypts data
     * @param mixed $data
     * @return string
     */
    public function encrypt($data)
    {
        // Serialize the data into a string
        $data = serialize($data);
        // Encrypt the data
        $data = mcrypt_encrypt($this->_algorithm, $this->_getKey(), $data, $this->_mode, $this->_generateIV());
        // Encode and trim it
        return base64_encode($data);
    }

    /**
     * Decrypts data
     * @param string $data
     * @return string
     */
    public function decrypt($data)
    {
        // Decode the data
        $data = base64_decode($data);
        // Decrypt the data
        $data = mcrypt_decrypt($this->_algorithm, $this->_getKey(), $data, $this->_mode, $this->_generateIV());
        // Unserialize the data
        if ($data === serialize(false)) {
            return false;
        }
        $data = @unserialize($data);
        return ($data !== false) ? $data : null;
    }

}