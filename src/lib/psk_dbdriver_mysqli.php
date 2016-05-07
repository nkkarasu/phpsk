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

require_once dirname(__FILE__) . '/psk_dbquery_mysqli.php';

/**
 * PSK_DB_MySQLi class.
 *
 * Database driver for MySQL server. This driver uses MySQLi extensions.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DB_MySQLi class documentation link.
 */

class PSK_DBDriver_MySQLi extends PSK_DBDriver_Base
{

	/**
	 * Throws an exception about the failed query with extension error message
	 * and error code.
	 *
	 * @param string $par_Query
	 * @throws Exception
	 */
	protected function __QueryError($par_Query = '')
	{
		throw new Exception(sprintf(PSK_STR_DB_CANTEXECQUERY,
				$par_Query, $this->__connection->error),
			$this->__connection->errno);
	}

	/**
	 * Sets up a connection with database server.
	 */
	function Connect()
	{

		$server = $this->__persistent ? 'p:' . $this->__server : $this->__server;
		$this->__connection = new mysqli($server, $this->__user,
			$this->__password, $this->__database);

		switch (mysqli_connect_errno()) {
			case 0:
				if (trim($this->__database) == '') {
					throw new Exception(sprintf(PSK_STR_DB_NODATABASESELECTED,
							$this->__database, mysqli_connect_error()),
						mysqli_connect_errno());
				}
				// Successfull connection.
				break;
			case 1045:
				throw new Exception(sprintf(PSK_STR_DB_ACCESSDENIED,
						$this->__user, mysqli_connect_error()),
					mysqli_connect_errno());
				break;
			case 2002:
				throw new Exception(sprintf(PSK_STR_DB_NOSERVER,
						$this->__server, mysqli_connect_error()),
					mysqli_connect_errno());
				break;
			case 1049:
				throw new Exception(sprintf(PSK_STR_DB_NODATABASE,
						$this->__database, mysqli_connect_error()),
					mysqli_connect_errno());
				break;
			default :
				throw new Exception(mysqli_connect_error(),
					mysqli_connect_errno());
				break;
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
		if ($this->__connected /*&& is_object($this->__connection)*/) {
			$this->__connection->close();
			$this->__connected = false;
		}
	}

	/**
	 * Starts a transaction.
	 */
	function BeginTrans()
	{
		if ($this->__transCount === 0) {
			$this->__connection->autocommit(false);
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
			$this->__connection->commit();
			$this->__transCount--;
		}
		if ($this->__transCount === 0) {
			$this->__connection->autocommit(true);
		}
	}

	/**
	 * Rolls back the last started transaction.
	 */
	function RollBackTrans()
	{
		if ($this->__transCount > 0) {
			$this->__connection->rollback();
			$this->__transCount--;
		}
		if ($this->__transCount === 0) {
			$this->__connection->autocommit(true);
		}
	}

	/**
	 * Executes a query and if successed, returns an instance of
	 * PSK_DBQuery_MySQLi. If not successed throws an exception.
	 *
	 * @param string $par_Query
	 *
	 * @throws Exception
	 * @return PSK_DBQuery_MySQLi
	 */
	function Query($par_Query)
	{
		if ($this->__connected /*&& (is_a($this->__connection, 'mysqli'))*/) {
			$result = $this->__connection->query($par_Query);
			if (!$result) {
				$this->__QueryError($par_Query);
			} else {
				$query = new PSK_DBQuery_MySQLi($this);
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
	 * @throws Exception
	 * @return integer
	 */
	function SimpleQuery($par_Query)
	{
		if ($this->__connected /*&& (is_a($this->__connection, 'mysqli'))*/) {
			$result = $this->__connection->query($par_Query);
			if (!$result) {
				$this->__QueryError($par_Query);
			} else {
				return $this->__connection->affected_rows;
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
	 * @throws Exception
	 * @return void <type>
	 */
	function ScalarQuery($par_Query)
	{
		if ($this->__connected /*&& (is_a($this->__connection, 'mysqli'))*/) {
			$result = $this->__connection->query($par_Query);
			if (!$result) {
				$this->__QueryError($par_Query);
			} else {
				$out = $result->fetch_row();
				$result->close();
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
		return $this->__connection->affected_rows;
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
		return $this->__connection->insert_id;
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
		$es = $this->__connection->real_escape_string($par_String);
		return "'" . $es . "'";
	}

	/**
	 * Returns a PSK database constant for key defination instead of database
	 * specifig key defination
	 *
	 * @param integer $par_KeyValue
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
		return $this->__MapParser($map, $par_Type, PSK_DBT_NONE,
			PSK_DBT_UNKNOWN, __METHOD__, $fa);
	}

	/**
	 *
	 * @see PSK_DBDriver_Base::Count()
	 * @param string $par_Field
	 * @param string $par_Table
	 * @param string $par_Condition
	 * @throws Exception
	 * @return int|void
	 */
	function Count($par_Field, $par_Table, $par_Condition = '')
	{
		return $this->ScalarQuery('SELECT COUNT(' . $par_Field . ')' .
			$this->__From($par_Table, $par_Condition));
	}

	/**
	 *
	 * @see PSK_DBDriver_Base::Value()
	 * @param string $par_Field
	 * @param string $par_Table
	 * @param string $par_Condition
	 * @throws Exception
	 * @return mixed|void
	 */
	function Value($par_Field, $par_Table, $par_Condition = '')
	{
		return $this->ScalarQuery('SELECT ' . $par_Field .
			$this->__From($par_Table, $par_Condition));
	}

	/**
	 *
	 * @see PSK_DBDriver_Base::Sum()
	 * @param $par_Field
	 * @param $par_Table
	 * @param string $par_Condition
	 * @throws Exception
	 * @return int|void
	 */
	function Sum($par_Field, $par_Table, $par_Condition = '')
	{
		return $this->ScalarQuery('SELECT SUM(' . $par_Field . ')' .
			$this->__From($par_Table, $par_Condition));
	}
}


