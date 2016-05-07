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
 * PSK_DBEC_Memo class.
 *
 * This class renders column data as plain text and uses textareas for
 * editing.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database and UI integration controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBEC_Memo class documentation link.
 */
class PSK_DBEC_Memo extends PSK_DBEC_Base
{
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
				$this->valueTag->inner =
					"<div class=\"wrap\"><textarea class=\"dbmemo\" name=\"" .
						$this->__id . "\" id=\"" . $this->__id . "\" rows=\"5\">" .
						$this->value . "</textarea></div>";
				break;
			default:
				;
				break;
		}

		return $this->valueTag->Render();
	}
}