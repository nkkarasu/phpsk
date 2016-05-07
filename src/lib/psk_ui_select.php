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
 * PSK_UI_Select class.
 *
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_UI_Select class documentation link
 */

class PSK_UI_Select extends PSK_UI_Base
{
	/**
	 * <strong>Event </strong>The name of the function which will be called
	 * after a selection event occurs.
	 *
	 * @var string
	 */
	public $onChange = '';

	/**
	 * Options wich will be listed in select control.
	 *
	 * @var array
	 */
	private $_options = array();

	/**
	 * Key value of the selected option.
	 *
	 * @var <type>
	 */
	private $_selected;

	/**
	 * If set true when a selection change occurs, the form will get submitted
	 * to server and onChange event handler will be called.
	 *
	 * @var boolean.
	 */
	private $_autoPostBack = false;

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Initialize()
	 */
	function Initialize()
	{
		$this->_selected = PSK_Application::getInstance()->ReadControlState(
			$this->__objectName . '_selected', $this->_selected,
			$this->__stateStorage);
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Render()
	 */
	function Render()
	{
		if ($this->__ready) return;

		$c = $this->_autoPostBack ? " onchange=\"" .
			PSK_Uri::getInstance()->MethodScript($this->getName(),
				'OptionChanged') . "\" " : "";

		$this->__Write("\n<select id=\"" . $this->__objectName . "\" name=\"" .
			$this->__objectName . "\" class=\"" . $this->__cssClass . "\"" . $c . ">\n");

		foreach ($this->_options as $value => $text) {
			$s = $value == $this->_selected ? " selected = \"selected\" " : "";
			$this->__Write("\t<option value=\"" . $value . "\"" . $s . ">");
			$this->__Write($text . "</option>\n");
		}

		$this->__Write("\n</select>\n");
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::ExportState()
	 */
	function ExportState()
	{
		PSK_Application::getInstance()->WriteControlState(
			$this->__objectName . '_selected', $this->_selected,
			$this->__stateStorage);
	}

	/**
	 * Sets the options of the select element.
	 *
	 * @param array $par_Options The options of the select element.
	 * @param <type> $par_Selected Selected option for the select element.
	 */
	function setOptions(array $par_Options, $par_Selected = '')
	{
		$this->_options = $par_Options;
		$this->_selected = $par_Selected;
	}

	/**
	 * Sets the options of the select element via database query. If you provide
	 * a query whic has only one field, values of that filed will be used as
	 * option texts; if you prowide a query more than one field, the firs field
	 * will be used as value properties of the option elements and second field
	 * will be used as texts of the option elemnts, other fields will be ignored.
	 *
	 * @param PSK_DBQuery_Base $par_Query
	 * @param <type> $par_Selected Selected option for the select element.
	 */
	function setOptionsFromQuery(PSK_DBQuery_Base $par_Query, $par_Selected = '')
	{
		$opts = array();
		if ($par_Query->FieldCount() == 1) {
			while ($rec = $par_Query->FetchNum()) {
				$opts[] = $rec[0];
			}
		} elseif ($par_Query->FieldCount() > 1) {
			while ($rec = $par_Query->FetchNum()) {
				$opts[$rec[0]] = $rec[1];
			}
		}
		$this->setOptions($opts, $par_Selected);
	}

	/**
	 * Sets the auto post back state of the control. If set true when user makes
	 * a choice from options, page will posted back to the server and
	 * OptionChange event of the control will be fired.
	 *
	 * @param boolean $par_AutoPostBackState
	 */
	function setAutoPostBack($par_AutoPostBackState)
	{
		$this->_autoPostBack = $par_AutoPostBackState;
	}

	/**
	 * Sets the first option as selected option.
	 */
	function setFirstActive()
	{
		if (count($this->_options) > 0) {
			$keys = array_keys($this->_options);
			$this->_selected = $keys[0];
		}
	}

	/**
	 * Returns the selected options value.
	 *
	 * @return <type>
	 */
	function getSelectedValue()
	{
		if ($this->_selected == '') $this->setFirstActive();
		return $this->_selected;
	}

	/**
	 * Returns the count of options in select list.
	 *
	 * @return number
	 */
	function getOptionCount()
	{
		return count($this->_options);
	}

	/**
	 * If AutoPostBack is enabled, when a change occurs on select control
	 * this function will be called.
	 */
	function OptionChanged()
	{
		if (isset($_POST[$this->__objectName]) && ($_POST[$this->__objectName] != '')) {
			$this->_selected = $_POST[$this->__objectName];
		}

		if ($this->onChange != '') {
			$args = array('selected' => &$this->_selected);
			$this->CallOwnerMethod($this->onChange, $args);
		}
	}
}