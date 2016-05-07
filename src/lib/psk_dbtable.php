<?php
/**
 * PSK
 *
 * An open source PHP web application development framework.
 *
 * @package    PSK (PHP Sınıf Kütüphanesi)
 * @author     Namık Kemal Karasu
 * @copyright  Copyright (C) Namık Kemal Karasu
 * @license    GPLv3
 * @since      Version 0.
 * @link       http://nkkarasu.net/psk/
 * @link       http://code.google.com/p/phpsk/
 */

/**
 * PSK_DBTable class.
 *
 * Generates SELECT, UPDATE, DELETE and INSERT statetements for a table
 * and executes it. You can inherit your models from this class.
 *
 * @package       PSK
 * @subpackage    Libraries
 * @category      Database
 * @author        Namık Kemal Karasu
 * @link          TODO add PSK_DBTable class documentation link.
 */

class PSK_DBTable extends PSK_OwnedObject
{
	/**
	 * Determines that select query has been executed or not.
	 *
	 * @var boolean
	 */
	protected $__active = false;

	/**
	 * Determines that any change has been made or not that may effect query
	 * results.
	 *
	 * @var boolean
	 */
	protected $__modified = true;

	/**
	 * The object which handles database stuff.
	 *
	 * @var PSK_DBDriver_Base
	 */
	protected $__db = null;

	/**
	 * Name of the table which PSK_DBTable interacts with.
	 *
	 * @var string
	 */
	protected $__table = '';

	/**
	 * The condition which will be evaluated at the WHERE clause of a
	 * SELECT query.
	 *
	 * @var string
	 */
	protected $__filter = '';

	/**
	 * The ORDER BY part of the SELECT query.
	 *
	 * @var string
	 */
	protected $__index = '';

	/**
	 * Identifes that query should have limits or not.
	 *
	 * @var boolean
	 */
	protected $__limited = false;

	/**
	 * Identifies the start of limit.
	 *
	 * @var integer
	 */
	protected $__limitStart = 0;

	/**
	 * Identifies the size of limit.
	 *
	 * @var integer
	 */
	protected $__limitSize = 0;

	/**
	 * Select query of the table.
	 *
	 * @var string
	 */
	protected $__selectQuery = '';

	/**
	 * Delete query of the table.
	 *
	 * @var string
	 */
	protected $__deleteQuery = '';

	/**
	 * Insert query of the table.
	 *
	 * @var string
	 */
	protected $__insertQuery = '';

	/**
	 * Update query of the table.
	 *
	 * @var string
	 */
	protected $__updateQuery = '';

	/**
	 * Names of the fields that will be used for query generation.
	 *
	 * @var array
	 */
	protected $__queryFieldNames = array();

	/**
	 * Names of the fields in the table.
	 *
	 * @var array
	 */
	protected $__tableFieldNames = array();

	/**
	 * Properties of the fields in the table.
	 *
	 * @var array
	 */
	protected $__fieldProps = array();

	/**
	 * @var PSK_DBQuery_Base
	 */
	protected $__queryResult = null;

	protected $__modelState = array();

	/**
	 * Constructor of the PSK_DBTable class.
	 *
	 * @param object $par_Owner     The object that owns this object.
	 * @param string $par_TableName The name of the table.
	 * @param string $par_Name      A name for the object.
	 */
	function  __construct($par_Owner, $par_TableName = '', $par_Name = '')
	{
		parent::__construct($par_Owner, $par_Name);
		$this->__db = PSK_Application::getInstance()->db;
		$this->__table = $par_TableName;
	}

	/**
	 * Assigns a value to a property.
	 *
	 * @param string $par_Property Name of the property which will get
	 *                             set.
	 * @param <type> $par_Value The value of the property.
	 */
	private function _AssignProperty($par_Property, $par_Value)
	{
		if ($par_Value == '') return;
		if ($this->$par_Property !== $par_Value) {
			$this->$par_Property = $par_Value;
			$this->__modified = true;
		}
	}

