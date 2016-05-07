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

/**
 * PSK_DBDriver_Base class.
 *
 * Base class for database drivers.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBDriver_Base class documentation link.
 */
abstract class PSK_DBDriver_Base extends PSK_OwnedObject
{
	/**
	 * Name of the database server.
	 *
	 * @var unknown_type
	 */
	protected $__server = 'localhost';

	/**
	 * User name for to access database
	 * @var string
	 */
	protected $__user = 'root';

	/**
	 * Password for to access database.
	 *
	 * @var string
	 */
	protected $__password = '';

	/**
	 * Database schema.
	 *
	 * @var string
	 */
	protected $__database = '';

	/**
	 * Determines how to connnect to database server. If true it tries to use
	 * a previously opened connection. If false it always opens a new connection.
	 *
	 * @var boolean
	 */
	protected $__persistent = false;

	/**
	 * Character of the database schema.
	 *
	 * @var string
	 */
	protected $__charSet = 'utf8';

	/**
	 * Collation of the database schema.
	 *
	 * @var string
	 */
	protected $__collation = '';

	/**
	 * Determines to connect to database server automatically or not.
	 *
	 * @var boolean
	 */
	protected $__autoConnect = false;

	/**
	 * Connection status.
	 *
	 * @var boolean
	 */
	protected $__connected = false;

	/**
	 * Resource link of connection.
	 *
	 * @var resource
	 */
	protected $__connection = null;

	/**
	 * Transaction count.
	 *
	 * @var integer
	 */
	protected $__transCount = 0;

	/**
	 * Returns a value from the Map array related to the Item. If the Item is
	 * an empty string then returns NonVal, if the Item is not exist in Map
	 * array then returns the MissingVal and adds a warning to log under DEBUG
	 * mode.
	 *
	 * @param array  $par_Map
	 * @param string $par_Item
	 * @param  <type> $par_NoneVal
	 * @param  <type> $par_MissingVal
	 * @param string $par_DebugMethod
	 * @param array  $par_DebugArgs
	 *
	 * @return <type>
	 */
	protected function __MapParser(array &$par_Map, $par_Item, $par_NoneVal,
	                               $par_MissingVal, $par_DebugMethod = '', array $par_DebugArgs = array())
	{
		if (array_key_exists($par_Item, $par_Map)) {
			return $par_Map[$par_Item];
		}

		if ($par_Item != '') {
			if (defined('DEBUG')) {
				PSK_Log::getInstance()->WriteLog(sprintf(PSK_DM_MISSINGPIECE,
						$par_DebugMethod . '(' . implode(', ', $par_DebugArgs) . ')'),
					PSK_ET_APPWARNING);
			}
			return $par_MissingVal;
		}

		return $par_NoneVal;
	}

	/**
	 * Destructor of database driver classes.
	 */
	function __destruct()
	{
		$this->DisConnect();
	}

	/**
	 * Sets the server name or address.
	 *
	 * @param string $par_Server Server name or address.
	 */
	function setServer($par_Server)
	{
		$this->__server = $par_Server;
	}

	/**
	 * Sets user name.
	 *
	 * @param string $par_User User name.
	 */
	function setUser($par_User)
	{
		$this->__user = $par_User;
	}

	/**
	 * Password of the user.
	 *
	 * @param string $par_Password Password of the user.
	 */
	function setPassword($par_Password)
	{
		$this->__password = $par_Password;
	}

	/**
	 * Sets the database schema.
	 *
	 * @param string $par_Database Database schema.
	 */
	function setDatabase($par_Database)
	{
		$this->__database = $par_Database;
	}

	/**
	 * Sets the use persistent connection property. Persistent connections
	 * increases performans of your application.
	 *
	 * @param boolean $par_Persistent
	 */
	function setPersistent($par_Persistent)
	{
		$this->__persistent = $par_Persistent;
	}

	/**
	 * Sets the character of the database schema.
	 *
	 * @param string $par_CharSet Character of the database schema.
	 */
	function setCharSet($par_CharSet)
	{
		$this->__charSet = $par_CharSet;
	}

	/**
	 * Sets the collation of the database schema.
	 *
	 * @param string $par_Collation Collation of the database schema.
	 */
	function setCollation($par_Collation)
	{
		$this->__collation = $par_Collation;
	}

