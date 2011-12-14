
<?php

/**
 * Administrator Model
 *
 * @package App
 * @subpackage Model
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Model_Admin extends Model_User {

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
   * Returns paginated, filtered list of administrators
   *
   * @param array $search Array of search filters.
   * @param int $page Page of data
   * @param int $limit Maximum Number of results to return
   * @param string $sort Column to sort by can be any in self::$sortable
   * @param string $dir Sort direction. Can be asc or desc
   * @return Zend_Paginator
   */
  public function getAdministratorList(array $search = array(), $page = 1, $limit = 25, $sort = 'id', $dir = 'asc'){
    // Merge with default search parameters
    $search = array_merge(self::$search, array_intersect_key($search, self::$search));
    $select = $this->select();
    $select->from($this, array(
      'id', 'username', 'firstname', 'lastname', 'email', 'active',
      'date_created', 'last_login'
    ));
    if($search['username']){
      $select->where("`username` LIKE ?", "%".$search['username']."%");
    }
    if($search['name']){
      $select->where("CONCAT_WS(' ',`firstname`,`lastname`) LIKE ?", "%".$search['name']."%");
    }
    if($search['email']){
      $select->where("`email` LIKE ?", $search['email']);
    }
    if(!is_null($search['active']) && $search['active'] !== ''){
      $select->where("`active` = ?", $search['active'], Zend_Db::PARAM_INT);
    }
    $sort = mb_strtolower($sort);
    if(!in_array($sort, self::$sortable)){
      $sort = 'id';
    }
    $dir = mb_strtoupper($dir);
    if(!in_array($dir, array('ASC','DESC'))){
      $dir = 'ASC';
    }
    if($sort == 'id'){
      $select->order("{$sort} {$dir}");
    }elseif($sort == 'name'){
      $select->order("firstname {$dir}");
      $select->order("lastname {$dir}");
      $select->order("id {$dir}");
    }else{
      $select->order("{$sort} {$dir}");
      $select->order("id {$dir}");
    }
    return App_Paginator::getPagination($select, $page, $limit);
  }

  /**
   * Adds a new administrator
   * @param array $values Array of user data
   * @return int User ID of new admin
   */
  public function addAdministrator(array $values){
    $data = $this->_filterDataArray($values, array('id','date_created','last_login','accesslevel','token','password_key','token_date'));
    $data['date_created'] = new Zend_Db_Expr("NOW()");
    $data['accesslevel'] = self::ACCESS_ADMIN;
    $data['password'] = self::encryptPassword($values['password']);
    $data['active'] = ($data['active']) ? 1 : 0;
    $this->insert($data);
    return $this->getAdapter()->lastInsertId($this->_name, $this->_primary);
  }

  /**
   * Updates an administrator
   * @param int $id The id of the administrator to update
   * @param array $values Array of admin data
   * @return int Number of affected rows
   */
  public function updateAdministrator($id, array $values){
    $data = $this->_filterDataArray($values, array('id','date_created','last_login','accesslevel','password','token','password_key','token_date'));
    $data['active'] = ($data['active']) ? 1 : 0;
    return $this->update($data, $this->getAdapter()->quoteInto("`id` = ?",$id, Zend_Db::PARAM_INT));
  }

  /**
   * Deletes an administrator
   * @param int $id The id of the admin to delete
   * @return int The number of affected rows
   */
  public function deleteAdministrator($id){
    return $this->delete($this->getAdapter()->quoteInto("`id` = ?",$id, Zend_Db::PARAM_INT));
  }

}
