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
 * PSK_Autz_Base class.
 *
 * Base class for authorization management.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Authorization
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Autz_Base class documentation link.
 */

abstract class PSK_Autz_Base extends PSK_OwnedObject
{
	/**
	 * Name of the login controller.
	 *
	 * @var string
	 */
	protected $__publicController = '';

	/**
	 * Public object or objects which does not requires or don't require
	 * authorization.
	 *
	 * @var <type>
	 */
	protected $__publicObjects = null;

	/**
	 * Secured object or objects which requires or require authorization.
	 *
	 * @var <type>
	 */
	protected $__privateObjects = null;

	/**
	 * Uri management object.
	 *
	 * @var PSK_Uri
	 */
	protected $__uri = null;

	/**
	 * Constructor of the PSK_AutzBase class.
	 *
	 * @param object $par_Owner The object that owns this object.
	 * @param string $par_Name  A name for the object.
	 */
	function __construct(PSK_Object $par_Owner, $par_Name = '')
	{
		parent::__construct($par_Owner, $par_Name);
		$this->__uri = PSK_Uri::getInstance();
	}

	/**
	 * Sets the name of the public controller.
	 *
	 * @param <type> $par_PublicObjects
	 */
	function setPublicObjects($par_PublicObjects)
	{
		$this->__publicObjects = $par_PublicObjects;
	}

	/**
	 * Sets the private objects.
	 *
	 * @param <type> $par_PrivateObjects
	 */
	function setPrivateObjects($par_PrivateObjects)
	{
		$this->__privateObjects = $par_PrivateObjects;
	}

	/**
	 * Defines login page.
	 *
	 * @param string $par_PublicController
	 */
	function setPublicController($par_PublicController)
	{
		$this->__publicController = $par_PublicController;
	}

	function getPublicCotroller()
	{
		return $this->__publicController;
	}

	/**
	 * Checks a user has authorization to access current content or not.
	 */
	abstract function Authorize();
}