	/**
	 * Generates a fake query to retrive the properties of fields in
	 * the table.
	 */
	private function _GenerateFieldProperties()
	{
		if ((count($this->__fieldProps) === 0) || ($this->__modified)) {
			$fis = 'SELECT ';
			if (count($this->__queryFieldNames) === 0) {
				$fis .= '* ';
			} else {
				foreach ($this->__queryFieldNames as $field) {
					$fis .= $field . ', ';
				}
				$fis = rtrim($fis, ', ');
			}
			$fis .= ' FROM ' . $this->__table;
			$fis .= ' WHERE 1 = 2';

			$fiq = $this->__db->Query($fis);
			$this->__fieldProps = array();

			$this->__tableFieldNames = array();

			for ($i = 0; $i < $fiq->FieldCount(); $i++) {
				$fi = $fiq->FieldProperties($i);
				$this->__fieldProps[$fi['Name']] = $fi;
				$this->__fieldProps[$fi['Name']]['InputName'] =
					$this->__table . '-' . $fi['Name'];
				$this->__tableFieldNames[] = $fi['Name'];
			}

			$fiq->Free();
		}

		//PSK_Log::getInstance()->WriteArray($this->__fieldProps);
	}

	/**
	 * Generates the select query of the table.
	 */
	private function _GenerateSelect()
	{
		$this->__selectQuery = 'SELECT ';
		if (count($this->__queryFieldNames) === 0) {
			$this->__selectQuery .= '* ';
		} else {
			foreach ($this->__queryFieldNames as $field) {
				$this->__selectQuery .= $field . ', ';
			}
			$this->__selectQuery = rtrim($this->__selectQuery, ', ');
		}
		$this->__selectQuery .= ' FROM ' . $this->__table;

		if ($this->__filter !== '') {
			$this->__selectQuery .= ' WHERE ' . $this->__filter;
		}

		if ($this->__index !== '') {
			$this->__selectQuery .= ' ORDER BY ' . $this->__index;
		}

		if ($this->__limited) {
			$this->__selectQuery .= ' LIMIT ' . $this->__limitStart . ', ' .
				$this->__limitSize;
		}
		$this->__modified = false;

		//PSK_Log::getInstance()->WriteDebug($this->__selectQuery);
	}

	/**
	 * Returns the status of table. Does it has an open query or does
	 * not.
	 */
	function isActive()
	{
		return $this->__active;
	}

	/**
	 * Adds a field to table to get managed.
	 *
	 * @param string $par_FieldName Name of the field to be managed.
	 */
	function AddField($par_FieldName)
	{
		$this->__queryFieldNames[] = $par_FieldName;
	}

	/**
	 * Adds fields to table to get managed.
	 *
	 * @param array $par_FieldNames An array that contains the names of
	 *                              the fields to be get managed.
	 */
	function AddFields(array $par_FieldNames)
	{
		foreach ($par_FieldNames as $fieldName) {
			$this->__queryFieldNames[] = $fieldName;
		}
	}

	/**
	 * Executes the select query.
	 */
	function Open()
	{
		if ($this->__active) return;
		if ($this->__table == '') {
			throw new Exception(PSK_STR_TBL_MISSINGTABLENAME);
		}
		if ($this->__modified) $this->_GenerateSelect();
		$this->__queryResult = $this->__db->Query($this->__selectQuery);
		$this->__active = true;
		$this->_GenerateFieldProperties();
		return $this->__queryResult->RowCount();
	}

	/**
	 * Frees the executed select query.
	 */
	function Close()
	{
		if (!$this->__active) return;
		$this->__queryResult->Free();
		$this->__active = false;
	}

