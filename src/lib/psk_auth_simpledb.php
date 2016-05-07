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
 * PSK_Auth_SimpleDB class.
 *
 * Simple database authentication provider class.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Authentication
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Auth_SimpleDB class documentation link.
 */

class PSK_Auth_SimpleDB extends PSK_Auth_Base
{
	/**
	 * (non-PHPdoc)
	 * @see PSK_Auth_Base::__Validate()
	 */
	protected function __Validate($par_Name, $par_Key)
	{
		$ac = $this->__config->ReadOption('params', 'authentication');

		$q = PSK_Application::getInstance()->db->Query(
			'SELECT ' . $ac['userField'] . ', ' . $ac['passwordField'] . ' FROM ' .
				$ac['authTable']);

		if ($q->RowCount() == 0) {
			//throw new Exception(PSK_STR_A_NODATA);
			return false;
		}

		$r = $q->FetchAssoc();

		if ((sha1($par_Name) === $r[$ac['userField']]) &&
			(sha1($par_Key) === $r[$ac['passwordField']])
		) {
			$this->__credential = $ac['credential'];
			$this->__fullUserName = $ac['fullUserName'];
			return true;
		}

		return false;
	}

}
