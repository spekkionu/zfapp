<?php

/**
 * User Model
 *
 * @package App
 * @subpackage Model
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Model_User extends App_Model
{

    /**
     * @var string $_name The name of the table
     */
    protected $_name = 'users';

    /**
     * @var string $_primary The name of the primary key of the table
     */
    protected $_primary = 'id';

    const ACCESS_ADMIN = 'admin';
    const ACCESS_SUPERADMIN = 'superadmin';

    /**
     * Data can be sorted by these columns
     * @var array $sortable
     */
    public static $sortable = array(
      'id', 'username', 'name', 'email', 'active',
      'date_created', 'last_updated', 'last_login'
    );

    /**
     * The default search filters
     * @var array $search
     */
    public static $search = array(
      'username' => null,
      'name' => null,
      'email' => null,
      'active' => null
    );

    /**
     * Returns the profile for a given user
     *
     * @param integer $id
     * @return array
     */
    public function getProfile($id)
    {
        $select = $this->select();
        $select->from($this, array('id', 'username', 'email', 'firstname', 'lastname', 'active', 'accesslevel', 'date_created', 'last_login'));
        $select->where('id = ?', $id, Zend_Db::PARAM_INT);

        return $this->getAdapter()->fetchRow($select);
    }

    /**
     * Updates user profile
     * @param int $userid
     * @param array $data
     * @return int Number of affected rows
     */
    public function updateProfile($userid, array $data)
    {
        $data = $this->_filterDataArray($data, array('id', 'password', 'accesslevel', 'signup_date', 'last_login', 'token', 'password_key', 'token_date'));

        return $this->update($data, 'id = ' . $this->getAdapter()->quote($userid, Zend_Db::PARAM_INT));
    }

    /**
     * Logs a user in
     *
     * @param string $username
     * @param string $password
     * @return object
     */
    public function login($username, $password)
    {
        $select = $this->select();
        $select->from($this, array(
          'id',
          'username',
          'password',
          'firstname',
          'lastname',
          'email',
          'accesslevel'
        ));
        $select->where("`username` LIKE ?", $username);
        $select->where('active = 1');
        $data = $this->getAdapter()->fetchRow($select, array(), Zend_Db::FETCH_OBJ);
        if (!$data) {
            throw new Validate_Exception('User account not found with this username and password.');
        }
        if (!self::checkPassword($password, $data->password)) {
            throw new Validate_Exception('User account not found with this username and password.');
        }

        // Update Last Login Date and clear password reset tokens
        $this->update(array(
          'last_login' => date('Y-m-d H:i:s'),
          'token' => null,
          'token_date' => null
          ), 'id = ' . $this->quote($data->id, Zend_Db::PARAM_INT));
        // Save the login data to the session.
        Zend_Auth::getInstance()->getStorage()->write($data);

        return Zend_Auth::getInstance()->getIdentity();
    }

    /**
     * Changes user's password
     * @param int $id
     * @param Form_ChangePassword $form
     * @return int Number of affected rows
     * @throws Validate_Exception
     */
    public function changePassword($id, Form_ChangePassword $form)
    {
        $data = $form->getValues();
        // Make sure old password is correct
        $select = $this->select();
        $select->from($this, array('id', 'password'));
        $select->where('id = ?', $id, Zend_Db::PARAM_INT);
        $found = $select->getAdapter()->fetchRow($select);
        if ($found['id'] != $id || !self::checkPassword($data['old_password'], $found['password'])) {
            $form->getElement('old_password')->addError("Old password is incorrect.");
            throw new Validate_Exception();
        }
        // Password matches, do change
        return $this->update(array(
            'password' => self::encrypt($data['password']),
            'token' => null,
            'token_date' => null
            ), 'id = ' . $this->quote($id, Zend_Db::PARAM_INT));
    }

    /**
     * Password reset for admin with this email
     * Returns array with id, username, firstname, lastname, email, token, and pin
     * @param string $email
     * @param string $access_level Optionally limit to only this access level
     * @return array
     */
    public function resetPassword($email, $access_level = null)
    {
        $select = $this->select();
        $select->from($this, array('id', 'username', 'firstname', 'lastname', 'email'));
        $select->where('email LIKE ?', $email);
        $select->where("active = 1");
        if ($access_level) {
            $select->where('accesslevel = ?', $access_level);
        }
        $user = $this->getAdapter()->fetchRow($select);
        if (!$user) {
            throw new Validate_Exception("No account with matching email address.");
        }
        // Generate token
        $user['token'] = md5(uniqid(time() . $user['email'], true));
        // Save token
        $this->update(array(
          'token' => $user['token'],
          'token_date' => date('Y-m-d H:i:s')
          ), 'id = ' . $this->quote($user['id']));

        return $user;
    }

    /**
     * Confirms the password reset with token and PIN
     * Changes the password
     * Logs the user in
     * @param string $token
     * @param Form_ChangePassword $form
     * @return array
     */
    public function confirmPasswordReset($token, Form_ChangePassword $form)
    {
        $values = $form->getValues();
        // Make sure token and pin are correct
        $date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')));
        $select = $this->select();
        $select->from($this, array('id', 'username', 'token_date'));
        $select->where("active = 1");
        $select->where('token LIKE ?', $token);
        $user = $this->getAdapter()->fetchRow($select);
        if (!$user) {
            throw new Validate_Exception("Could not find account.");
        }
        if ($user['token_date'] < $date) {
            throw new Validate_Exception("Password reset has expired.");
        }
        // Password matches, do change
        $this->update(array(
          'password' => self::encrypt($values['password']),
          'token_date' => null
          ), 'id = ' . $this->quote($user['id']));

        return $this->login($user['username'], $values['password']);
    }

    /**
     * Encrypts value
     * @param  string $value
     * @return string
     */
    public static function encrypt($value)
    {
        return password_hash($value, PASSWORD_BCRYPT, array('cost' => 12));
    }

    /**
     * Check password against hash
     * @param string $password
     * @param string $hash
     * @return boolean
     */
    public static function checkPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }
}