	/**
	 * Returns a row from the result of select query as an array.
	 * If query is activated. By default the result is an associative
	 * array with field names. Also you can use PSK_RM_NUM as
	 * $par_ReadMethod to get an indexed array with numbers.
	 *
	 * @param integer $par_ReadMethod Identifies how to return resul
	 *                                array. Use PSK_RM_NUM as $par_ReadMethod to get an indexed array
	 *                                with numbers or use PSK_RM_ASSOC to get an associative array.
	 */
	function Read($par_ReadMethod = PSK_RM_ASSOC)
	{
		if (!$this->__active) return false;
		switch ($par_ReadMethod) {
			case PSK_RM_ASSOC:
				return $this->__queryResult->FetchAssoc();
			case PSK_RM_NUM:
				return $this->__queryResult->FetchNum();
			case PSK_RM_OBJECT:
				return $this->__queryResult->FetchObject();
		}
	}

	/**
	 * Inserts data to table. Use an associative array for to define
	 * values to be added.
	 *
	 * @param array $par_FieldValues An associative array wich contains
	 *                               field names and values.
	 *
	 * @throws Exception If an empty array passed for $par_FieldValues.
	 */
	function Insert(array $par_FieldValues)
	{
		$this->_GenerateFieldProperties();

		$fl = '';
		$vl = '';

		foreach ($par_FieldValues as $field => $value) {
			if (($value !== '') &&
				(!$this->__fieldProps[$field]['AutoIncrement'])
			) {
				$fl .= '`' . $field . '`, ';

                if (ini_get('magic_quotes_gpc')) {
                    $vl .= ' "'.$value . '" ,';
                }
                else {
				    $vl .= $this->__db->EscapeString($value) . " ,";
                }
			}
		}

		if ($fl == '') {
			throw new Exception(PSK_STR_TBL_EMTYFIELDLIST);
		}

		if (!$this->Validate($par_FieldValues)) return;

		$this->__insertQuery = 'INSERT INTO `' . $this->__table . '` (' .
			rtrim($fl, ', ') . ') VALUES (' . rtrim($vl, ', ') . ')';

		//$this->__owner->log->WriteLog($this->__insertQuery, PSK_ET_APPWARNING);

		//$this->AddModelState($this->__insertQuery, 'query');

		$res = $this->__db->SimpleQuery($this->__insertQuery);
		if ($res) $this->__modified = true;
		return $this->__db->LastInsertId();
	}

	/**
	 * Updates table data. Use an associative array for to define
	 * values to be updated.
	 *
	 * @param array  $par_FieldValues An associative array wich contains
	 *                                field names and values.
	 * @param string $par_Condition   Use this if you need a condition for
	 *                                update.
	 *
	 * @throws Exception If an empty array passed for $par_FieldValues.
	 */
	function Update(array $par_FieldValues, $par_Condition = '', $par_AllowEmptyStrings = false)
	{
		if (count($par_FieldValues) == 0) {
			throw new Exception(PSK_STR_TBL_EMTYFIELDLIST);
		}
		$this->_GenerateFieldProperties();

		$uq = 'UPDATE `' . $this->__table . '` SET ';
		foreach ($par_FieldValues as $field => $value) {

			if ($this->__fieldProps[$field]['Type'] == PSK_DBT_DATETIME) {
				if ($value == '') {
					$uq .= '`' . $field . "` = NULL, ";
					continue;
				}
			}

			if ($par_AllowEmptyStrings) {
                if (ini_get('magic_quotes_gpc')) {
                    $uq .= '`' . $field . '` = "' .
                        $value . '", ';
                }
                else {
                    $uq .= '`' . $field . "` = " .
                        $this->__db->EscapeString($value) . ", ";
                }
			}
			else {
				if (($value !== '')) {
                    if (ini_get('magic_quotes_gpc')) {
                        $uq .= '`' . $field . '` = "' . $value . '", ';
                    }
                    else {
                        $uq .= '`' . $field . "` = " . $this->__db->EscapeString($value) . ", ";
                    }
				}
				//else {
				//	$uq .= '`' . $field . "` = NULL, ";
				//}
			}
		}

		if (!$this->Validate($par_FieldValues)) return;

		$uq = rtrim($uq, ', ');
		if ($par_Condition != '') $uq .= ' WHERE ' . $par_Condition;
		$this->__updateQuery = $uq;

		//$this->__owner->log->WriteDebug($this->__updateQuery);
		//$this->__owner->log->WriteArray($this->__fieldProps);

		//$this->AddModelState($this->__updateQuery, 'query');

		$res = $this->__db->SimpleQuery($this->__updateQuery);
		if ($res) $this->__modified = true;
		return $res;
	}

