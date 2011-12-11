<?php
/**
 * Model Base Class
 *
 * @package    App
 * @subpackage Model
 * @author     spekkionu
 * @license    New BSD http://www.opensource.org/licenses/bsd-license.php
 */
abstract class App_Model extends Zend_Db_Table_Abstract {


  /**
   * Loops through data and filters columns.
   * Removed any non-existing or not allowed fields.
   * Sets empty values to null
   * @param array $data
   * @param array $notallowed
   * @return array
   */
  protected function _filterDataArray(array $data, $notallowed = array()){
  	foreach($data as $key=>$value){
  		if(!in_array($key, $this->_getCols()) || in_array($key, $notallowed)){
  			unset($data[$key]);
  		}else{
  			$data[$key] = $this->_filterValue($value);
  		}
      if(method_exists($this, '_filter_'.$key)){
        $method = '_filter_'.$key;
        $data[$key] = $this->$method($data[$key]);
      }
  	}
    
  	return $data;
  }

  /**
   * Filters a single value
   * @param string $value
   * @return string
   */
  protected function _filterValue($value){
    $value = trim($value);
    if($value == '') $value = null;
    return $value;
  }

  /**
   * Loops through data and validates columns.
   * @param array $data
   * @param array $notallowed
   * @return array
   */
  protected function _validateDataArray(array $data){
    $valid = true;
    $result = array();
  	foreach($data as $key=>$value){
  		if(method_exists($this, '_validate_'.$key)){
        $method = '_validate_'.$key;
        $result[$key] = $this->$method($value);
        if($result[$key] !== true) $valid = false;
      }
  	}
  	return ($valid) ? $valid : $result;
  }

  /**
   * Safely quotes a value for an SQL statement.
   *
   * If an array is passed as the value, the array values are quoted
   * and then returned as a comma-separated string.
   *
   * @param mixed $value The value to quote.
   * @param mixed $type  OPTIONAL the SQL datatype name, or constant, or null.
   * @return mixed An SQL-safe quoted value (or string of separated values).
   */
  protected function quote($value, $type = null){
    return $this->getAdapter()->quote($value, $type);
  }
}