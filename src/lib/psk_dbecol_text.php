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
 * PSK_DBEC_Text class.
 *
 * This class renders column data as plain text and uses textboxes for
 * editing.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database and UI integration controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBEC_Text class documentation link.
 */
class PSK_DBEC_Text extends PSK_DBEC_Base
{
	private $_type = PSK_IT_TEXT;

	public $required = false;
	public $placeHolder = '';
	public $pattern = '';

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
				$r = '';
				if ($this->required) $r = ' required ';
				$h = '';
				if ($this->placeHolder != '') $h = ' placeholder="' . $this->placeHolder . '" ';
				$p = '';
				if ($this->pattern != '') $p = ' pattern="' . $this->pattern . '" ';

				$this->valueTag->inner = $this->readOnly ?
					"<div class=\"wrap\">" . $this->value . "</div>" :
					"<div class=\"wrap\">" .
					"<input type=\"".$this->_type."\" name=\"" . $this->__id .
					"\" id=\"" . $this->__id . "\" value=\"" . $this->value .
					"\" class=\"dbtext\"".$r.$p.$h."/></div>";
				break;
			default:
				;
				break;
		}

		return $this->valueTag->Render();
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