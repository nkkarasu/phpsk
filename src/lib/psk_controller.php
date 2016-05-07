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
 * PSK_Controller class.
 *
 * Base class for controllers.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Controllers
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Controller class documentation link.
 */

abstract class PSK_Controller extends PSK_OwnedObject
{
	/**
	 * Logging class of the application.
	 *
	 * @var PSK_Log
	 */
	public $log = null;

	/**
	 * The class which responsible configuration of everything in PSK.
	 *
	 * @var PSK_Config
	 */
	public $config = null;

	/**
	 * Uri and request handling class.
	 *
	 * @var PSK_Uri
	 */
	public $uri = null;

	/**
	 * Session management object of the application.
	 *
	 * @var PSK_Session
	 */
	public $session = null;

	/**
	 * Layout manager of the application.
	 *
	 * @var PSK_Layout
	 */
	public $layout = null;

	/**
	 * The object which handles database stuff. This is a plugin object. So
	 * You need to specify which plugin to use to connect the database. To do
	 * that add a configuration option to your config array like
	 * <code>$psk_conf['application']['dbClass'] = 'MySQL'</code> or call
	 * <code>setDbClass</code> method of PSK_Application.
	 *
	 * @var PSK_DBDriver_Base
	 */
	public $db = null;

	/**
	 * Contains the objects which will be managed by this controller.
	 *
	 * @var array
	 */
	private $_objects = array();

	/**
	 * Contains the ui control objects which will be managed by this controller.
	 *
	 * @var array
	 */
	private $_uiControls = array();

	/**
	 * Constructor of PSK_Controller.
	 *
	 * @param string $par_Name A name for the object.
	 */
	function  __construct($par_Name = '')
	{
		parent::__construct(PSK_Application::getInstance(), $par_Name);
		$this->log = PSK_Application::getInstance()->log;
		$this->config = PSK_Application::getInstance()->config;
		$this->uri = PSK_Application::getInstance()->uri;
		$this->layout = PSK_Application::getInstance()->layout;
		$this->session = PSK_Application::getInstance()->session;

		if (is_object(PSK_Application::getInstance()->db)) {
			$this->db = PSK_Application::getInstance()->db;
		}
	}

	/**
	 * Calls specified method of all ui controls belongs to this controller.
	 *
	 * @param string $par_MethodName Name of the method that will be called.
	 *
	 * @throws Exception
	 */
	function CallControlsMethod($par_MethodName)
	{
		foreach ($this->_uiControls as $control) {
			//PSK_Log::getInstance()->WriteDebug($control->getName().'->'.$par_MethodName);
			if (!$control->getVisible()) {
				continue;
			}
			if (method_exists($control, $par_MethodName)) {
				$control->$par_MethodName();
			} else {
				throw new Exception(PSK_STR_CTL_UNDEFINEDMETHOD);
			}
		}
	}

	/**
	 * Calls methods of a control inside a controller. This method is called by
	 * PSK_Application.
	 */
	function CallControlMethod()
	{
		//$this->log->WriteArray($_POST);
		if ($this->uri->isPost()) {
			$controlKey = $_POST['_target'];
		} else {
			$controlKey = $this->uri->Parameter(1);
			//$this->log->WriteArray($this->uri->Parameters(0));
		}

		if (trim($controlKey) == '') return;

		if (array_key_exists($controlKey, $this->_objects)) {
			$control = $this->_objects[$controlKey];
			$method = null;
			$args = null;
			if ($this->uri->isPost()) {
				$method = $_POST['_method'];
				$args = explode('|', $_POST['_args']);
			} else {
				$method = $this->uri->Parameter(2);
				$args = $this->uri->Parameters(3);
				if ($control->getLinkTarget() != PSK_LT_ACTION) {
					$this->log->WriteLog('>:(');
					return;
				}
			}

			##
			##$this->log->WriteDebug('Call method : '.$controlKey.'->'.$method);
			##

			if (method_exists($control, $method)) {
				$control->$method($args);
			} else {
				throw new Exception(sprintf(PSK_STR_CTL_UNDEFINEDMETHOD,
					$method, $control->getName()));
			}
		}
	}

	/**
	 * Generates unique control names for a control if it's name has not been
	 * set.
	 *
	 * @return string
	 */
	function NewObjectName()
	{
		return $this->__objectName . '_object_' . (count($this->_objects) + 1);
	}

	/**
	 * Adds an object to the controller.
	 *
	 * @param PSK_OwnedObject $par_Object
	 * @throws Exception
	 */
	function AddObject(PSK_OwnedObject $par_Object)
	{
		##
		##$this->log->WriteDebug('Add Object : '.$par_Object->getName());
		##

		if (array_key_exists($par_Object->getName(), $this->_objects)) {
			throw new Exception(sprintf(PSK_STR_CTL_USEDOBJECTNAME,
				$par_Object->getName()));
		} else {
			$this->_objects[$par_Object->getName()] = $par_Object;
		}
	}

	/**
	 * Adds a ui control to the controller.
	 *
	 * @param PSK_UI_Base|PSK_UI_Object $par_UIControl
	 * @throws Exception
	 */
	function AddUIControl(PSK_UI_Base $par_UIControl)
	{
		$this->AddObject($par_UIControl);
		$this->_uiControls[$par_UIControl->getName()] = $par_UIControl;
	}

	function RenderView($par_View = '', $par_Section = '__MAIN__')
	{
		if ($par_View == '') {
			$par_View = $this->uri->controller . '/' . $this->uri->action;
 		}
		PSK_Application::getInstance()->setResponseType(PSK_RT_VIEW);
		$this->layout->AddView($par_View, $par_Section);
	}

	function RenderPartial($par_Partial) {
		PSK_Application::getInstance()->setResponseType(PSK_RT_PARTIAL_VIEW);
		$this->layout->AddView($par_Partial);
	}

	function RenderJSON(array $par_JSON) {
		PSK_Application::getInstance()->AddJSON($par_JSON);
	}

	/**
	 * This is an optional method for cleaning resources after all execution
	 * cycle. To do that you need to override this method.
	 */
	function CleanUp()
	{
		// Inside of this method is intentionaly left blank. It is automaticaly
		// called by PSK_Application.
		// If you need to make some cleanup or free some resources override this
		// method in your controller classes.
	}

	/**
	 * Constructs the model with the given class name by $par_AModel and
	 * returns its' intance.
	 *
	 * @param string $par_AModel Class name of the model.
	 * @param bool $par_FromBase Determines the path where the model file is
	 *                           located. If $par_FromBase is true then the loader will try to load
	 *                           model file from the base application model folder otherwise it will
	 *                           try to load the model file from current modules model folder.
	 * @throws Exception
	 */
	function ConstructModel($par_AModel, $par_FromBase = false)
	{
		$modelFile = PSK_Application::getInstance()->getFullModelPath($par_FromBase) .
			$par_AModel . '.php';

		if (is_file($modelFile)) {
			require_once $modelFile;
			if (class_exists($par_AModel)) {
				$instance = new $par_AModel($this);
			} else {
				throw new Exception(sprintf(PSK_STR_APP_NOMODELCLASS,
					$par_AModel));
			}
			return $instance;
		} else {
			throw new Exception(sprintf(PSK_STR_APP_NOMODEL, $modelFile));
		}

		$this->log->WriteDebug($modelFile);
	}
}
