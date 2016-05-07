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

require_once 'psk_dbtable.php';
require_once 'psk_dbecol_base.php';
require_once 'psk_dbecol_text.php';
require_once 'psk_dbecol_check.php';
require_once 'psk_dbecol_memo.php';
require_once 'psk_dbecol_file.php';
require_once 'psk_dbecol_image.php';
require_once 'psk_dbecol_select.php';
require_once 'psk_dbecol_commands.php';

/**
 * PSK_DBVisualTable class.
 *
 * Abstract base class for Controls. If you need to develop a control that is
 * maneged by controlles, you should inherit it from this class.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database and UI integration controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBVisualTable class documentation link.
 */
class PSK_DBVisualTable extends PSK_DBTable
{
	/**
	 * Columns of the editor...
	 *
	 * @var array
	 */
	private $_columns = array();

	/**
	 * Determines that primary key field should be used as command field or used
	 * as a regular field.
	 *
	 * @var bool
	 */
	private $_enableEditing = false;

	/**
	 * The column which has the primary key index.
	 *
	 * @var PSK_DBEC_Base
	 */
	protected $__keyColumn = null;

	/**
	 * Constructor of the PSK_DBVisualTable class.
	 *
	 * @param <type> $par_Owner Reference of the owner object.
	 * @param string $par_TableName The name of the database table.
	 * @param string $par_Name      A name for the object.
	 */
	function  __construct($par_Owner, $par_TableName, $par_Name = '')
	{
		if ($par_Owner instanceof PSK_Controller) {
			if ($par_Name == '') {
				$par_Name = $par_Owner->NewObjectName();
			}
			parent::__construct($par_Owner, $par_TableName, $par_Name);
			$par_Owner->AddObject($this);
			return;
		}
		parent::__construct($par_Owner, $par_TableName, $par_Name);
	}

	/**
	 * This function automatically generetes column objects. It adds all the
	 * columns in the table into the _columns array.
	 */
	private function _GenerateColumns()
	{
		if (count($this->_columns) === 0) {
			foreach ($this->FieldProperties() as $fp) {

				//PSK_Log::getInstance()->WriteArray($fp);

				if ($this->_enableEditing && $fp['Key'] == 'primary') {
					$c = new PSK_DBEC_Commands($this, $fp['Name']);
					$c->AddEditCommands();
					$c->AddPostCommands();
					$c->titleTag->style .= 'text-align: center;';
					$c->valueTag->style .= 'text-align: center;';
				} else {
					switch ($fp['Type']) {
						case PSK_DBT_INTEGER:
						case PSK_DBT_FLOAT:
							$c = new PSK_DBEC_Text($this, $fp['Name']);
							$c->valueTag->style = 'text-align: right';
							break;
						case PSK_DBT_TEXT:
							$c = new PSK_DBEC_Memo($this, $fp['Name']);
							break;
						default :
							$c = new PSK_DBEC_Text($this, $fp['Name']);
					}
				}
				$this->AddColumn($c);
				//$this->_columns[$fp['Name']] = $c;
			}
		}
	}

