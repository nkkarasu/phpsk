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
 * PSK_UI_Text class.
 *
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_UI_Memo class documentation link
 */

class PSK_UI_Memo extends PSK_UI_Base
{
	/**
	 * The text will be shown in memo control.
	 *
	 * @var string
	 */
	private $_data = '';

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Initialize()
	 */
	function Initialize()
	{
		if (PSK_Uri::getInstance()->isPost()) {
			if (isset($_POST[$this->__objectName]))
				$this->_data = $_POST[$this->__objectName];
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Render()
	 */
	function Render()
	{
		if ($this->__ready) return;
		//<textarea id="ozet" name="ozet" class="metin"  rows="7" >
		$this->__Write("\n<textarea id=\"" .
			$this->__objectName . "\" name=\"" . $this->__objectName . "\" class=\"" .
			$this->__cssClass . "\"/>");
		$this->__Write($this->_data);
		$this->__Write("</textarea>\n");
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::ExportState()
	 */
	function ExportState()
	{
	}

	/**
	 * Assigns the data property of the control.
	 *
	 * @param string $par_Data Value of the data proeprty.
	 */
	function setData($par_Data)
	{
		$this->_data = $par_Data;
	}

	/**
	 * Returns the value of the memo control.
	 *
	 * @return string;
	 */
	function getData()
	{
		return $this->_data;
	}
}
