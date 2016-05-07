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
 * @link           TODO add PSK_UI_Text class documentation link
 */

class PSK_UI_Text extends PSK_UI_Base
{
	/**
	 * The text will be shown in textbox control.
	 *
	 * @var string
	 */
	private $_text = '';

	/**
	 * Detemines type of text control.
	 *
	 * @var string
	 */
	private $_type = PSK_IT_TEXT;

	public $required = false;
	public $placeHolder = '';
	public $pattern = '';
	public $params = '';

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Initialize()
	 */
	function Initialize()
	{
		if (PSK_Uri::getInstance()->isPost()) {
			if (isset($_POST[$this->__objectName]))
				$this->_text = $_POST[$this->__objectName];
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Render()
	 */
	function Render()
	{
		if ($this->__ready) return;

		$r = ''; if ($this->required) $r = ' required ';
		$h = ''; if ($this->placeHolder != '') $h = ' placeholder="'.$this->placeHolder.'" ';
		$p = ''; if ($this->pattern != '') $p = ' pattern="'.$this->pattern.'" ';

		$this->__Write("\n<input type=\"" . $this->_type . "\" id=\"" .
			$this->__objectName . "\" name=\"" . $this->__objectName . "\" class=\"" .
			$this->__cssClass . "\" value=\"" . $this->_text . "\"".
			$r.$h.$p.$this->params."/>\n");

	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::ExportState()
	 */
	function ExportState()
	{
	}

	/**
	 * Assigns the text property of the control.
	 *
	 * @param string $par_Text Value of the text proeprty.
	 */
	function setText($par_Text)
	{
		$this->_text = $par_Text;
	}

	/**
	 * Returns the value of the textbox control.
	 *
	 * @return string;
	 */
	function getText()
	{
		return $this->_text;
	}

	/**
	 * Assigns the type property.
	 *
	 * @param string $par_Type
	 */
	function setType($par_Type)
	{
		$this->_type = $par_Type;
	}
}

?>
