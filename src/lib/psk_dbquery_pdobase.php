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
 * PSK_DBQuery_PDOBase class.
 *
 * Query interface for connections based on PDO.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBQuery_PDOBase class documentation link.
 */

abstract class PSK_DBQuery_PDOBase extends PSK_DBQuery_Base
{
	/**
	 * Result set of the query.
	 *
	 * @var PDOStatement
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
		unset($this->__resultSet);
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
		return $this->__resultSet->fetch(PDO::FETCH_NUM);
	}

	/**
	 * Returns a result row as an associative array. Each call to this function
	 * returns the next row in the result set or NULL if there are no more rows.
	 *
	 * @return array
	 */
	function FetchAssoc()
	{
		return $this->__resultSet->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Returns a result row as an object. Each call to this function returns the
	 * next row in the result set or NULL if there are no more rows.
	 *
	 * @return stdClass
	 */
	function FetchObject()
	{
		return $this->__resultSet->fetch(PDO::FETCH_OBJ);
	}

	/**
	 * Returns the number of rows in the result set.
	 *
	 * @return integer
	 */
	function RowCount()
	{
		return $this->__resultSet->rowCount();
	}

	/**
	 * Returns the number of columns in the result set.
	 *
	 * @return integer
	 */
	function FieldCount()
	{
		return $this->__resultSet->columnCount();
	}
}

?>
