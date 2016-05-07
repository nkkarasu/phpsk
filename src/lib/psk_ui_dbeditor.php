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

require_once 'psk_dbvisualtable.php';

/**
 * PSK_UI_DBEditor class.
 *
 * Database editor component.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_UIDBEditor class documentation link
 */

class PSK_UI_DBEditor extends PSK_UI_Base
{
	/**
	 * CSS class name for even rows
	 * @var string
	 */
	public $evenRowClass = 'even_row';

	/**
	 * CSS class name for odd rows
	 * @var string
	 */
	public $oddRowClass = 'odd_row';

	public $onModeChange = '';

	/**
	 * The model that intracts data at list mode.
	 *
	 * @var PSK_DBVisualTable
	 */
	private $_listModel = null;

	/**
	 * The model that intracts data at form mode.
	 *
	 * @var PSK_DBVisualTable;
	 */
	private $_formModel = null;

	private $_filter = null;

	private $_visibleTitle = true;

	/**
	 * View mode of the editor
	 *
	 * @see psk_const.php for view mode constants.
	 * @var integer
	 */
	protected $__viewMode = PSK_VM_LIST;

	protected $__dataMode = PSK_DM_VIEW;

	protected $__selectField = '';

	protected $__selectKey = '';

	function __construct(PSK_Controller $par_Owner, $par_CSSClass = '',
	                     $par_Name = '')
	{
		parent::__construct($par_Owner, $par_CSSClass, $par_Name);
		$this->__propertyState['viewMode'] = 'c';
		$this->__propertyState['dataMode'] = 'c';
	}

