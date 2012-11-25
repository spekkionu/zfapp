<?php

/**
 * Mock Session handler
 *
 * Stores session in file
 */
class Session_SaveHandler_Mock implements Zend_Session_SaveHandler_Interface
{

    /**
     * Path of the session file
     *
     * @var string
     */
    private static $_path;

    /**
     * Session name (optional)
     *
     * @var string
     */
    private static $_name;

    /**
     * Stored data
     * @var array
     */
    private static $_data = array();

    /**
     * Open the session
     *
     * @param string $save_path
     * @param string $session_name
     * @return bool
     */
    public function open($save_path, $session_name)
    {
        self::$_path = $save_path;
        self::$_name = $session_name;

        self::$_data[self::$_name] = array();
        return true;
    }

    /**
     * Close the session
     *
     * @return bool
     */
    public function close()
    {
        return true;
    }

    /**
     * Read the session
     *
     * @param integer $id
     * @return string
     */
    public function read($id)
    {
        if (array_key_exists($id, self::$_data[self::$_name])) {
          return self::$_data[self::$_name][$id];
        } else {
          return false;
        }
    }

    /**
     * Encrypt and write the session
     *
     * @param integer $id
     * @param string $data
     * @return bool
     */
    public function write($id, $data)
    {
        self::$_data[self::$_name][$id] = $data;
        return true;
    }

    /**
     * Destroy the session
     *
     * @param int $id
     * @return bool
     */
    public function destroy($id)
    {
        unset(self::$_data[self::$_name][$id]);
    }

    /**
     * Garbage Collector
     *
     * @param int $max
     * @return bool
     */
    public function gc($max)
    {
        return true;
    }

}
