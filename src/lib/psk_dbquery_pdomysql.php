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
require_once dirname(__FILE__) . '/psk_dbquery_pdobase.php';

/**
 * PSK_DBQuery_PDOMySQL class.
 *
 * Query interface for connections based on PDO to MySQL.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBQuery_PDOMySQL class documentation link.
 */

class PSK_DBQuery_PDOMySQL extends PSK_DBQuery_PDOBase
{
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

		$field = $this->__resultSet->getColumnMeta($par_FieldOffset);

		$meta['Name'] = $field['name'];
		$meta['Table'] = $field['table'];

		$sql =
			"SELECT DATA_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT, EXTRA
			 FROM information_schema.COLUMNS
			 WHERE TABLE_SCHEMA = '%s' AND TABLE_NAME = '%s' AND COLUMN_NAME = '%s'";

		$query = $this->__owner->Query(sprintf($sql,
			$this->__owner->getDatabase(), $meta['Table'], $meta['Name']));
		$info = $query->FetchNum();
		$query->Free();
		unset ($query);

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
