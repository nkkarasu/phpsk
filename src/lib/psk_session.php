<?php

/**
 * PSK
 *
 * An open source PHP web application development framework.
 *
 * @package    PSK (PHP Sınıf Kütüphanesi)
 * @author     Namık Kemal Karasu
 * @copyright  Copyright (C) Namık Kemal Karasu
 * @license    GPLv3
 * @since      Version 0.
 * @link       http://nkkarasu.net/psk/
 * @link       http://code.google.com/p/phpsk/
 */

/**
 * PSK_Session class.
 *
 * Session management object of the PSK Application.
 *
 * @package     PSK
 * @subpackage  Libraries
 * @category    Session
 * @author      Namık Kemal Karasu
 * @link        TODO add PSK_Session class documentation link.
 */
class PSK_Session extends PSK_Object
{
	/**
	 * Singleton instance.
	 *
	 * @var PSK_Session
	 */
	protected static $__instance = null;

	/**
	 * State of the session.
	 *
	 * @see psk_const.php for available values under <b>Session states</b>.
	 * @var integer
	 */
	private $_sessionState = PSK_SS_CLOSED;

	/**
	 * Constructor of PSK_Session.
	 *
	 * @param string $par_Name A name for the object.
	 */
	function  __construct($par_Name = '')
	{
		parent::__construct($par_Name);
	}

	/**
	 * Returns singleton instance of PSK_Session.
	 *
	 * @return PSK_Session
	 */
	public static function getInstance()
	{
		if (null === self::$__instance) {
			self::$__instance = new self();
		}
		return self::$__instance;
	}

	/**
	 * Starts the session.
	 *
	 */
	function Start()
	{
		if ($this->_sessionState === PSK_SS_CLOSED) {
			session_start();
			$this->_sessionState = PSK_SS_OPEN;
		}
	}

	/**
	 * Destroys the session.
	 *
	 */
	function Stop()
	{
		if ($this->_sessionState === PSK_SS_OPEN) {
			session_destroy();
			$this->_sessionState = PSK_SS_CLOSED;
		}
	}

	/**
	 * Validates a value or a value array against the SESSION array.
	 *
	 * @param array|string $par_Value The value or value array which will be
	 *                                validated.
	 * @param <type> $par_Section Top level array key of the value.
	 *
	 * @return boolean
	 */
	function Validate($par_Value, $par_Section = '')
	{
		if (is_array($par_Value)) {
			foreach ($par_Value as $key => $value) {
				if ($par_Section == '') {
					if (isset($_SESSION[$key])) {
						if ($_SESSION[$key] != $value) return false;
					} else return false;
				} else {
					if (isset($_SESSION[$par_Section][$key])) {
						if ($_SESSION[$par_Section][$key] != $value) return false;
					} else return false;
				}
			}
			return true;
		} else {
			if (isset($_SESSION[$par_Section])) {
				return $_SESSION[$par_Section] == $par_Value;
			}
			return false;
		}
	}

	/**
	 * Writes a value or value array to SESSION.
	 *
	 * @param array|string $par_Value The value or value array which will be
	 *                                written into SESSION.
	 * @param <type> $par_Section Top level array key of the value.
	 */
	function Write($par_Value, $par_Section = '')
	{
		if (is_array($par_Value)) {
			foreach ($par_Value as $key => $value) {
				if ($par_Section == '') {
					$_SESSION[$key] = $value;
				} else {
					$_SESSION[$par_Section][$key] = $value;
				}
			}
		} else {
			$_SESSION[$par_Section] = $par_Value;
		}
	}

	/**
	 * Reads a value from SESSION.
	 *
	 * @param  <type> $par_Key Session key of the value.
	 * @param  <type> $par_Section Top level session key of the value.
	 *
	 * @return <type>
	 */
	function Read($par_Key, $par_Section = '')
	{
		if ($par_Section == '') {
			if (isset($_SESSION[$par_Key])) {
				return $_SESSION[$par_Key];
			} else return false;
		} else {
			if (isset($_SESSION[$par_Section][$par_Key])) {
				return $_SESSION[$par_Section][$par_Key];
			} else return false;
		}
	}

	/**
	 * Deletes a key or key array from the SESSION.
	 *
	 * @param <type> $par_Key Key or Keys to delete.
	 * @param <type> $par_Section Top level key of the deleting key or keys.
	 */
	function Delete($par_Key, $par_Section = '')
	{
		if (is_array($par_Key)) {
			foreach ($par_Key as $key) {
				if ($par_Section == '') {
					if (isset($_SESSION[$key])) {
						$_SESSION[$key] = '';
						unset($_SESSION[$key]);
					}
				} else {
					if (isset($_SESSION[$par_Section][$key])) {
						$_SESSION[$par_Section][$key] = '';
						unset($_SESSION[$par_Section][$key]);
					}
				}
			}
		} else {
			if ($par_Section == '') {
				if (isset($_SESSION[$par_Key])) {
					$_SESSION[$par_Key] = '';
					unset($_SESSION[$par_Key]);
				}
			} else {
				if (isset($_SESSION[$par_Section][$par_Key])) {
					$_SESSION[$par_Section][$par_Key] = '';
					unset($_SESSION[$par_Section][$par_Key]);
				}
			}
		}
	}
}
