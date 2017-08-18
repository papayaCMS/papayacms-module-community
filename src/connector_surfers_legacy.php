<?php
/**
* Community user legacy connector class
*
* @copyright 2002-2017 by dimensional GmbH - All rights reserved.
* @link http://www.papaya-cms.com/
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @todo Move internal method logics (i.e. getProfileDataClassTitles) to surfers_admin
* @package Papaya-Modules
* @subpackage _Base-Community
* @version $Id: connector_surfers_legacy.php 39697 2014-03-27 16:54:51Z weinert $
*/

/**
* Basic class surfer administration
*/
require_once(dirname(__FILE__).'/base_surfers.php');

/**
* @see surfer_admin
*
* @package Papaya-Modules
* @subpackage _Base-Community
*
* @method void loadGroups()
* @method bool existHandle($handle, $includeBlocked = FALSE)
* @method bool checkHandle($handle)
* @method bool existEmail($email, $includeBlocked = FALSE)
* @method bool checkPasswordForPolicy($password, $handle = '')
* @method array getDataFieldNames($class = 0)
* @method array|string|NULL getDynamicData($surferIds, $fields)
* @method int setDynamicData($surferId, $fields, $value = NULL)
* @method array|bool checkDynamicData($fields, $value = NULL)
* @method array getDynamicEditFields($fields, $prefix = '', $lng = 0)
* @method void databaseDebugNextQuery() databaseDebugNextQuery(integer $count = 1)
* @method integer databaseDeleteRecord() databaseDeleteRecord(string $table, $filter, mixed $value = NULL)
* @method void databaseEnableAbsoluteCount() databaseEnableAbsoluteCount()
* @method string databaseEscapeString() databaseEscapeString(mixed $value)
* @method string databaseGetSqlSource() databaseGetSqlSource(string $function, array $params = NULL)
* @method string databaseGetSqlCondition() databaseGetSqlCondition(array $filter, $value = NULL)
* @method integer|NULL databaseInsertRecord() databaseInsertRecord(string $table, $idField, array $values = NULL)
* @method integer|boolean databaseInsertRecords() databaseInsertRecords(string $table, array $values)
* @method boolean|integer|PapayaDatabaseResult databaseQuery() databaseQuery(string $sql, integer $max = NULL, integer $offset = NULL, boolean $readOnly = TRUE)
* @method boolean|integer|PapayaDatabaseResult databaseQueryFmt() databaseQueryFmt(string $sql, array $values, integer $max = NULL, integer $offset = NULL, boolean $readOnly = TRUE)
* @method boolean|integer|PapayaDatabaseResult databaseQueryFmtWrite() databaseQueryFmtWrite(string $sql, array $values)
* @method boolean|integer|PapayaDatabaseResult databaseQueryWrite() databaseQueryWrite(string $sql)
* @method integer|boolean databaseUpdateRecord() databaseUpdateRecord(string $table, array $values, $filter, mixed $value = NULL)
* @method array databaseQueryTableNames() databaseQueryTableNames()
* @method array databaseQueryTableStructure() databaseQueryTableStructure(string $tableName)
* @method string databaseGetTableName() databaseGetTableName(string $tablename, $usePrefix = TRUE)
*/
class connector_surfers_legacy extends base_connector {
  /**
  * surfer_admin object
  * @var surfer_admin
  */
  private $_surferAdmin = NULL;

  public $groupList = array();

  /**
  * Get/set the surfer_admin object
  *
  * @param surfer_admin $surferAdmin
  * @return surfer_admin
  */
  public function surferAdmin($surferAdmin = NULL) {
    if ($surferAdmin !== NULL) {
      $this->_surferAdmin = $surferAdmin;
    } elseif ($this->_surferAdmin === NULL) {
      $this->_surferAdmin = surfer_admin::getInstance();
    }
    return $this->_surferAdmin;
  }

  /**
  * Magic getter to read public properties of the surfer_admin object
  *
  * @param string $name
  * @return mixed
  */
  public function __get($name) {
    $result = NULL;
    if (isset($this->surferAdmin()->$name)) {
      $result = $this->surferAdmin()->$name;
    }
    return $result;
  }

  /**
  * Magic setter to modify public properties of the surfer_admin object
  *
  * @param string $name
  * @param mixed $value
  */
  public function __set($name, $value) {
    $this->surferAdmin()->$name = $value;
  }

  /**
  * Magic caller to call methods of the surfer_admin object
  *
  * @param string $name
  * @param array $arguments
  * @return mixed
  */
  public function __call($name, $arguments) {
    return call_user_func_array(array($this->surferAdmin(), $name), $arguments);
  }
}