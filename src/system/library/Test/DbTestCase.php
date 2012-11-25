<?php

/**
 * Base Test Case for database tests
 */
abstract class Test_DbTestCase extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        parent::setUp();
        Doctrine_Manager::connection("sqlite:///" . TEST_DATA . '/cache/testdb.sqlite');
        Doctrine_Core::createDatabases();
        Doctrine_Core::createTablesFromModels(MODELS_PATH);
        Doctrine_Core::loadData(TEST_DATA . '/fixtures');
        $db = new Zend_Db_Adapter_Pdo_Sqlite(array(
            'dbname' => TEST_DATA . '/cache/testdb.sqlite'
          ));
        Zend_Db_Table::setDefaultAdapter($db);
        Cache::clearCacheManager();
    }

    protected function tearDown()
    {
        parent::tearDown();
        Doctrine_Manager::getInstance()->closeConnection(Doctrine_Manager::connection());
        $db = Zend_Db_Table::getDefaultAdapter();
        if($db){
          $db->closeConnection();
        }
        @unlink(TEST_DATA . '/cache/testdb.sqlite');
    }

}