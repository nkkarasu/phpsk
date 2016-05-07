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
 * PSK_Object class.
 *
 * Base class for all PSK objects.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Base
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Object class documentation link
 */
class PSK_Object
{
	/**
	 * Object name
	 *
	 * @var string
	 */
	protected $__objectName = '';

	/**
	 * Constructor accepts an optional name for object.
	 *
	 * @param string $par_Name A name for the object.
	 */
	function __construct($par_Name = '')
	{
		$this->__objectName = $par_Name;
	}

	/**
	 * Returns the name of the object.
	 *
	 * @return string
	 */
	function getName()
	{
		return $this->__objectName;
	}

	/**
	 * Sets the name of the object.
	 *
	 * @param string $par_Name
	 */
	function setName($par_Name)
	{
		$this->__objectName = $par_Name;
	}

}

/**
 * PSK_OwnedObject class.
 *
 * Base class for objects that needs to access it's owner object.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Base
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Object class documentation link
 */
class PSK_OwnedObject extends PSK_Object
{
	protected $__owner = null;

	/**
	 * Constructor of the PSK_OwnedObject class.
	 *
	 * @param object $par_Owner The object that owns this object.
	 * @param string $par_Name  A name for the object.
	 */
	function __construct($par_Owner, $par_Name = '')
	{
		$this->__owner = $par_Owner;
		parent::__construct($par_Name);
		//PSK_Log::getInstance()->WriteDebug($this->getName().' : '.
		//	$this->__owner->getName());
	}

	/**
	 * Calls a specific method of the owner class.
	 * This method added to support event driven programming.
	 *
	 * @param string $par_MethodName Method name which will be invoked.
	 * @param array  $par_Args       Arguments to be passed to method.
	 *
	 * @return <type> Specific to owner's method.
	 */
	function CallOwnerMethod($par_MethodName, array $par_Args = array())
	{
		if (trim($par_MethodName) === '') {
			return false;
		}
		if (method_exists($this->__owner, $par_MethodName)) {
			return $this->__owner->$par_MethodName($this, $par_Args);
		} else {
			throw new Exception(sprintf(PSK_STR_ERR_NOOWNERMETHOD,
				$par_MethodName));
		}
	}
}