	function HideTitle()
	{
		$this->_visibleTitle = false;
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Initialize()
	 */
	function Initialize()
	{
		if (!isset($this->_formModel)) $this->_formModel = $this->_listModel;

		if ($this->__propertyState['viewMode'] == 'c') {
			$this->__viewMode = PSK_Application::getInstance()->ReadControlState(
				$this->__objectName . '_viewMode', PSK_VM_LIST,
				$this->__stateStorage);
		}
		if ($this->__propertyState['dataMode'] == 'c') {
			$this->__dataMode = PSK_Application::getInstance()->ReadControlState(
				$this->__objectName . '_dataMode', PSK_DM_VIEW,
				$this->__stateStorage);
		}
		$this->_filter = PSK_Application::getInstance()->ReadControlState(
			$this->__objectName . '_filter', '',
			$this->__stateStorage);
		$this->__selectField = PSK_Application::getInstance()->ReadControlState(
			$this->__objectName . '__selectField', '',
			$this->__stateStorage);
		$this->__selectKey = PSK_Application::getInstance()->ReadControlState(
			$this->__objectName . '_selectKey', '',
			$this->__stateStorage);
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Render()
	 */
	function Render()
	{
		if ($this->__ready) return;

		$this->__Write("\n<div id=\"" . $this->__objectName . "\" class=\"" .
			$this->__cssClass . "\">\n");

		switch ($this->__viewMode) {
			case PSK_VM_LIST:
				if (!isset($this->_listModel)) break;
				$this->__Write("<table class=\"list\" cellspacing=\"0\" cellpadding=\"0\">\n");

				// Render titles...
				if ($this->_visibleTitle) {
					$this->__Write("<tr>\n");

					foreach ($this->_listModel->Columns() as $column) {
						$this->__Write($column->RenderTitle($this->__dataMode,
							$this->__viewMode));
					}
					$this->__Write("</tr>\n");
				}

				// Render values...
				$this->_listModel->Open();
				$rowClass = $this->evenRowClass;

				while ($columns = $this->_listModel->Read()) {

					$rowClass = $rowClass == $this->evenRowClass
						? $this->oddRowClass
						: $this->evenRowClass;

					if ($this->__selectField != '') {
						if ($columns[$this->__selectField]->value ==
							$this->__selectKey
						) $rowClass = 'select_row';
					}

					$this->__Write("<tr class=\"$rowClass\">\n");
					foreach ($columns as $column) {
						$this->__Write($column->RenderValue($this->__dataMode,
							$this->__viewMode));
					}
					$this->__Write("</tr>\n");
				}
				break;
			case PSK_VM_FORM:
				$this->__Write("<table class=\"form\" cellspacing=\"0\" cellpadding=\"0\">\n");
				$this->_formModel->setFilter($this->_filter);
				$this->_formModel->Open();
				if ($this->__dataMode == PSK_DM_INSERT) {
					$columns = $this->_formModel->Columns();
				} else {
					$columns = $this->_formModel->Read();
				}

				//PSK_Log::getInstance()->WriteDebug($this->_filter);
				//PSK_Log::getInstance()->WriteDebug($this->__dataMode);
				//PSK_Log::getInstance()->WriteDebug($this->_listModel->FieldCount());

				if ($columns) {
					foreach ($columns as $column) {
						$this->__Write("<tr>\n");
						$this->__Write($column->RenderTitle($this->__dataMode,
							$this->__viewMode));
						$this->__Write($column->RenderValue($this->__dataMode,
							$this->__viewMode));
						$this->__Write("</tr>\n");
					}
				}
				break;
		}

		$this->__Write("</table>");

		$this->__Write("\n</div>\n\n");
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::ExportState()
	 */
	function ExportState()
	{
		PSK_Application::getInstance()->WriteControlState(
			$this->__objectName . '_viewMode', $this->__viewMode,
			$this->__stateStorage);
		PSK_Application::getInstance()->WriteControlState(
			$this->__objectName . '_dataMode', $this->__dataMode,
			$this->__stateStorage);
		PSK_Application::getInstance()->WriteControlState(
			$this->__objectName . '_filter', $this->_filter,
			$this->__stateStorage);
		PSK_Application::getInstance()->WriteControlState(
			$this->__objectName . '_selectField', $this->__selectField,
			$this->__stateStorage);
		PSK_Application::getInstance()->WriteControlState(
			$this->__objectName . '_selectKey', $this->__selectKey,
			$this->__stateStorage);
	}

	/**
	 * Sets the parents of the columns in a model.
	 *
	 * @param PSK_DBVisualTable $par_VTable The owner model of the columns.
	 */
	public function _setColumnParent(PSK_DBVisualTable $par_VTable)
	{
		foreach ($par_VTable->Columns() as $column) {
			$column->setParent($this);
		}
	}

	private function _SwitchToFormMode($par_Filter, $par_DataMode = PSK_DM_VIEW)
	{
		$this->__dataMode = $par_DataMode;
		$this->_filter = $par_Filter;
		$this->_ModeChanged(PSK_VM_FORM);

		$this->__propertyState['viewMode'] = 's';
	}

	private function _ModeChanged($par_Mode)
	{
		$this->__viewMode = $par_Mode;
		if ($this->onModeChange != '') {
			$args = array(
				'viewMode' => $par_Mode,
				'dataMode' => $this->__dataMode,
				'filter' => $this->_filter);
			$this->CallOwnerMethod($this->onModeChange, $args);
		}
	}

	private function _SwitchToListMode($par_Message = '')
	{
		$this->__dataMode = PSK_DM_VIEW;
		$this->_ModeChanged(PSK_VM_LIST);
		if ($par_Message != '')
			PSK_Log::getInstance()->WriteLog($par_Message,
				PSK_ET_APPINFORMATION);

		$this->__propertyState['viewMode'] = 's';
		$this->__propertyState['dataMode'] = 's';
	}

	/**
	 * Assigns the list model which will used in list mode.
	 *
	 * @param PSK_DBVisualTable $par_ListModel The model that intracts data at
	 *                                         list mode.
	 */
	function setListModel(PSK_DBVisualTable $par_ListModel)
	{
		$this->_listModel = $par_ListModel;
		$this->_setColumnParent($this->_listModel);
	}

	/**
	 * Assigns the form model which will used in form mode.
	 *
	 * @param PSK_DBVisualTable $par_FormModel The model that intracts data at
	 *                                         form mode.
	 */
	function setFormModel(PSK_DBVisualTable $par_FormModel)
	{
		$this->_formModel = $par_FormModel;
		$this->_setColumnParent($this->_formModel);
	}

	/**
	 * Cancels data editing, inserting or deleting.
	 *
	 * @param array $par_Args
	 */
	function cancel(array $par_Args)
	{
		$this->__viewMode = PSK_VM_LIST;
		$this->__dataMode = PSK_DM_VIEW;
		$this->__selectField = '';
		$this->__selectKey = '';
	}

	/**
	 * Adds a new row into model.
	 */
	function insert(array $par_Args)
	{
		$this->_SwitchToFormMode($par_Args[0], PSK_DM_INSERT);
	}

	function doinsert(array $par_Args)
	{
		if ($this->_formModel->DoInsert()) {
			$this->_SwitchToListMode(PSK_STR_DBE_INSERTCOMPLETE);
		}
	}

	function update(array $par_Args)
	{
		$this->_SwitchToFormMode($par_Args[0], PSK_DM_EDIT);
	}

	function doupdate(array $par_Args)
	{
		if ($this->_formModel->DoUpdate($par_Args[0])) {
			$this->_SwitchToListMode(PSK_STR_DBE_UPDATECOMPLETE);
		}
	}

	function delete(array $par_Args)
	{
		$this->_SwitchToFormMode($par_Args[0], PSK_DM_DELETE);
		PSK_Log::getInstance()->WriteLog(PSK_STR_DBE_CONFIRMDELETE,
			PSK_ET_APPWARNING);
	}

	function dodelete(array $par_Args)
	{
		if ($this->_formModel->DoDelete($this->_filter)) {
			$this->_SwitchToListMode(PSK_STR_DBE_DELETECOMPLETE);
		} else {
			$this->_SwitchToFormMode($par_Args[0], PSK_DM_DELETE);
		}
	}

	function select(array $par_Args)
	{
		$a = explode('=', $par_Args[0]);
		$this->__selectField = $a[0];
		$this->__selectKey = $a[1];
	}

	function ViewForm ()
	{
		$this->_SwitchToFormMode('TEST', PSK_DM_INSERT);
	}
}