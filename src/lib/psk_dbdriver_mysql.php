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

require_once dirname(__FILE__) . '/psk_dbquery_mysql.php';

/**
 * PSK_DB_MySQL class.
 *
 * Database driver for MySQL server. This driver uses MySQL extensions.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DB_MySQL class documentation link.
 */

class PSK_DBDriver_MySQL extends PSK_DBDriver_Base
{
	/**
	 * Throws an exception about the failed query with extension error message
	 * and error code.
	 *
	 * @param string $par_Query
	 */
	protected function __QueryError($par_Query = '')
	{
		if ($par_Query !== '') {
			throw new Exception(sprintf(PSK_STR_DB_CANTEXECQUERY,
				$par_Query, mysql_error()), mysql_errno());
		}
		switch (mysql_errno()) {
			case 1045:
				throw new Exception(sprintf(PSK_STR_DB_ACCESSDENIED,
					$this->__user, mysql_error()), mysql_errno());
				break;
			case 1046:
				throw new Exception(sprintf(PSK_STR_DB_NODATABASESELECTED,
					mysql_error()), mysql_errno());
				break;
			case 1049:
				throw new Exception(sprintf(PSK_STR_DB_NODATABASE,
					$this->__database, mysql_error()), mysql_errno());
				break;
			case 2002:
				throw new Exception(sprintf(PSK_STR_DB_NOSERVER,
					$this->__server, mysql_error()), mysql_errno());
				break;
			default :
				throw new Exception(mysql_error(), mysql_errno());
				break;
		}
	}

	/**
	 * Sets up a connection with database server.
	 */
	function Connect()
	{
		$cf = $this->__persistent ? 'mysql_pconnect' : 'mysql_connect';
		$this->__connection = @$cf($this->__server, $this->__user, $this->__password);
		if (!$this->__connection) {
			$this->__QueryError();
		}
		if (!@mysql_select_db($this->__database, $this->__connection)) {
			$this->__QueryError();
		}

		$this->__connected = true;

		$this->SimpleQuery("SET NAMES '" . $this->__charSet .
			"' COLLATE '" . $this->__collation . "'");
	}

	/**
	 * Ends a connection from database server.
	 */
	function DisConnect()
	{
		if ($this->__connected && is_resource($this->__connection)) {
			mysql_close($this->__connection);
			$this->__connected = false;
		}
	}

	/**
	 * Starts a transaction.
	 */
	function BeginTrans()
	{
		if ($this->__transCount === 0) {
			$this->SimpleQuery('SET AUTOCOMMIT=0');
		}
		$this->SimpleQuery('START TRANSACTION');
		$this->__transCount++;
	}

	/**
	 * Commits the last started transaction.
	 */
	function CommitTrans()
	{
		if ($this->__transCount > 0) {
			$this->SimpleQuery('COMMIT');
			$this->__transCount--;
		}
		if ($this->__transCount === 0) {
			$this->SimpleQuery('SET AUTOCOMMIT=1');
		}
	}

	/**
	 * Rolls back the last started transaction.
	 */
	function RollBackTrans()
	{
		if ($this->__transCount > 0) {
			$this->SimpleQuery('ROLLBACK');
			$this->__transCount--;
		}
		if ($this->__transCount === 0) {
			$this->SimpleQuery('SET AUTOCOMMIT=1');
		}
	}

	/**
	 * Executes a query and if successed, returns an instance of
	 * PSK_DBQuery_MySQL. If not successed throws an exception.
	 *
	 * @param string $par_Query
	 *
	 * @return PSK_DBQuery_MySQL
	 */
	function Query($par_Query)
	{
		//PSK_Log::getInstance()->WriteDebug($par_Query);
		if ($this->__connected /*&& is_resource($this->__connection)*/) {
			$result = @mysql_query($par_Query, $this->__connection);
			if (!$result) {
				$this->__QueryError($par_Query);
			} else {
				$query = new PSK_DBQuery_MySQL($this);
				$query->setResultSet($result);
				return $query;
			}
		} else {
			throw new Exception(PSK_STR_DB_NOOPENCONNECTION);
		}
	}

	/**
	 * Executes a query and if successed returns the affected row count. If not
	 * successed throws an exception.
	 *
	 * @param string $par_Query
	 *
	 * @return integer
	 */
	function SimpleQuery($par_Query)
	{
		if ($this->__connected /*&& is_resource($this->__connection)*/) {
			$result = @mysql_query($par_Query, $this->__connection);
			if (!$result) {
				$this->__QueryError($par_Query);
			} else {
				return @mysql_affected_rows($this->__connection);
			}
		} else {
			throw new Exception(PSK_STR_DB_NOOPENCONNECTION);
		}
	}

