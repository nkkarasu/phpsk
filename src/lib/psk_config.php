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
 * PSK_Config class.
 *
 * Configuration loader class.
 * This class takes an array as input and outputs array's items as
 * it's properties. Also configures other objects wiht their setter methods.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Configuration
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Config class documentation link.
 */

class PSK_Config extends PSK_Object
{
	/**
	 * Singleton instance of PSK_Config.
	 *
	 * @var PSK_Config
	 */
	protected static $__instance = null;

	/**
	 * Configuration options array.
	 *
	 * @var array
	 */
	private $_configArray = null;

	/**
	 * Defines active section for to get sub config options as properties.
	 *
	 * @var Specific to option.
	 */
	private $_activeSection;

	/**
	 * Contructor of PSK_Config.
	 *
	 * @param string $par_Name A name for the object.
	 */
	function  __construct($par_Name = '')
	{
		parent::__construct($par_Name);
	}

	/**
	 * Returns a whole section or an option from the configuration array.
	 *
	 * @param  <type> $par_Option Defines the option index or key or section
	 * index or key.
	 *
	 * @return <type>
	 */
	function __get($par_Option)
	{
		if (isset($this->_activeSection)) {
			return $this->_ArrayItem($this->_configArray[$this->_activeSection], $par_Option);
		}
		return $this->_ArrayItem($this->_configArray, $par_Option);
	}

	/**
	 * Returns singleton instance of PSK_Config.
	 *
	 * @return PSK_Config
	 */
	public static function getInstance()
	{
		if (null === self::$__instance) {
			self::$__instance = new self();
		}
		return self::$__instance;
	}

	/**
	 * Returns an item of an array which is defined by par_Key parameter.
	 *
	 * @param array $par_Array
	 * @param  <type> $par_Key
	 *
	 * @return <type>
	 */
	private function _ArrayItem(array $par_Array, $par_Key)
	{
		if (array_key_exists($par_Key, $par_Array)) {
			return $par_Array[$par_Key];
		} else {
			throw new Exception(PSK_STR_CONF_INVALIDOPT . ' ' . $par_Key);
		}
	}

	/**
	 * Reads the specified configuration option.
	 *
	 * @param  <type> $par_Option Defines the option index or key.
	 * @param  <type> $par_Section Defines the section index or key.
	 *
	 * @return <type>
	 */
	function ReadOption($par_Option, $par_Section = '')
	{
		if ($par_Section === '') {
			return $this->_ArrayItem($this->_configArray, $par_Option);
		} else {
			if (is_array($this->_configArray[$par_Section])) {
				return $this->_ArrayItem($this->_configArray[$par_Section],
					$par_Option);
			} else {
				throw new Exception(PSK_STR_CONF_INVALIDSECT);
			}
		}
	}

	/**
	 * Configures an object's properties. Calls only setters if available.
	 *
	 * @param <type> $par_Object Object to configure.
	 * @param array $par_Options Options array.
	 */
	function ConfigureObject($par_Object, array $par_Options)
	{
		if (array_key_exists('inherits', $par_Options)) {
			$this->ConfigureObject($par_Object, $this->ReadOption($par_Options['inherits']));
		}
		foreach ($par_Options as $key => $option) {
			if ($key == 'inherits')
				continue;

			$key[0] = strtoupper($key[0]);
			$key = 'set' . $key;
			if (method_exists($par_Object, $key)) {
				$par_Object->$key($option);
			}
		}
	}

	/**
	 * Checks if option key or index is exists in specified section or not.
	 *
	 * @param <type> $par_Option Option key or index.
	 * @param <type> $par_Section Section key or index.
	 *
	 * @return boolean
	 */
	function isOptionExist($par_Option, $par_Section = '')
	{
		if ($par_Section == '') {
			return array_key_exists($par_Option, $this->_configArray);
		}
		return array_key_exists($par_Option, $this->_configArray[$par_Section]);
	}

	/**
	 * Assigns the configuration array.
	 *
	 * @param array $par_ConfigArray Configuration array
	 */
	function setConfigArray(array &$par_ConfigArray)
	{
		$this->_configArray = & $par_ConfigArray;
	}

	/**
	 * Sets the active section for to get sub config otpions as class properties.
	 *
	 * @param <type> $par_Section Section index or key.
	 */
	function setActiveSection($par_Section)
	{
		if (array_key_exists($par_Section, $this->_configArray)) {
			$this->_activeSection = $par_Section;
		} else {
			throw new Exception(PSK_STR_CONF_INVALIDSECT);
		}
	}
}
