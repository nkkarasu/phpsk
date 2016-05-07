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
 * PSK_Tags class.
 *
 * This class includes static helper methods for HTML tags.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database and UI integration controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Tags class documentation link.
 */
class PSK_Tags
{
	/**
	 * Generates an anchor [a] tag.
	 *
	 * @param string $par_Inner The inner part of the tag.
	 * @param string $par_Href  Reference link for the anchor.
	 *
	 * @return string
	 */
	static function Anchor($par_Inner, $par_Href = '#', $par_Title = '')
	{
		$title = $par_Title == '' ? '' : ' title="'.$par_Title.'" ';
		return '<a '.$title.' href="' . $par_Href . '">' . $par_Inner . '</a>';
	}

	static function Image($par_Src)
	{
		return "<img scr=\"" . $par_Src . "\" />";
	}
}