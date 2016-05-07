<?php

/**
 * PSK
 *
 * An open source PHP web application development framework.
 *
 * @package      PSK (PHP Sınıf Kütüphanesi)
 * @author       Namık Kemal Karasu
 * @copyright    Copyright (C) Namık Kemal Karasu
 * @license      GPLv3
 * @since        Version 0.
 * @link         http://nkkarasu.net/psk/
 * @link         http://code.google.com/p/phpsk/
 */

/**
 * PSK_DBEC_Commands class.
 *
 * This class uses column data for genereting commands. It uses the column
 * data as key values.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database and UI integration controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBEC_Commands class documentation link.
 */
class PSK_DBEC_Commands extends PSK_DBEC_Base
{
	/**
	 * The array that contains commands.
	 *
	 * @var array
	 */
	private $_commands = array();

	private $_classes = array(
		'insert' => 'yellow',
		'update' => 'green',
		'delete' => 'red',
		'doinsert' => 'yellow',
		'doupdate' => 'green',
		'dodelete' => 'red',
		'cancel' => ''
	);

	/**
	 * Constructor of PSK_DBEC_Commands class.
	 *
	 * @param PSK_DBTable $par_Owner    The PSK_DBTable object which will provide
	 *                                  data to this column.
	 * @param string      $par_Name     The name of the table field which will be related
	 *                                  to this column.
	 * @param string      $par_FieldSQL SQL code for agregeted or calculated fields.
	 */
	function __construct(PSK_DBTable $par_Owner, $par_Name, $par_FieldSQL = '')
	{
		parent::__construct($par_Owner, $par_Name, $par_FieldSQL);
		$this->readOnly = true;
	}

	/**
	 * Adds a command.
	 *
	 * @param string  $par_CommandText       Caption of the command
	 * @param string  $par_TargetMethod      Name of the method which will be invoked
	 *                                       if this command get invoked.
	 * @param integer $par_VisibleDataMode   Determines the data mode when this
	 *                                       command can be visible.
	 *
	 * @see Data mode constants in psk_const.php
	 *
	 * @param integer $par_VisibleViewMode   Determines the view mode when this
	 *                                       command can be visible.
	 *
	 * @see View mode constants in psk_const.php
	 *
	 * @param bool    $par_RenderInTitleCell Determines the place of the command.
	 *                                       If true, command get rendered in the title cell of the column, othervise
	 *                                       command gets rendered in the data cells of the column
	 */
	function AddCommand($par_CommandText, $par_TargetMethod,
	                    $par_VisibleDataMode = PSK_DM_VIEW, $par_VisibleViewMode = PSK_VM_LIST,
	                    $par_RenderInTitleCell = false)
	{
		$this->_commands
		[$par_VisibleDataMode]
		[$par_VisibleViewMode]
		[(int)$par_RenderInTitleCell]
		[$par_TargetMethod] = $par_CommandText;
	}

	/**
	 * Add default edit commands. Insert, update and delete. This commands
	 * changes only view mode and data mode, does not affects data.
	 */
	function AddEditCommands()
	{
		$this->AddCommand(PSK_STR_DBE_ADD, 'insert', PSK_DM_VIEW, PSK_VM_LIST, true);
		$this->AddCommand(PSK_STR_DBE_EDIT, 'update');
		$this->AddCommand(PSK_STR_DBE_DELETE, 'delete');
	}

	/**
	 * Add default post commands. Insert, update nad delet This commands may
	 * change data.
	 */
	function AddPostCommands()
	{
		$this->AddCommand(PSK_STR_DBE_SAVE, 'doinsert', PSK_DM_INSERT, PSK_VM_FORM);
		$this->AddCommand(PSK_STR_DBE_CANCEL, 'cancel', PSK_DM_INSERT, PSK_VM_FORM);
		$this->AddCommand(PSK_STR_DBE_SAVE, 'doupdate', PSK_DM_EDIT, PSK_VM_FORM);
		$this->AddCommand(PSK_STR_DBE_CANCEL, 'cancel', PSK_DM_EDIT, PSK_VM_FORM);
		$this->AddCommand(PSK_STR_DBE_DELETE, 'dodelete', PSK_DM_DELETE, PSK_VM_FORM);
		$this->AddCommand(PSK_STR_DBE_CANCEL, 'cancel', PSK_DM_DELETE, PSK_VM_FORM);
	}

	/**
	 * Add select command. This command changes the data mode of reletad table
	 * to PSK_DM_SELECTED
	 */
	function AddSelectCommand()
	{
		$this->AddCommand(PSK_STR_DBE_SELECT, 'select');
	}

	/**
	 * Renders the commands.
	 *
	 * @param integer $par_DataMode          Identify the data mode for the commands will
	 *                                       get rendered.
	 *
	 * @see Data mode constants in psk_const.php
	 *
	 * @param integer $par_ViewMode          Identify the view mode for the commands will
	 *                                       get rendered.
	 *
	 * @see View mode constants in psk_const.php
	 *
	 * @param bool    $par_RenderInTitleCell Select the the commands will
	 *                                       get rendered for title cell.
	 */
	private function _RenderCommands($par_DataMode = PSK_DM_VIEW,
	                                 $par_ViewMode = PSK_VM_LIST, $par_RenderInTitleCell = false)
	{
		if (array_key_exists($par_DataMode, $this->_commands)) {
			if (array_key_exists($par_ViewMode, $this->_commands[$par_DataMode])) {
				if (array_key_exists((int)$par_RenderInTitleCell,
					$this->_commands[$par_DataMode][$par_ViewMode])
				) {
					$commands = $this->_commands[$par_DataMode][$par_ViewMode]
					[(int)$par_RenderInTitleCell];

					//PSK_Log::getInstance()->WriteArray($commands);

					$c = '';
					foreach ($commands as $command => $commandText) {
						//if ($par_RenderInTitleCell) $this->value = -1;
						if (trim($this->value) == '') $this->value = -1;

						$c .= '<button onclick="'.
							PSK_Uri::getInstance()->PostHref($this->__parent->getName(),
								$command, array($this->getName() . '=' . $this->value)).
							'" class="'.$this->_classes[$command].'" >'.$commandText.'</button>';
					}

					return $c;
				}
			}
		}
		return '';
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_DBEC_Base::RenderValue()
	 */
	function RenderValue($par_DataMode = PSK_DM_VIEW,
	                     $par_ViewMode = PSK_VM_LIST)
	{
		if (!$this->visible) return;

		$this->__OnRender($this->valueTag, $this->onValueRender, $par_DataMode);

		$this->valueTag->inner = $this->_RenderCommands(
			$par_DataMode, $par_ViewMode);

		return $this->valueTag->Render();
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_DBEC_Base::RenderTitle()
	 */
	function RenderTitle($par_DataMode = PSK_DM_VIEW,
	                     $par_ViewMode = PSK_VM_LIST)
	{
		if (!$this->visible) return;

		$this->__OnRender($this->titleTag, $this->onTitleRender, $par_DataMode);

		$this->titleTag->inner = $this->_RenderCommands(
			$par_DataMode, $par_ViewMode, true);

		return $this->titleTag->Render();
	}
}