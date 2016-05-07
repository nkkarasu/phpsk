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

require_once dirname(__FILE__) . '/psk_dbdriver_pdobase.php';

/**
 * PSK_DB_PDOMySQL class.
 *
 * Database driver for MySQL server. This driver uses PDO extensions.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DB_PDOMySQL class documentation link.
 */

class PSK_DBDriver_PDOMySQL extends PSK_DBDriver_PDOBase
{
	/**
	 * Sets up a connection with database server.
	 */
	function  Connect()
	{
		if ($this->__connected) return;

		$options = array();
		$options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
		$options[PDO::ATTR_PERSISTENT] = $this->__persistent;

		try {
			$dsn = 'mysql:host=' . $this->__server . ';dbname=' . $this->__database;

			//PSK_Log::getInstance()->WriteDebug($dsn);

			$this->__connection = new PDO($dsn, $this->__user, $this->__password, $options);

			$this->__connected = true;

			$this->SimpleQuery("SET NAMES '" . $this->__charSet .
				"' COLLATE '" . $this->__collation . "'");
		} catch (Exception $e) {
			switch ($e->getCode()) {
				case 1045:
					throw new Exception(sprintf(PSK_STR_DB_ACCESSDENIED,
						$this->__user, $e->getMessage()), $e->getCode());
					break;
				case 1046:
					throw new Exception(sprintf(PSK_STR_DB_NODATABASESELECTED,
						$e->getMessage()), $e->getCode());
					break;
				case 1049:
					throw new Exception(sprintf(PSK_STR_DB_NODATABASE,
						$this->__database, $e->getMessage()), $e->getCode());
					break;
				case 2002:
					throw new Exception(sprintf(PSK_STR_DB_NOSERVER,
						$this->__server, $e->getMessage()), $e->getCode());
					break;
				default:
					throw $e;
					break;
			}
		}
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
	 * (non-PHPdoc)
	 * @see PSK_DBDriver_Base::Count()
	 */
	function Count($par_Field, $par_Table, $par_Condition = '')
	{
		return $this->ScalarQuery('SELECT COUNT(' . $par_Field . ')' .
			$this->__From($par_Table, $par_Condition));
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