	/**
	 * Extracts field values from _POST array and returns that.
	 *
	 * @return array An associative array which contains field names and values.
	 */
	protected function __GetPost()
	{
		$fv = array();
		foreach ($this->_columns as $column) {
			if (!$column->readOnly)
				$fv[$column->getName()] = $column->ReadFromPost();
		}
		return $fv;
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_DBTable::Open()
	 */
	function Open()
	{
		$this->_GenerateColumns();
		parent::Open();
	}

	/**
	 * Reads a row from the table, assigns the values to the column objects and
	 * returns the _columns array.
	 *
	 * @return array Returns an array that includes PSK_DBEC_Base descendants.
	 */
	function Read($par_ReadMethod = PSK_RM_ASSOC)
	{
		$row = parent::Read($par_ReadMethod);

		//PSK_Log::getInstance()->WriteArray($row);

		if ($row) {
			foreach ($row as $field => $value) {
				$this->Column($field)->valueTag->inner = $value;
				$this->Column($field)->value = $value;
			}
			return $this->_columns;
		}
		return false;
	}

	/**
	 * Adds a column to editor which is related to a field of the table.
	 *
	 * @param PSK_DBEC_Base $par_Column An instance of a column class which
	 *                                  is derived from PSK_DBEC_Base.
	 */
	function AddColumn(PSK_DBEC_Base $par_Column)
	{
		$this->_columns[$par_Column->getName()] = $par_Column;
		$this->AddField($par_Column->fieldSQL);
		$fp = $this->FieldProperties($par_Column->getName());
		if ($fp['Key'] == 'primary') {
			$this->__keyColumn = $par_Column;
		}
	}

	/**
	 * Retuns a column object.
	 *
	 * @param string $par_ColumnName Name of the column (Actualy name of the
	 *                               field).
	 *
	 * @return PSK_DBEC_Base
	 */
	function Column($par_ColumnName)
	{
		$this->_GenerateColumns();

		if (array_key_exists($par_ColumnName, $this->_columns) === true) {
			return $this->_columns[$par_ColumnName];
		} else {
			throw new Exception(sprintf(PSK_STR_TBL_NOSUCHCOLUMN,
				$par_ColumnName, $this->__table));
		}
	}

	/**
	 * Returns the key column of table...
	 *
	 * @return PSK_DBEC_Base
	 */
	function KeyColumn()
	{
		return $this->__keyColumn;
	}

	/**
	 * Returns all of the column objects.
	 *
	 * @return array
	 */
	function Columns()
	{
		$this->_GenerateColumns();
		return $this->_columns;
	}

	/**
	 * Enables editing.
	 */
	function EnableEditing()
	{
		$this->_enableEditing = true;
	}

	/**
	 * Inserts posted data.
	 *
	 * @return integer Affected row count.
	 */
	function DoInsert()
	{
		$fieldValues = $this->__GetPost();

		foreach ($this->Columns() as $col) {
			if ($col instanceof PSK_DBEC_Image) {

				$name = $col->SaveImage();
				if ($name) {
					$fieldValues[$col->getName()] = $name;
				} else {
					$fieldValues[$col->getName()] = '__NOFILE__';
				}
			}
		}

		return $this->Insert($fieldValues);
	}

	/**
	 * Updates posted data.
	 *
	 * @param string $par_Condition Update condtiton.
	 *
	 * @return integer Affected row count.
	 */
	function DoUpdate($par_Condition = '')
	{
		return $this->Update($this->__GetPost(), $par_Condition);
	}

	/**
	 * Deletes a record from table.
	 *
	 * @param string $par_Condition Deletion condition.
	 */
	function DoDelete($par_Condition = '')
	{
		foreach ($this->Columns() as $col) {
			if ($col instanceof PSK_DBEC_Image) {
				$col->DeleteImage (
					$this->__db->Value (
						$col->getName(),
						$this->getTable(),
						$par_Condition));
			}
		}
		return $this->Delete($par_Condition);
	}

	function deleteImage($par_Args)
	{
		$col = $this->Column($par_Args[0]);

		if ($col instanceof PSK_DBEC_Image) {
			$col->DeleteImage($par_Args[1]);
			$this->Update(array($col->getName() => '__NOFILE__'),
				$col->getName() . '=' .
				$this->__db->EscapeString($par_Args[1]));
			PSK_Log::getInstance()->WriteLog(
				sprintf(PSK_STR_DBE_IMAGEDELETED, $par_Args[0]),
				PSK_ET_APPINFORMATION);
		}
	}

	function uploadImage($par_Args)
	{
		$col = $this->Column($par_Args[0]);

		if ($col instanceof PSK_DBEC_Image) {

			$name = $col->SaveImage();
			if ($name) {
				$this->Update(array('resim' => $name), $par_Args[1]);
				PSK_Log::getInstance()->WriteLog(PSK_STR_DBE_IMAGEUPLOADED,
					PSK_ET_APPINFORMATION);
			}
		}
	}
}