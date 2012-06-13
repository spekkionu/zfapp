<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Addusers extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->createTable('users', array(
             'id' => 
             array(
              'type' => 'integer',
              'primary' => true,
              'unsigned' => true,
              'autoincrement' => true,
              'comment' => 'primary key',
              'length' => 4,
             ),
             'username' => 
             array(
              'type' => 'string',
              'notblank' => true,
              'notnull' => true,
              'unique' => true,
              'comment' => 'The username the user logs in with',
              'length' => 25,
             ),
             'password' => 
             array(
              'type' => 'string',
              'fixed' => 1,
              'notblank' => true,
              'notnull' => true,
              'comment' => 'The password the user logs in with',
              'length' => 128,
             ),
             'firstname' => 
             array(
              'type' => 'string',
              'notblank' => true,
              'notnull' => true,
              'comment' => 'The first name of the user',
              'length' => 32,
             ),
             'lastname' => 
             array(
              'type' => 'string',
              'notblank' => true,
              'notnull' => true,
              'comment' => 'The last name of the user',
              'length' => 64,
             ),
             'email' => 
             array(
              'type' => 'string',
              'notnull' => true,
              'notblank' => true,
              'unique' => true,
              'comment' => 'The email address of the user',
              'length' => 127,
             ),
             'active' => 
             array(
              'type' => 'boolean',
              'default' => 0,
              'notnull' => true,
              'unsigned' => true,
              'comment' => 'Only active users may log in.',
              'length' => 25,
             ),
             'date_created' => 
             array(
              'notnull' => true,
              'type' => 'timestamp',
              'length' => 25,
             ),
             'last_login' => 
             array(
              'type' => 'timestamp',
              'comment' => 'The date the user last logged in.',
              'length' => 25,
             ),
             'accesslevel' => 
             array(
              'type' => 'string',
              'notblank' => true,
              'notnull' => true,
              'default' => 'user',
              'comment' => 'The access level of the user',
              'length' => 50,
             ),
             'token' => 
             array(
              'type' => 'string',
              'fixed' => 1,
              'comment' => 'Token used for password reset',
              'length' => 128,
             ),
             'password_key' => 
             array(
              'type' => 'string',
              'fixed' => 1,
              'comment' => 'Hash used for password reset',
              'length' => 128,
             ),
             'token_date' => 
             array(
              'type' => 'date',
              'comment' => 'Date the password reset token expires.',
              'length' => 25,
             ),
             ), array(
             'type' => 'INNODB',
             'indexes' => 
             array(
              'login' => 
              array(
              'fields' => 
              array(
               0 => 'username',
               1 => 'password',
               2 => 'active',
              ),
              'type' => 'unique',
              ),
             ),
             'primary' => 
             array(
              0 => 'id',
             ),
             'collate' => 'utf8_unicode_ci',
             'charset' => 'utf8',
             ));
    }

    public function down()
    {
        $this->dropTable('users');
    }
}