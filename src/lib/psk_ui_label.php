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
 * PSK_UI_Label class.
 *
 * A simple class for displaying texts.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_UI_Label class documentation link
 */

class PSK_UI_Label extends PSK_UI_Base
{
	/**
	 * The text will be rendered in page.
	 *
	 * @var string
	 */
	private $_text = '';

	/**
	 * Style property of the surroundig tag.
	 *
	 * @var string
	 */
	private $_cssStyle = '';

	/**
	 * DEPRACETED
	 * Determines that text will be surrounding with <b>div</b> or
	 * <b>span</b> tag.
	 *
	 * @var boolean
	 */
	private $_div = false;

	/**
	 * Determines the tag which will contains the label text.
	 * @var string
	 */
	private $_tag = 'span';

	/**
	 * This method called by owner controller of the control. This method
	 * initializes control.
	 */
	function Initialize() { ; }

	/**
	 * This method called by owner controller of the control. This method
	 * generates the output of the control.
	 */
	function Render()
	{
		if ($this->__ready) return;

		$format = '';
		if ($this->__cssClass != '') $format = ' class="' . $this->__cssClass . '" ';
		if ($this->_cssStyle != '') $format .= ' style="' . $this->_cssStyle . '" ';
		$head = "<".$this->_tag;
		$tail = "</".$this->_tag.">\n";
		$this->__output = $head . $format . ">" . $this->_text . $tail;

		$this->__ready = true;
	}

	/**
	 * This method called by owner controller of the control. This
	 * method saves the state of the control.
	 */
	function ExportState() { ; }

	/**
	 * Sets the text property of the control.
	 *
	 * @param string $par_Text
	 */
	function setText($par_Text)
	{
		$this->_text = $par_Text;
	}

	/**
	 * Sets the style property of the control.
	 *
	 * @param string $par_CssStyle
	 */
	function setCssStyle($par_CssStyle)
	{
		$this->_cssStyle = $par_CssStyle;
	}

	/**
	 * DEPRACATED
	 * Sets the div property of the control.
	 * NOTE: Stil stays and edited for backwards compatibility.
	 *
	 * @param boolean $par_DivStatus
	 */
	function setDiv($par_DivStatus)
	{
		if ($par_DivStatus) {
			$this->_tag = 'div';
		}
		else {
			$this->_tag = 'span';
		}
		$this->_div = $par_DivStatus;
	}

	/**
	 * Determines the tag which will contains the label text.
	 * @param string $parTag
	 */
	function setTag ($parTag)
	{
		$this->_tag = $parTag;
	}
}

?>
