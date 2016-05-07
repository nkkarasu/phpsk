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
 * PSK_Autz_Simple class.
 *
 * Simple authorization provider class. This class is checks only a module
 * requires authentication or not. To define a private module, add
 * a configuration option to your config file like
 * <code>
 * $psk_conf['authorization']['privateModule'] = '__MAIN__';
 * </code>
 * Such an option makes all controllers in the main module private. So you need
 * to define one of them as public. To do that,  add a configuration option to
 * your config file like
 * <code>
 * $psk_conf['authorization']['publicController'] = 'index';
 * </code>
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Authentication
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Autz_Simple class documentation link.
 */

class PSK_Autz_Simple extends PSK_Autz_Base
{
	/**
	 * Checks a user has authorization to access current content or not.
	 *
	 * @return boolean
	 */
	function Authorize()
	{
		$m = $this->__uri->module;
		$c = $this->__uri->controller;

		if ($m === '') {
			$m = '__MAIN__';
		}

		$secret = (($m === $this->__privateObjects) &&
			($c !== $this->__publicController));

		if (!$secret) {
			return true;
		}

		return PSK_Application::getInstance()->auth->isAuthUser();
	}

	/**
	 * Defines which module will be secured.
	 *
	 * @param string $par_PrivateModule
	 */
	function setPrivateModule($par_PrivateModule)
	{
		$this->__privateObjects = $par_PrivateModule;
	}
}

