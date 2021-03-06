<?php

/**
 * User
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    Simplecart
 * @subpackage Model
 * @author     spekkionu <spekkionu@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class User extends Base_User
{

    public function setPassword($password)
    {
        return $this->_set('password', Model_User::encrypt($password));
    }

    public function preInsert($event)
    {
        $invoker = $event->getInvoker();

        $invoker->date_created = date('Y-m-d H:i:s');
    }

}