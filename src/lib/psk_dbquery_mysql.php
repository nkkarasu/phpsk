<?php

/**
 * PSK
 *
 * An open source PHP web application development framework.
 *
 * @package       PSK (PHP Sınıf Kütüphanesi)
 * @author        Namık Kemal Karasu
 * @copyright     Copyright (C) Namık Kemal Karasu
 * @license       GPLv3
 * @since         Version 0.
 * @link          http://nkkarasu.net/psk/
 * @link          http://code.google.com/p/phpsk/
 */

require_once dirname(__FILE__) . '/psk_dbquery_base.php';

/**
 * PSK_DBQuery_MySQL class.
 *
 * Query interface for connections based on MySQL extensions.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBQuery_MySQL class documentation link.
 */

class PSK_DBQuery_MySQL extends PSK_DBQuery_Base
{
	/**
	 * Result set of the query.
	 *
	 * @var resource
	 */
	protected $__resultSet = null;

	/**
	 * Destructor of the class.
	 */
	function  __destruct()
	{
		$this->Free();
	}

	/**
	 * Assigns the result set of the query.
	 *
	 * @param resource $par_ResultSet
	 */
	function setResultSet($par_ResultSet)
	{
		$this->__resultSet = $par_ResultSet;
	}

	/**
	 * Clears the result set from memory.
	 */
	function Free()
	{
		if (is_resource($this->__resultSet)) {
			mysql_free_result($this->__resultSet);
		}
		unset($this->__fieldMeta);
	}

	/**
	 * Returns a result row as an enumerated array. Each call to this function
	 * returns the next row in the result set or NULL if there are no more rows.
	 *
	 * @return array
	 */
	function FetchNum()
	{
		return @mysql_fetch_row($this->__resultSet);
	}

	/**
	 * Returns a result row as an associative array. Each call to this function
	 * returns the next row in the result set or NULL if there are no more rows.
	 *
	 * @return array
	 */
	function FetchAssoc()
	{
		return @mysql_fetch_assoc($this->__resultSet);
	}

	/**
	 * Returns a result row as an object. Each call to this function returns the
	 * next row in the result set or NULL if there are no more rows.
	 *
	 * @return stdClass
	 */
	function FetchObject()
	{
		return @mysql_fetch_object($this->__resultSet);
	}

	/**
	 * Returns the number of rows in the result set.
	 *
	 * @return integer
	 */
	function RowCount()
	{
		return @mysql_num_rows($this->__resultSet);
	}

	/**
	 * Returns the number of columns in the result set.
	 *
	 * @return integer
	 */
	function FieldCount()
	{
		return @mysql_num_fields($this->__resultSet);
	}

	/**
	 * Returns the meta information about specified field in the result set as
	 * an associative array. This array contains below keys.
	 * Name, Table, Type, RealType, AllowNull, Key, Default and Extra.
	 *
	 * @param integer $par_FieldOffset
	 *
	 * @return array
	 */
	function FieldProperties($par_FieldOffset)
	{
		if (array_key_exists($par_FieldOffset, $this->__fieldMeta)) {
			return $this->__fieldMeta[$par_FieldOffset];
		}

		$meta = array();

		$meta['Name'] = @mysql_field_name($this->__resultSet, $par_FieldOffset);
		$meta['Table'] = @mysql_field_table($this->__resultSet, $par_FieldOffset);

		$sql =
			"SELECT DATA_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT, EXTRA
			 FROM information_schema.COLUMNS 
			 WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s' AND COLUMN_NAME = '%s'";

		$query = $this->__owner->Query(sprintf($sql,
			$this->__owner->getDatabase(), $meta['Table'], $meta['Name']));
		$info = $query->FetchNum();
		$query->Free();

		$meta['Type'] = $this->__owner->MapTypes($info[0]);
		$meta['RealType'] = $info[0];
		$meta['AllowNull'] = $info[1] === 'YES' ? true : false;
		$meta['Key'] = $this->__owner->MapKeys($info[2]);
		$meta['Default'] = $info[3];
		$meta['AutoIncrement'] = strpos($info[4], 'auto_increment') === false ? false : true;
		$meta['Extra'] = $info[4];

		return $meta;
	}
}

?>
