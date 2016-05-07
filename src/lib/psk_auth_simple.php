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
 * PSK_Auth_Simple class.
 *
 * Simple authentication provider class.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Authentication
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Auth_Simple class documentation link.
 */

class PSK_Auth_Simple extends PSK_Auth_Base
{
	/**
	 * (non-PHPdoc)
	 * @see PSK_Auth_Base::__Validate()
	 */
	protected function __Validate($par_Name, $par_Key)
	{
		$ac = $this->__config->ReadOption('user', 'authentication');
		if (($par_Name === $ac['name']) && ($par_Key === $ac['key'])) {
			$this->__credential = $ac['credential'];
			$this->__fullUserName = $ac['fullUserName'];
			return true;
		}
		return false;
	}
}