	/**
	 * Sets the auto connect property.
	 *
	 * @param boolean $par_AutoConnect <b>true</b> for auto connect.
	 * <b>false</b> for manuel connect.
	 */
	function setAutoConnect($par_AutoConnect)
	{
		$this->__autoConnect = $par_AutoConnect;
	}

	/**
	 * Returns the auto connect status.
	 *
	 * @return boolean
	 */
	function getAutoConnect()
	{
		return $this->__autoConnect;
	}

	/**
	 * Returns the database name which driver connected to.
	 *
	 * @return string
	 */
	function getDatabase()
	{
		return $this->__database;
	}

	/**
	 * Returns connection status.
	 *
	 * @return boolean
	 */
	function isConnected()
	{
		return $this->__connected;
	}

	/**
	 * Generates FROM part of a query.
	 *
	 * @param string $par_Table     Name of the table.
	 * @param string $par_Condition Condition for WHERE part.
	 */
	protected function __From($par_Table, $par_Condition = '')
	{
		$r = ' FROM ' . $par_Table;
		if ($par_Condition !== '') {
			$r .= ' WHERE ' . $par_Condition;
		}
		return $r;
	}

	/**
	 * Sets up a connection with database server.
	 */
	abstract function Connect();

	/**
	 * Ends a connection from database server.
	 */
	abstract function DisConnect();

	/**
	 * Starts a transaction.
	 */
	abstract function BeginTrans();

	/**
	 * Commits the last started transaction.
	 */
	abstract function CommitTrans();

	/**
	 * Rolls back the last started transaction.
	 */
	abstract function RollBackTrans();

	/**
	 * Executes a query and if successed, returns an instance of
	 * PSK_DBQuery_Base. If not successed throws an exception.
	 *
	 * @return PSK_DBQuery_Base
	 */
	abstract function Query($par_Query);

	/**
	 * Executes a query and if successed returns the affected row count. If not
	 * successed throws an exception.
	 */
	abstract function SimpleQuery($par_Query);

	/**
	 * Executes a query and if successed returns first row's first field's value
	 * If not successed throws an exception.
	 */
	abstract function ScalarQuery($par_Query);

	/**
	 * Returns the number of rows affected by the last INSERT, UPDATE, REPLACE
	 * or DELETE query.
	 */
	abstract function AffectedRows();

	/**
	 * Returns the last inserted record's id. Some databases requires the name
	 * of the sequence or generator.
	 */
	abstract function LastInsertId($par_SeqOrGenName = '');

	/**
	 * Escapes special characters in a string for use in a SQL statement.
	 */
	abstract function EscapeString($par_String);

	/**
	 * Returns a PSK database constant for key defination instead of database
	 * specifig key defination
	 */
	abstract function MapKeys($par_KeyValue);

	/**
	 * Returns a PSK database constant for field type defination instead of
	 * database specifig field type defination.
	 */
	abstract function MapTypes($par_Type);

	/**
	 * Returns the count of records in a table for a given field name and
	 * condition.
	 *
	 * @param string $par_Field     Name of the field for counting.
	 * @param string $par_Table     Name of the table that contains the field.
	 * @param string $par_Condition Condition for counting.
	 *
	 * @return integer
	 */
	abstract function Count($par_Field, $par_Table, $par_Condition = '');

	/**
	 * Returns the value of a field in a table for given condition.
	 *
	 * @param string $par_Field     Name of the field for counting.
	 * @param string $par_Table     Name of the table that contains the field.
	 * @param string $par_Condition Condition for counting.
	 *
	 * @return mixed
	 */
	abstract function Value($par_Field, $par_Table, $par_Condition = '');

	/**
	 * Returns the sum of a field in a table for given condition.
	 *
	 * @param        $par_Field
	 * @param        $par_Table
	 * @param string $par_Condition
	 *
	 * @return integer
	 */
	abstract function Sum($par_Field, $par_Table, $par_Condition = '');

	/**
	 * Returns only one row data in an associative array.
	 *
	 * @param string $par_Condition Contion for to filter the table data. If not
	 * provided than the first row will be returned.
	 *
	 * @return array
	 */
	//abstract function ReadRow($par_Condition = '');
}

?>