	/**
	 * Deletes data from table.
	 *
	 * @param string $par_Condition Use this if you need a condition for
	 *                              delete.
	 */
	function Delete($par_Condition = '')
	{
		$this->__deleteQuery = 'DELETE FROM `' . $this->__table . '`';
		if ($par_Condition !== '')
			$this->__deleteQuery .= ' WHERE ' . $par_Condition;

		//$this->__owner->log->WriteDebug($this->__deleteQuery);

		$res = $this->__db->SimpleQuery($this->__deleteQuery);
		if ($res) $this->__modified = true;
		return $res;
	}

	/**
	 * Returns an array that contains field values from _POST.
	 *
	 * @return array
	 */
	function ValuesFromPost()
	{
		$this->_GenerateFieldProperties();

		$fv = array();

		foreach ($this->__fieldProps as $field) {
			//PSK_Log::getInstance()->WriteArray($field);
			if (isset($_POST[$field['InputName']])) {
				//if (!empty($_POST[$field['InputName']])) {
				$fv[$field['Name']] = $_POST[$field['InputName']];
				//}
			}
		}

		//PSK_Log::getInstance()->WriteArray($fv);
		return $fv;
	}

	/**
	 * Returns the count of fields of table.
	 */
	function FieldCount()
	{
		return $this->__queryResult->FieldCount();
	}

	/**
	 * Returns the properties of a field.
	 *
	 * @param string $par_FieldName Name of the field whichs properties to be
	 *                              returned. If you did not set $par_FieldName then it returns properties
	 *                              all fields.
	 */
	function FieldProperties($par_FieldName = '')
	{
		$this->_GenerateFieldProperties();
		if ($par_FieldName == '') return $this->__fieldProps;
		return $this->__fieldProps[$par_FieldName];
	}

	/**
	 * Returns input name of a field. Input name of a field can be used for
	 * reding values of a field form _POST for inserting and updating.
	 *
	 * @param string $par_FieldName Name of the field whichs input name to be
	 *                              returned.
	 *
	 * @return string
	 */
	function InputName($par_FieldName)
	{
		$this->_GenerateFieldProperties();
		return $this->__fieldProps[$par_FieldName]['InputName'];
	}

	/**
	 * Override this function to validate field values before an insert or
	 * update event. Return true if values are valid othervise return false to
	 * cancel event.
	 */
	function Validate(array &$par_FieldValues)
	{
		return true;
	}

	function AddModelState($par_State, $par_Section = 'error') {
		$this->__modelState[$par_Section][] = $par_State;
	}

	/**
	 * Sets the table name property.
	 *
	 * @param string $par_Table
	 */
	function setTable($par_Table)
	{
		$this->_AssignProperty('__table', $par_Table);
	}

	/**
	 * Sets the index property. This property defines the ORDER BY part of the
	 * select query.
	 *
	 * @param string $par_Index
	 */
	function setIndex($par_Index)
	{
		$this->_AssignProperty('__index', $par_Index);
	}

	/**
	 * Sets the filter property. This property defines the WHERE part of
	 * the select query.
	 *
	 * @param string $par_Filter
	 */
	function setFilter($par_Filter)
	{
		$this->_AssignProperty('__filter', $par_Filter);
	}

	/**
	 * Sets the limit properties.
	 *
	 * @param integer $par_LimitStart
	 * @param integer $par_LimitSize
	 */
	function setLimit($par_LimitStart, $par_LimitSize)
	{
		$this->_AssignProperty('__limitStart', $par_LimitStart);
		$this->_AssignProperty('__limitSize', $par_LimitSize);
		$this->__limited = true;
	}

	/**
	 * Returns the table name.
	 */
	function getTable()
	{
		return $this->__table;
	}

	function getModelState() {
		return $this->__modelState;
	}
}
