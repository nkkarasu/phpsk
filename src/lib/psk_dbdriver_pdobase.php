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

require_once dirname(__FILE__) . '/psk_dbquery_pdomysql.php';

/**
 * PSK_DB_PDOBase class.
 *
 * Base class for database drivers that uses PDO.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DB_PDOBase class documentation link.
 */
abstract class PSK_DBDriver_PDOBase extends PSK_DBDriver_Base
{
	/**
	 * Count of the rows that affected by the last SimpleQuery method call.
	 *
	 * @var integer
	 */
	protected $__affectedRows = 0;

	/**
	 * Ends a connection from database server.
	 */
	function  DisConnect()
	{
		$this->__connection = null;
		$this->__connected = false;
	}

	/**
	 * Starts a transaction.
	 */
	function BeginTrans()
	{
		$this->__connection->beginTransaction();
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
	}

	/**
	 * Rolls back the last started transaction.
	 */
	function RollBackTrans()
	{
		if ($this->__transCount > 0) {
			$this->__connection->rollBack();
			$this->__transCount--;
		}
	}

	/**
	 * Executes a query and if successed, returns an instance of
	 * PSK_DBQuery_PDO. If not successed throws an exception.
	 *
	 * @param string $par_Query
	 *
	 * @return PSK_DBQuery_PDO
	 */
	function Query($par_Query)
	{
		//PSK_Log::getInstance()->WriteLog($par_Query, PSK_ET_APPWARNING); 

		if ($this->__connected /*&& (is_a($this->__connection, 'PDO'))*/) {
			$query = new PSK_DBQuery_PDOMySQL($this);
			$result = $this->__connection->query($par_Query);
			$query->setResultSet($result);
			return $query;
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
		if ($this->__connected /*&& (is_a($this->__connection, 'PDO'))*/) {
			try {
				$this->__affectedRows = $this->__connection->exec($par_Query);
				return $this->__affectedRows;
			} catch (PDOException $e) {
				throw new Exception(sprintf(PSK_STR_DB_CANTEXECQUERY,
					$par_Query, $e->getMessage()), 0);
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
		if ($this->__connected /*&& (is_a($this->__connection, 'PDO'))*/) {
			try {
				$result = $this->__connection->query($par_Query);
				$out = $result->fetchColumn(0);
				$result = null;
				return $out;
			} catch (PDOException $e) {
				throw new Exception(sprintf(PSK_STR_DB_CANTEXECQUERY,
					$par_Query, $e->getMessage()), 0);
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
		return $this->__affectedRows;
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
		return $this->__connection->lastInsertId($par_SeqOrGenName);
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
		return $this->__connection->quote($par_String);
	}
}

?>
