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
 * PSK_DBEC_File class.
 *
 * This class renders column data as a file input.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database and UI integration controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBEC_File class documentation link.
 */
class PSK_DBEC_File extends PSK_DBEC_Base
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
				$this->valueTag->inner =
					"<div class=\"wrap\">" .
						"<input type=\"file\" name=\"" . $this->__id .
						"\" id=\"" . $this->__id . "\" class=\"dbfile\" />" .
						"</div>";
				break;
			default:
				;
				break;
		}

		return $this->valueTag->Render();
	}

	function ReadFromPost()
	{
		if (isset($_FILES[$this->__id]) &&
			($_FILES[$this->__id] != '') &&
			($_FILES[$this->__id]['error'] == 0)
		) {
			return $_FILES[$this->__id]['name'];
		}
		return '';
	}
}