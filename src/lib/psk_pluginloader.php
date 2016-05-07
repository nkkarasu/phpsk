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
 * PSK_PluginLoader class.
 *
 * Used by PSK_Application to load plugins.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Base
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Plugged_Base class documentation link
 */

class PSK_PluginLoader extends PSK_Object
{
	/**
	 * Name of the plugin library.
	 *
	 * @var string
	 */
	private $_pluginLibrary = '';

	/**
	 * The class which will be loaded.
	 *
	 * @var string
	 */
	private $_pluginClass = '';

	/**
	 * The loaded plugin.
	 *
	 * @var <type>
	 */
	private $_plugin = null;

	/**
	 * Constructor of PSK_PluginLoader.
	 *
	 * @param string $par_Name A name for the object.
	 */
	function  __construct($par_Name = '')
	{
		parent::__construct($par_Name);
	}

	/**
	 * Loads the defined plugin. You can choose plugin class and library by
	 * using this methods parameters or using setPluginClass and
	 * setPluginLibrary.
	 *
	 * @param string $par_PluginClass
	 * @param string $par_PluginLibrary
	 */
	function LoadPlugin($par_PluginClass = '', $par_PluginLibrary = '')
	{
		if ($par_PluginClass !== '') {
			$this->_pluginClass = $par_PluginClass;
		}

		if ($par_PluginLibrary !== '') {
			$this->_pluginLibrary = $par_PluginLibrary;
		}

		if ($this->_pluginLibrary === '') {
			throw new Exception(PSK_STR_PLG_NOLIBRARY);
		}

		if ($this->_pluginClass === '') {
			throw new Exception(PSK_STR_PLG_NOCLASS);
		}

		$libPath = dirname(__FILE__);

		$pluginClass = 'PSK_' . $this->_pluginLibrary . '_' . $par_PluginClass;
		$pluginBase = $libPath . '/psk_' . strtolower($this->_pluginLibrary) . '_base.php';
		$pluginFile = $libPath . '/psk_' . strtolower($this->_pluginLibrary) . '_' .
			strtolower($this->_pluginClass) . '.php';

		if (is_file($pluginBase)) {
			require_once $pluginBase;
			if (is_file($pluginFile)) {
				require_once $pluginFile;
				if (class_exists($pluginClass)) {
					$this->_plugin =
						new $pluginClass(PSK_Application::getInstance());
				} else {
					throw new Exception(sprintf(PSK_STR_PLG_CLASSNOTIMPLEMENTED,
						$pluginClass));
				}
			} else {
				throw new Exception(sprintf(PSK_STR_PLG_CLASSFILENOTEXIST,
					$pluginFile));
			}
		} else {
			throw new Exception(sprintf(PSK_STR_PLG_BASEFILENOTEXIST,
				$pluginBase));
		}
	}

	/**
	 * Sets the plugin library.
	 *
	 * @param string $par_PluginLibrary
	 */
	function setPluginLibrary($par_PluginLibrary)
	{
		$this->_pluginLibrary = $par_PluginLibrary;
	}

	/**
	 * Sets the plugin class.
	 *
	 * @param string $par_PluginClass
	 */
	function setPluginClass($par_PluginClass)
	{
		$this->_pluginClass = $par_PluginClass;
	}

	/**
	 * Returns the loaded plugin. If no plugin loaded then returns false.
	 *
	 * @return <type>
	 */
	function getPlugin()
	{
		if (is_object($this->_plugin)) {
			return $this->_plugin;
		}
		return false;
	}
}
