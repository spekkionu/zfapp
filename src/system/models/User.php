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
    // Encrypt the password
    $password = self::encryptPassword($password);

    // Get the Zend_Auth instance
    $auth = Zend_Auth::getInstance();
    $auth->clearIdentity();
    // Setup table and credentials columns
    $authAdapter = new Zend_Auth_Adapter_DbTable($this->getAdapter());
    $authAdapter->setTableName($this->_name)
      ->setIdentityColumn('username')
      ->setCredentialColumn('password');
    $authAdapter->setIdentity($username);
    $authAdapter->setCredential($password);
    $select = $authAdapter->getDbSelect();
    $select->where('active = 1');
    // Authenticate the user
    $result = $auth->authenticate($authAdapter);
    if ($result->isValid()) {
      // The account was found pull the db row.
      $data = $authAdapter->getResultRowObject(array(
        'id',
        'username',
        'firstname',
        'lastname',
        'email',
        'accesslevel'
        ));
      // Update Last Login Date and clear password reset tokens
      $this->update(array(
        'last_login' => date('Y-m-d H:i:s'),
        'token' => null,
        'password_key' => null,
        'token_date' => null
        ), 'id = ' . $this->quote($data->id, Zend_Db::PARAM_INT));
      // Save the login data to the session.
      $auth->getStorage()->write($data);

      return $auth->getIdentity();
    } else {
      throw new Validate_Exception('User account not found with this username and password.');
    }
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
    $encrypted_password = self::encryptPassword($data['old_password']);
    // Make sure old password is correct
    $select = $this->select();
    $select->from($this, array('id'));
    $select->where('id = ?', $id, Zend_Db::PARAM_INT);
    $select->where('password = ?', $encrypted_password);
    $found = $select->getAdapter()->fetchOne($select);
    if ($found != $id) {
      $form->getElement('old_password')->addError("Old password is incorrect.");
      throw new Validate_Exception();
    }
    // Password matches, do change
    return $this->update(array(
      'password' => self::encryptPassword($data['password']),
      'token' => null,
      'password_key' => null,
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
    $user['pin'] = rand(1000, 9999);
    // Save token
    $this->update(array(
      'token' => self::encryptToken($user['token']),
      'password_key' => self::encryptPin($user['pin']),
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
    $token = self::encryptToken($token);
    $pin = self::encryptPin($values['pin']);
    $date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')));
    $select = $this->select();
    $select->from($this, array('id', 'username', 'token_date'));
    $select->where("active = 1");
    $select->where('token LIKE ?', $token);
    $select->where('password_key LIKE ?', $pin);
    $user = $this->getAdapter()->fetchRow($select);
    if (!$user) {
      $form->getElement('pin')->addError("Could not find match for given PIN number.");
      throw new Validate_Exception();
    }
    if ($user['token_date'] < $date) {
      $form->getElement('pin')->addError("This PIN number has expired.");
      throw new Validate_Exception();
    }
    // Password matches, do change
    $this->update(array(
      'password' => self::encryptPassword($values['password'])
      ), 'id = ' . $this->quote($user['id']));

    return $this->login($user['username'], $values['password']);
  }

  /**
   * Encrypts password
   * @param  string $password
   * @return string
   */
  public static function encryptPassword($password)
  {
    $crypt_key = 'vghhgjVCHRTjhgfhjmHFDNHGJTDhtykMGFBRThgfdnb';

    return self::encrypt($password, $crypt_key);
  }

  /**
   * Encrypts token
   * @param  string $token
   * @return string
   */
  public static function encryptToken($token)
  {
    $crypt_key = 'gfjhGFHBgfjhymgfdBHRTIJMfdhgJKNBVMtdyhgkmFDgrte';

    return self::encrypt($token, $crypt_key);
  }

  /**
   * Encrypts PIN
   * @param  string $pin
   * @return string
   */
  public static function encryptPin($pin)
  {
    $crypt_key = 'hkthyJFDGrtjhgmRTHYRTYkhyjgfdhRTEYgjthgyIKrthd';

    return self::encrypt($pin, $crypt_key);
  }

  /**
   * Encrypts value
   * @param  string $value
   * @param  string $crypt_key
   * @return string
   */
  protected static function encrypt($value, $crypt_key)
  {
    return hash('sha512', $value . $crypt_key);
  }

}
