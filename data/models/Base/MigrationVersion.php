<?php

/**
 * Base_MigrationVersion
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $version
 * 
 * @package    App
 * @subpackage Model
 * @author     spekkionu <spekkionu@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Base_MigrationVersion extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('migration_version');
        $this->hasColumn('version', 'integer', 4, array(
             'type' => 'integer',
             'unsigned' => false,
             'primary' => true,
             'length' => '4',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}