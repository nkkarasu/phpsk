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
 * PSK_DBEC_Select class.
 *
 * This class renders column data as a drop down list.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database and UI integration controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBEC_Combo class documentation link.
 */
class PSK_DBEC_Select extends PSK_DBEC_Base
{
	/**
	 * The array that contains selection options.
	 *
	 * @var array
	 */
	private $_options = array();

	/**
	 * (non-PHPdoc)
	 * @see PSK_DBEC_Base::RenderValue()
	 */
	function RenderValue($par_DataMode = PSK_DM_VIEW,
	                     $par_ViewMode = PSK_VM_LIST)
	{
		if (!$this->visible) return;

		$this->__OnRender($this->valueTag, $this->onValueRender, $par_DataMode);

		switch ($par_DataMode) {
			case PSK_DM_INSERT:
			case PSK_DM_EDIT:
				$o = '';
				foreach ($this->_options as $value => $option) {
					$s = $value == $this->value ? " selected=\"selected\" " : '';
					$o .= "<option value=\"" . $value . "\" " . $s . ">" . $option . "</option>";
				}
				$this->valueTag->inner =
					"<div class=\"wrap\">" .
						"<select id=\"" . $this->__id . "\" name=\"" . $this->__id . "\">" .
						$o .
						"</select></div>";
				break;
			default:
				if (array_key_exists($this->value, $this->_options)) {
					$this->valueTag->inner = $this->_options[$this->value];
				}
				break;
		}

		return $this->valueTag->Render();
	}

	function setOptions(array $par_Options)
	{
		$this->_options = $par_Options;
	}

	/**
	 * Assigns options from a query. First field in query will be used as option id
	 * and second field in the query will be used as option text, others will ignored.
	 *
	 * @param PSK_DBQuery_Base $par_Query
	 */
	function setOptionsFromQuery(PSK_DBQuery_Base $par_Query)
	{
		while ($r = $par_Query->FetchNum()) {
			$this->_options[$r[0]] = $r[1];
		}
	}

	function AddOption($par_Value, $par_Option)
	{
		$this->_options[$par_Value] = $par_Option;
	}
}