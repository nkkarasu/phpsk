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
 * PSK_DBQuery_Base class.
 *
 * Base class for database queries.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBQuery_Base class documentation link.
 */

abstract class PSK_DBQuery_Base extends PSK_OwnedObject
{
	/**
	 * Stores meta information about fields in the result set. This array
	 * populates by FieldInfo method at first call. Other calls to FieldInfo
	 * uses this array to return meta information about the field.
	 *
	 * @var array
	 */
	protected $__fieldMeta = array();

	/**
	 * Constructor for PSK_DBQuery_Base class.
	 *
	 * @param PSK_DBDriver_Base $par_Owner
	 * @param string            $par_Name
	 */
	function  __construct(PSK_DBDriver_Base $par_Owner, $par_Name = '')
	{
		parent::__construct($par_Owner, $par_Name);
	}

	/**
	 * Assigns the result set of the query.
	 */
	abstract function setResultSet($par_ResultSet);

	/**
	 * Clears the result set from memory.
	 */
	abstract function Free();

	/**
	 * Returns a result row as an enumerated array. Each call to this function
	 * returns the next row in the result set or NULL if there are no more rows.
	 */
	abstract function FetchNum();

	/**
	 * Returns a result row as an associative array. Each call to this function
	 * returns the next row in the result set or NULL if there are no more rows.
	 */
	abstract function FetchAssoc();

	/**
	 * Returns a result row as an object. Each call to this function returns the
	 * next row in the result set or NULL if there are no more rows.
	 */
	abstract function FetchObject();

	/**
	 * Returns the number of rows in the result set.
	 */
	abstract function RowCount();

	/**
	 * Returns the number of columns in the result set.
	 */
	abstract function FieldCount();

	/**
	 * Returns the meta information about specified field in the result set as
	 * an associative array. This array contains below keys.
	 * Name, Table, Type, RealType, AllowNull, Key, Default, Extra
	 */
	abstract function FieldProperties($par_FieldOffset);

}

?>
