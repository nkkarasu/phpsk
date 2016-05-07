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
 * PSK_Auth_Base class.
 *
 * Base class for authentication providers.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Authentication
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Auth_Base class documentation link.
 *
 */

abstract class PSK_Auth_Base extends PSK_OwnedObject
{
	/**
	 * Determines the type of the user.
	 *
	 * @see psk_const.php for available values under <b>User types</b>.
	 * @var integer
	 */
	protected $__userType = PSK_UT_GUEST;

	/**
	 * User name that suplied by the visitor. This value is checked by
	 * PSK_Auth_Base::__Validate() to let visitor to get authenticated.
	 *
	 * @var string
	 */
	protected $__userName = '';

	/**
	 * User key(password) that suplied by the visitor. This value is checked by
	 * PSK_Auth_Base::__Validate() to let visitor to get authenticated.
	 *
	 * @var string
	 */
	protected $__userKey = '';

	/**
	 * Real name of the user.
	 *
	 * @var string
	 */
	protected $__fullUserName = '';

	/**
	 * Credential for user to get authorized.
	 *
	 * @var string
	 */
	protected $__credential = '';

	/**
	 * Session management object of the application.
	 *
	 * @var PSK_Session
	 */
	protected $__session = null;

	/**
	 * Configuration manager.
	 *
	 * @var PSK_Config
	 */
	protected $__config = null;

	/**
	 * Login page for unauthenticated user to log in.
	 *
	 * @var string
	 */
	public $loginPage = '';

	/**
	 * Constructor of the PSK_AuthBase class.
	 *
	 * @param object $par_Owner The object that owns this object.
	 * @param string $par_Name  A name for the object.
	 */
	function  __construct(PSK_Object $par_Owner, $par_Name = '')
	{
		parent::__construct($par_Owner, $par_Name);
		$this->__session = PSK_Session::getInstance();
		$this->__config = PSK_Config::getInstance();
	}

	/**
	 * Initializes the authentication class. Loads the suplied user name and
	 * user key by visitor at previous visits.
	 */
	function Initialize()
	{
		$this->__userName = $this->__session->Read('name', '__AUTH__');
		$this->__userKey = $this->__session->Read('key', '__AUTH__');
		$this->__userType = $this->__Validate($this->__userName,
			$this->__userKey) ? PSK_UT_AUTHUSER : PSK_UT_GUEST;
	}

	/**
	 * Authenticates the user with given user name and key if they are valid.
	 *
	 * @param string $par_UserName User name to get authenticated.
	 * @param string $par_UserKey  User key to get authenticated.
	 *
	 * @return boolean true on succesfull authentication, other wise false.
	 */
	function Authenticate($par_UserName, $par_UserKey)
	{
		if ($this->__Validate($par_UserName, $par_UserKey)) {
			$this->__userName = $par_UserName;
			$this->__userKey = $par_UserKey;
			$this->__userType = PSK_UT_AUTHUSER;
			$auth = array('name' => $par_UserName, 'key' => $par_UserKey);
			$this->__session->Write($auth, '__AUTH__');
			return true;
		}
		return false;
	}

	/**
	 * Ends the authentication.
	 */
	function EndAuthentication()
	{
		$this->__userName = '';
		$this->__userKey = '';
		$this->__userType = PSK_UT_GUEST;
		$this->__session->Delete('__AUTH__');
	}

	/**
	 * Returns the authentication status of the user.
	 *
	 * @return boolean true if user is an authenticated user, other wise false.
	 */
	function isAuthUser()
	{
		return $this->__userType === PSK_UT_AUTHUSER;
	}

	/**
	 * Returns full user name if it has been set, otherwise returns user name.
	 *
	 * @return string
	 */
	function UserName()
	{
		if ($this->__fullUserName !== '') {
			return $this->__fullUserName;
		}
		return $this->__userName;
	}

	/**
	 * Cheks a user name and key if they are valid or not.
	 *
	 * @param string $par_Name User name to get validated.
	 * @param string $par_Key  Password to get validated.
	 */
	abstract protected function __Validate($par_Name, $par_Key);

	function setLoginPage($par_LoginPage)
	{
		$this->loginPage = $par_LoginPage;
	}
}