	/**
	 * Executes a query and if successed returns first row's first field's value
	 * If not successed throws an exception.
	 *
	 * @param string $par_Query
	 *
	 * @return <type>
	 */
	function ScalarQuery($par_Query)
	{
		//PSK_Log::getInstance()->WriteDebug($par_Query);
		if ($this->__connected /*&& is_resource($this->__connection)*/) {
			$result = @mysql_query($par_Query, $this->__connection);
			if (!$result) {
				$this->__QueryError($par_Query);
			} else {
				$out = @mysql_fetch_row($result);
				@mysql_free_result($result);
				return $out[0];
			}
		} else {
			throw new Exception(PSK_STR_DB_NOOPENCONNECTION);
		}
	}

	/**
	 * Returns the number of rows affected by the last INSERT, UPDATE, REPLACE
	 * or DELETE query.
	 *
	 * @return integer
	 */
	function AffectedRows()
	{
		return @mysql_affected_rows($this->__connection);
	}

	/**
	 * Returns the last inserted record's id. Some databases requires the name
	 * of the sequence or generator.
	 *
	 * @param string $par_SeqOrGenName
	 *
	 * @return <type>
	 */
	function LastInsertId($par_SeqOrGenName = '')
	{
		return @mysql_insert_id($this->__connection);
	}

	/**
	 * Escapes special characters in a string for use in a SQL statement.
	 *
	 * @param string $par_String
	 *
	 * @return string
	 */
	function EscapeString($par_String)
	{
		$es = @mysql_real_escape_string($par_String, $this->__connection);
		return "'" . $es . "'";
	}

	/**
	 * Returns a PSK database constant for key defination instead of database
	 * specifig key defination
	 *
	 * @param string $par_KeyValue
	 */
	function MapKeys($par_KeyValue)
	{
		$map = array(
			'PRI' => PSK_DBK_PRIMARY,
			'MUL' => PSK_DBK_MULTIPLE,
			'UNI' => PSK_DBK_UNIQUE
		);

		$fa = func_get_args();
		return $this->__MapParser($map, $par_KeyValue, PSK_DBK_NONE,
			PSK_DBK_UNKNOWN, __METHOD__, $fa);
	}

	/**
	 * Returns a PSK database constant for field type defination instead of
	 * database specifig field type defination.
	 *
	 * @param string $par_Type
	 */
	function  MapTypes($par_Type)
	{
		$map = array(
			'bit' => PSK_DBT_INTEGER,
			'tinyint' => PSK_DBT_INTEGER,
			'smallint' => PSK_DBT_INTEGER,
			'mediumint' => PSK_DBT_INTEGER,
			'int' => PSK_DBT_INTEGER,
			'bigint' => PSK_DBT_INTEGER,

			'float' => PSK_DBT_FLOAT,
			'double' => PSK_DBT_FLOAT,
			'decimal' => PSK_DBT_FLOAT,

			'char' => PSK_DBT_STRING,
			'varchar' => PSK_DBT_STRING,
			'enum' => PSK_DBT_STRING,
			'set' => PSK_DBT_STRING,

			'tinytext' => PSK_DBT_TEXT,
			'mediumtext' => PSK_DBT_TEXT,
			'text' => PSK_DBT_TEXT,
			'longtext' => PSK_DBT_TEXT,

			'date' => PSK_DBT_DATETIME,
			'time' => PSK_DBT_DATETIME,
			'year' => PSK_DBT_DATETIME,
			'timestamp' => PSK_DBT_DATETIME,
			'datetime' => PSK_DBT_DATETIME,

			'binary' => PSK_DBT_BLOB,
			'varbinary' => PSK_DBT_BLOB,
			'tinyblob' => PSK_DBT_BLOB,
			'mediumblob' => PSK_DBT_BLOB,
			'blob' => PSK_DBT_BLOB,
			'longblob' => PSK_DBT_BLOB
		);

		$fa = func_get_args();
		return $this->__MapParser($map, $par_Type, PSK_DBK_NONE,
			PSK_DBK_UNKNOWN, __METHOD__, $fa);
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_DBDriver_Base::Count()
	 */
	function Count($par_Field, $par_Table, $par_Condition = '')
	{
		$q = 'SELECT COUNT(' . $par_Field . ')' .
			$this->__From($par_Table, $par_Condition);
		$r = $this->ScalarQuery($q);
		return $r;
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_DBDriver_Base::Value()
	 */
	function Value($par_Field, $par_Table, $par_Condition = '')
	{
		return $this->ScalarQuery('SELECT ' . $par_Field .
			$this->__From($par_Table, $par_Condition));
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_DBDriver_Base::Sum()
	 */
	function Sum($par_Field, $par_Table, $par_Condition = '')
	{
		return $this->ScalarQuery('SELECT SUM(' . $par_Field . ')' .
			$this->__From($par_Table, $par_Condition));
	}
}
