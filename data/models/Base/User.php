<?php

/**
 * Base_User
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property boolean $active
 * @property timestamp $date_created
 * @property timestamp $last_login
 * @property string $accesslevel
 * @property string $token
 * @property string $password_key
 * @property date $token_date
 *
 * @package    Simplecart
 * @subpackage Model
 * @author     spekkionu <spekkionu@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Base_User extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('users');
        $this->hasColumn('id', 'integer', 4, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             'length' => '4',
             ));
        $this->hasColumn('username', 'string', 25, array(
             'type' => 'string',
             'notblank' => true,
             'notnull' => true,
             'unique' => true,
             'length' => '25',
             ));
        $this->hasColumn('password', 'string', 128, array(
             'type' => 'string',
             'fixed' => 1,
             'notblank' => true,
             'notnull' => true,
             'length' => '128',
             ));
        $this->hasColumn('firstname', 'string', 32, array(
             'type' => 'string',
             'notblank' => true,
             'notnull' => true,
             'length' => '32',
             ));
        $this->hasColumn('lastname', 'string', 64, array(
             'type' => 'string',
             'notblank' => true,
             'notnull' => true,
             'length' => '64',
             ));
        $this->hasColumn('email', 'string', 127, array(
             'type' => 'string',
             'notnull' => true,
             'notblank' => true,
             'unique' => true,
             'length' => '127',
             ));
        $this->hasColumn('active', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             'unsigned' => true,
             ));
        $this->hasColumn('date_created', 'timestamp', null, array(
             'type' => 'timestamp',
             'notnull' => true,
             ));
        $this->hasColumn('last_login', 'timestamp', null, array(
             'type' => 'timestamp',
             ));
        $this->hasColumn('accesslevel', 'string', 50, array(
             'type' => 'string',
             'notblank' => true,
             'notnull' => true,
             'default' => 'user',
             'length' => '50',
             ));
        $this->hasColumn('token', 'string', 128, array(
             'type' => 'string',
             'fixed' => 1,
             'length' => '128',
             ));
        $this->hasColumn('password_key', 'string', 128, array(
             'type' => 'string',
             'fixed' => 1,
             'length' => '128',
             ));
        $this->hasColumn('token_date', 'date', null, array(
             'type' => 'date',
             ));


        $this->index('login', array(
             'fields' =>
             array(
              0 => 'username',
              1 => 'password',
              2 => 'active',
             ),
             'type' => 'unique',
             ));
        $this->option('collate', 'utf8_unicode_ci');
        $this->option('charset', 'utf8');
        $this->option('type', 'INNODB');
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable(array(
             'created' =>
             array(
              'name' => 'date_created',
              'type' => 'timestamp',
             ),
             'updated' =>
             array(
              'disabled' => true,
             ),
             ));
        $this->actAs($timestampable0);
    }
}