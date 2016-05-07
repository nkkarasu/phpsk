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
 * PSK_UI_Login class.
 *
 * Login box control.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_UI_Login class documentation link
 */

class PSK_UI_Login extends PSK_UI_Base
{
	const html_LoginBox =
		'
	<div id="%s" class="%s">
		<div class="titlebar">%s</div>
		<div class="data">
			<label class="username">%s</label>
			<div class="input-wrap">
				<input type="text" name="%s" />
			</div>
			<label class="password">%s</label>
			<div class="input-wrap">
				<input type="password" name="%s" />
			</div>
		</div>
		<div class="actions">%s</div>
	</div>
	';

	const html_LoggedInBox =
		'
	<div id="%s" class="%s">
		<div class="titlebar">%s</div>
		<div class="data"><div>%s</div></div>
		<div class="actions">%s</div>
	</div>
	';

	/**
	 * Enables control to validate user name and password automaticaly or not.
	 * If self valdate is set to false, you should define an onLogin event
	 * handler.
	 *
	 * @var boolean
	 */
	public $selfValidate = true;

	/**
	 * <strong>Event </strong>The name of the function which will be called
	 * after a login event occurs.
	 *
	 * @var string
	 */
	public $onLogin = '';

	/**
	 * <strong>Event </strong>The name of the function which will be called
	 * after a logout event occurs.
	 *
	 * @var string
	 */
	public $onLogout = '';

	/**
	 * Starts the authentication with given credentials.
	 */
	function login()
	{
		$name = isset($_POST[$this->__objectName . '_name']) ?
			$_POST[$this->__objectName . '_name'] : '';
		$key = isset($_POST[$this->__objectName . '_key']) ?
			$_POST[$this->__objectName . '_key'] : '';
		$args = array('name' => $name, 'key' => $key);

		$error = false;
		if ($name == '') {
			PSK_Log::getInstance()->WriteLog(PSK_STR_LC_USERREQUIRED,
				PSK_ET_APPERROR);
			$error = true;
		}
		if ($key == '') {
			PSK_Log::getInstance()->WriteLog(PSK_STR_LC_PASSWORDREQUIRED,
				PSK_ET_APPERROR);
			$error = true;
		}

		if ($this->selfValidate && !$error) {
			if (!PSK_Application::getInstance()->auth->Authenticate($name, $key)) {
				PSK_Log::getInstance()->WriteLog(PSK_STR_LC_WRONGCREDENTIALS,
					PSK_ET_APPERROR);
			}
			else {
				if ($this->onLogin != '')
					$this->CallOwnerMethod($this->onLogin, $args);
			}
		}

		if ($error) return;

		//PSK_Log::getInstance()->WriteDebug('Login executed.');
		//PSK_Log::getInstance()->WriteArray($args);
	}

	/**
	 * Terminates the authentication.
	 **/
	function logout()
	{
		if ($this->selfValidate)
			PSK_Application::getInstance()->auth->EndAuthentication();

		if ($this->onLogout != '')
			$this->CallOwnerMethod($this->onLogout, array());
	}

	/**
	 * Initializes the login control.
	 */
	function Initialize()
	{
		if ($this->__initialized) return;
		if ((!is_object(PSK_Application::getInstance()->auth)) &&
			($this->selfValidate)
		) {
			throw new Exception(PSK_STR_LC_NOAUTH);
		}
		$this->__initialized = true;
	}

	/**
	 * Renders the html output of login control.
	 */
	function Render()
	{
		if ($this->__ready) return;

		if (PSK_Application::getInstance()->auth->isAuthUser()) {
			$actionButton = '<button onclick="'.
				PSK_Uri::getInstance()->PostHref($this->__objectName, 'logout').
				'">Oturum Kapat</button>';

			$this->__output = sprintf(self::html_LoggedInBox,
				$this->__objectName, $this->__cssClass, PSK_STR_LC_TITLEOPEN,
				sprintf(PSK_STR_LC_LOGGEDINAS,
					PSK_Application::getInstance()->auth->UserName()),
				$actionButton);
		} else {
			$actionButton = '<button onclick="'.
				PSK_Uri::getInstance()->PostHref($this->__objectName, 'login').
				'">Oturum Aç</button>';
			$this->__output = sprintf(self::html_LoginBox, $this->__objectName,
				$this->__cssClass, PSK_STR_LC_TITLE, PSK_STR_LC_USER,
				$this->__objectName . '_name', PSK_STR_LC_PASSWORD,
				$this->__objectName . '_key', $actionButton);
		}

		$this->__ready = true;
	}

	/**
	 * Exports the state of login control.
	 */
	function ExportState()
	{
		;
	}
}

?>
