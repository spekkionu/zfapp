
<?php
/**
 * CMS Model
 *
 * @package App
 * @subpackage Model
 * @author spekkionu
 * @license New BSD http://www.opensource.org/licenses/bsd-license.php
 */
class Model_Content extends App_Model {

  /**
   * @var string $_name The name of the table
   */
  protected $_name   = 'content';

  /**
   * @var string $_primary The name of the primary key of the table
   */
  protected $_primary = 'id';

  /**
   * Data can be sorted by these columns
   * @var array $sortable
   */
  public static $sortable = array(
    'id', 'url', 'title', 'active',
    'date_created', 'last_updated'
  );

  /**
   * The default search filters
   * @var array $search
   */
  public static $search = array(
    'url' => null,
    'title' => null,
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
  public function getPageList(array $search = array(), $page = 1, $limit = 25, $sort = 'url', $dir = 'asc'){
    // Merge with default search parameters
    $search = array_merge(self::$search, array_intersect_key($search, self::$search));
    $select = $this->select();
    $select->from($this, array(
      'id', 'url', 'title', 'active', 'can_delete',
      'date_created', 'last_updated'
    ));
    if($search['url']){
      $select->where("`url` LIKE ?", $search['url']);
    }
    if($search['title']){
      $select->where("`title` LIKE ?", "%".$search['title']."%");
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
    if($sort == 'url'){
      $select->order("{$sort} {$dir}");
    }else{
      $select->order("{$sort} {$dir}");
      $select->order("url {$dir}");
    }
    return App_Paginator::getPagination($select, $page, $limit);
  }

  /**
   * Returns the content of a page by id
   *
   * @param integer $id
   * @return array
   */
  public function getPage($id) {
    $select = $this->select();
    $select->from($this);
    $select->where('id = ?', $id, Zend_Db::PARAM_INT);
    return $this->getAdapter()->fetchRow($select);
  }

  /**
   * Returns the content of a page by id
   *
   * @param string $url
   * @return array
   */
  public function getPageByUrl($url) {
    $select = $this->select();
    $select->from($this, array('id', 'url', 'title', 'content'));
    $select->where('url LIKE ?', $url);
    $select->where("`active` = 1");
    return $this->getAdapter()->fetchRow($select);
  }

  /**
   * Adds new page
   * @param array $data
   * @return int RowID of page
   */
  public function addPage(array $data){
    $data = $this->_filterDataArray($data, array('id', 'date_created', 'last_updated', 'can_delete'));
    $data['date_created'] = new Zend_Db_Expr("NOW()");
    $data['can_delete'] = 1;
    $this->insert($data);
    return $this->getAdapter()->lastInsertId($this->_name, $this->_primary);
  }

  /**
   * Updates page content
   * @param int $id
   * @param array $data
   * @return int Number of affected rows
   */
  public function updatePage($id, array $data){
    $data = $this->_filterDataArray($data, array('id', 'date_created', 'last_updated', 'can_delete'));
    $data['last_updated'] = new Zend_Db_Expr("NOW()");
    return $this->update($data, 'id = ' . $this->getAdapter()->quote($id, Zend_Db::PARAM_INT));
  }

  /**
   * Deletes a page
   * @param int $id
   * @return int Number of affected rows
   */
  public function deletePage($id){
    return $this->delete('id = ' . $this->getAdapter()->quote($id, Zend_Db::PARAM_INT));
  }

  /**
   * Filters url
   * Trims off extra slashes and dashes
   * @param string $url
   * @return string
   */
  public static function filterUrl($url){
    // Replace multiple slashes with one
    $url = preg_replace('/\\/{2,}/i', '/', trim($url));
    // Replace multiple dashes with one
    $url = preg_replace('/\\-{2,}/i', '-', $url);
    // Trim slashes and dashes from beginning of string
    $url = preg_replace('/^[-\\/]+/i', '', $url);
    // Trim slashes and dashes from end of string
    $url = preg_replace('/[-\\/]+$/i', '', $url);
    return $url;
  }

  /**
   * Filters content.
   *
   * Makes sure it is valid html.
   * Removes non-allowed tags and attributes.
   *
   * @uses HTMLPurifier
   * @param string $content
   * @return string Filtered content
   */
  public static function filterContent($content){
    if(empty($content)){
      return $content;
    }
    require_once("HTMLPurifier/HTMLPurifier.safe-includes.php");
    $config = HTMLPurifier_Config::createDefault();

    $config->set('HTML.DefinitionID', 'page_filter');
    $config->set('HTML.DefinitionRev', 1);
    /*
    //$config->set('Cache.DefinitionImpl', null); // TODO: remove this later!
    if ($def = $config->maybeGetRawHTMLDefinition()) {
      // Definition Cache is not Valid. Build it.
      // Allow target attribute on links
      $def->addAttribute('a', 'target', new HTMLPurifier_AttrDef_Enum(
        array('_blank','_self','_target','_top')
      ));
    }
     *
     */
    $config->set('Attr.AllowedFrameTargets', array('_blank','_self','_target','_top'));
    $config->set('Attr.ForbiddenClasses', array());
    $config->set('AutoFormat.RemoveSpansWithoutAttributes', true);
    //$config->set('HTML.Allowed', "");
    $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
    $config->set('HTML.MaxImgLength', 800);
    $config->set('CSS.MaxImgLength', 800);
    $config->set('HTML.TidyLevel', 'light');

    $config->set('Output.Newline', "\n");
    $config->set('Output.TidyFormat', true);
    $config->set('URI.AllowedSchemes', array (
      'http' => true, 'https' => true, 'mailto' => true
    ));
    $config->set('Cache.SerializerPath', Cache::getHtmlPurifierCache('page_filter'));
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($content);
  }

}