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

require_once 'psk_base.php';
require_once 'psk_config.php';
require_once 'psk_log.php';
require_once 'psk_uri.php';
require_once 'psk_session.php';
require_once 'psk_layout.php';
require_once 'psk_controller.php';
require_once 'psk_pluginloader.php';
require_once 'psk_ui_base.php';
require_once 'psk_tags.php';

if (!@defined(STRINGS)) {
	include 'psk_lib_strings_tr.php';
}

/**
 * PSK_Application class.
 *
 * This is the main class for a PSK application. Takes user requests' and loads
 * related controllers. Loads plugins...
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Application
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Application class documentation link
 */
class PSK_Application extends PSK_Object
{

	/**
	 * Singleton instance of PSK_Application
	 *
	 * @var PSK_Application
	 */
	protected static $__instance = null;

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
	 * The controller which is currently responsible from the request.
	 *
	 * @var PSK_Controller
	 */
	public $controller = null;

	/**
	 * The object which handles database stuff. This is a plugin object. So
	 * you need to specify which plugin to use to connect the database. To do
	 * that add a configuration option to your config array like
	 * <code>$psk_conf['application']['dbClass'] = 'MySQL'</code> or call
	 * <code>setDbClass</code> method of PSK_Application.
	 *
	 * @var PSK_DBDriver_Base
	 */
	public $db = null;

	/**
	 * The object which manages authentication. This is a plugin object. So
	 * you need to specify which plugin to use to manage the authentication.
	 * To do that add a configuration option to your config array like
	 * <code>$psk_conf['application']['authClass'] = 'Simple'</code> or call
	 * <code>setAuthClass</code> method of PSK_Application.
	 *
	 * @var PSK_Auth_Base
	 */
	public $auth = null;

	/**
	 * The object which manages authorization. This is a plugin object. So
	 * you need to specify which plugin to use to manage the authorization.
	 * To do that add a configuration option to your config array like
	 * <code>$psk_conf['application']['autzClass'] = 'Simple'</code> or call
	 * <code>setAutzClass</code> method of PSK_Application.
	 *
	 * @var PSK_Autz_Base
	 */
	public $autz = null;

	/**
	 * Identifies the active locale of the application.
	 *
	 * @var string
	 */
	public $locale = '';

	/**
	 * The path where PSK library files are located.
	 *
	 * @var string
	 */
	private $_libPath = '';

	/**
	 * The path where application files are located.
	 *
	 * @var string
	 */
	private $_applicationPath = 'app';

	/**
	 * The path where controller files are located.
	 *
	 * @var string
	 */
	private $_controllerPath = 'controllers';

	/**
	 * The path where view files are located.
	 *
	 * @var string
	 */
	private $_viewPath = 'views';

	/**
	 * The path where model files are located.
	 *
	 * @var string
	 */
	private $_modelPath = 'models';

	/**
	 * The path where template files are located.
	 *
	 * @var string
	 */
	private $_templatePath = 'templates';

	/**
	 * The path where localization files are located.
	 *
	 * @var string
	 */
	private $_i18nPath = 'i18n';

	/**
	 * The array that contains locales supported by the application.
	 *
	 * @var array
	 */
	private $_locales = array();

	/**
	 * Contains layout option sections of the controllers.
	 *
	 * @var array
	 */
	private $_controllerLayouts = array();

	/**
	 * Determines where to store states of controls by default.
	 * If you do not intentionally set a state storage for a control,
	 * it will use the this value to store it's state.
	 *
	 * @see psk_const.php for available values under <b>State storage</b>.
	 * @var integer
	 */
	private $_defaultStateStorage = PSK_SS_HIDDEN;

	/**
	 * The view files which will be rendered.
	 *
	 * @var array
	 */
	private $_controlStatesHidden = array();

	/**
	 * Saves the states of controls for exporting into session.
	 *
	 * @var array
	 */
	private $_controlStatesSession = array();

	/**
	 * Saves the states of controls for exporting into a cookie.
	 *
	 * @var array
	 */
	private $_controlStatesCookie = array();

	/**
	 * Name of the database driver class.
	 *
	 * @var string
	 */
	private $_dbClass = '';

	/**
	 * Name of the authentication management class.
	 *
	 * @var string
	 */
	private $_authClass = '';

	/**
	 * Name of the authorization management class.
	 *
	 * @var string
	 */
	private $_autzClass = '';
	private $_responseType = PSK_RT_NONE;
	private $_jsonData = array();

	/**
	 * Constructor of the PSK_Application class.
	 *
	 * @param string $par_Name Name of the application.
	 */
	function __construct($par_Name = '')
	{
		if ($par_Name == '') {
			$par_Name = 'PSK_APP';
		}

		parent::__construct($par_Name);

		$this->_libPath = dirname(__FILE__) . '/';

		$this->config = PSK_Config::getInstance();
		$this->log = PSK_Log::getInstance();
		$this->uri = PSK_Uri::getInstance();
		$this->session = PSK_Session::getInstance();

		// set_error_handler(array($this, 'ErrorHandler'), error_reporting());
		// set_exception_handler(array($this, 'ExceptionHandler'));

		self::$__instance = $this;
	}

	/**
	 * Returns singleton instance of PSK_Applicaiton.
	 *
	 * @return PSK_Application
	 */
	public static function getInstance()
	{
		if (self::$__instance === null) {
			self::$__instance = new self();
		}
		return self::$__instance;
	}

	/**
	 * Run baby run!
	 *
	 * @param string $par_FrontController File name of the default file for
	 *                                    application.
	 *
	 * @throws Exception
	 */
	function Run($par_FrontController)
	{
		$this->session->Start();
		try {

			if ($this->config->isOptionExist('locales', 'application')) {
				$this->_locales = $this->config->ReadOption('locales', 'application');
			}
			$this->uri->setLocales($this->_locales);

			if ($this->config->isOptionExist('log')) {
				$this->config->ConfigureObject($this->log, $this->config->ReadOption('log'));
			}
			if ($this->config->isOptionExist('application')) {
				$this->config->ConfigureObject($this, $this->config->ReadOption('application'));
			}
			if ($this->config->isOptionExist('uri')) {
				$this->config->ConfigureObject($this->uri, $this->config->ReadOption('uri'));
			}

			$this->uri->setBasePage($par_FrontController);
			$this->uri->CompileUri();

			//var_dump($this->config);
			//var_dump($this->log);
			//var_dump($this->uri);

			// Determine the application locale...
			if (count($this->_locales) > 0) {
				if ($this->uri->locale != "") {
					$this->locale = $this->uri->locale;
				} else {
					$requestedLocales = array();
					foreach (explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $rl) {
						if (strpos($rl, ';') !== false)
							$requestedLocales[] = strtolower(substr($rl, 0, strpos($rl, ';')));
						else
							$requestedLocales[] = strtolower($rl);
					}

					//$this->log->WriteArray($requestedLocales, PSK_ET_APPINFORMATION);

					foreach ($requestedLocales as $rl) {
						if (array_search($rl, $this->_locales) !== false) {
							$this->locale = $rl;
							break;
						}
					}

					if ($this->locale == '') {
						foreach ($requestedLocales as $rl) {
							if (strpos($rl, '-') !== false)
								$l = substr($rl, 0, strpos($rl, '-'));
							else
								$l = $rl;
							if (array_search($l, $this->_locales) !== false) {
								$this->locale = $l;
								break;
							}
						}
					}

					if ($this->locale == '') {
						$this->locale = $this->_locales[0];
					}
				}
			}

			if ($this->locale == '') {
				$this->locale = 'tr';
			}

			//$this->log->WriteDebug('App locale: '. $this->locale);
			//$this->log->WriteDebug('URI locale: '. $this->uri->locale);

			$locFile = 'psk_strings_' . $this->locale . '.php';
			/** @noinspection PhpIncludeInspection */
			include $locFile;

			$this->_InitializeLayout();

			//var_dump($this->layout);

			//echo "Load plugins... ";

			$this->db = $this->_LoadPlugin($this->_dbClass, 'DBDriver', 'database');
			$this->auth = $this->_LoadPlugin($this->_authClass, 'Auth', 'authentication');
			$this->autz = $this->_LoadPlugin($this->_autzClass, 'Autz', 'authorization');

			//echo "Load plugins ok... ";

			if (is_object($this->db)) {
				if ($this->db->getAutoConnect()) {
					$this->db->Connect();
				}
			}

			if (is_object($this->auth)) {
				$this->auth->Initialize();
			}

			// Load localization file for template...
			$template_i18n = $this->uri->EndWithSlash($this->_applicationPath) .
				$this->uri->EndWithSlash($this->_i18nPath) .
				$this->layout->getTemplateName() . '_' . $this->locale . '.php';

			//$this->log->WriteDebug('Template i10n file: '.$template_i18n);

			if (is_file($template_i18n)) {
				/** @noinspection PhpIncludeInspection */
				include($template_i18n);
			}

			//die($template_i18n);

			$this->controller = $this->_ConstructController($this->uri->controller);

			//var_dump($this->controller);

			if (is_object($this->autz)) {
				if (is_object($this->auth)) {
					if (!$this->autz->Authorize()) {
						if (isset($_SERVER[HTTP_X_REQUESTED_WITH])) {
							$res['Data'] = null;
							$res['State'] = array('error' => array('Oturumunuz sonlanmış. Tekrar giriş yapın.'));
							$this->AddJSON($res);
						} else {
							if ($this->auth->loginPage != '') {
								$this->uri->Redirect($this->auth->loginPage);
							}
							throw new Exception(sprintf(PSK_STR_A_NOTAUTHORIZED,
								$this->uri->ActionLink(PSK_STR_A_LOGIN, array(),
									array('c' => $this->autz->getPublicCotroller()))), 1000);
						}
					}
				} else {
					throw new Exception(PSK_STR_A_AUTHNEEDED);
				}
			}

			if ($this->_responseType != PSK_RT_JSON)
			{
				try {
					$action = $this->uri->action . 'Action';
					if (method_exists($this->controller, $action)) {

						// passing uri params to action methods might be nice!

						$this->controller->$action();
					} else {
						$this->log->WriteLog(sprintf(PSK_STR_APP_NOACTION,
							$this->uri->action), PSK_ET_APPERROR);
					}
				} catch (Exception $e) {
					$this->log->WriteException($e);
				}

				$this->controller->CallControlsMethod('Initialize');

				try {
					if (isset($_POST['_source']) &&
						($_POST['_source'] == 'psk_control')
					) {
						$this->controller->CallControlMethod();
					} elseif ($this->uri->Parameter(0) == '_ctlmth') {
						$this->controller->CallControlMethod();
					}
				} catch (Exception $e) {
					$this->log->WriteException($e);
				}

				$this->controller->CallControlsMethod('Render');
				$this->controller->CallControlsMethod('ExportState');
			}

		} catch (Exception $e) {
			$this->log->WriteException($e);
		};

		//echo($this->_responseType);

		switch ($this->_responseType) {
			case PSK_RT_NONE:
			case PSK_RT_VIEW:
				if (is_object($this->layout)) {
					try {
						if ($this->layout->getTemplate() != '') {
							include($this->layout->getTemplate());
						}
					} catch (Exception $e) {
						$this->log->WriteException($e);
					}
				}

				$this->log->ShowLog();
				$this->layout->IncludeView();

				echo "\n";
				foreach ($this->_controlStatesHidden as $stateKey => $stateVal) {
					echo "<input type=\"hidden\" name=\"" . $stateKey . "\" id=\"" .
						$stateKey . "\" value=\"" . $stateVal . "\" />\n";
				}
				break;
			case PSK_RT_PARTIAL_VIEW:
				$this->log->ShowLog();
				$this->layout->IncludeView();

				echo "\n";
				foreach ($this->_controlStatesHidden as $stateKey => $stateVal) {
					echo "<input type=\"hidden\" name=\"" . $stateKey . "\" id=\"" .
						$stateKey . "\" value=\"" . $stateVal . "\" />\n";
				}
				break;
			case PSK_RT_JSON:
				header('Content-Type: application/json');
				foreach($this->_jsonData as $json) {
					echo json_encode($json);
				}
				break;
			case PSK_RT_BLANK:
				break;
		}


		$this->session->Write($this->_controlStatesSession, 'CONTROL_STATES');

		if (is_object($this->controller)) {
			$this->controller->CleanUp();
		}
	}

	/**
	 * Initializes layout object and configures it.
	 */
	private function _InitializeLayout()
	{
		$this->layout = PSK_Layout::getInstance();
		//$this->layout->setName($this->__objectName);

		$module = $this->uri->module == '' ? '__MAIN__' : $this->uri->module;

		if (array_key_exists($module, $this->_controllerLayouts)) {
			$cl = $this->_controllerLayouts[$module];

			if (array_key_exists($this->getName(), $cl) === true) {
				$this->config->ConfigureObject($this->layout,
					$this->config->ReadOption($cl[$this->getName()]));
			}
			if (array_key_exists($this->uri->module, $cl) === true) {
				$this->config->ConfigureObject($this->layout,
					$this->config->ReadOption($cl[$this->uri->module]));
			}
			if (array_key_exists($this->uri->controller, $cl) === true) {
				$this->config->ConfigureObject($this->layout,
					$this->config->ReadOption($cl[$this->uri->controller]));
			}
		} else {
			if (array_key_exists('__MAIN__', $this->_controllerLayouts)) {
				$cl = $this->_controllerLayouts['__MAIN__'];
				if (array_key_exists($this->__objectName, $cl) === true) {
					$this->config->ConfigureObject($this->layout,
						$this->config->ReadOption($cl[$this->__objectName]));
				}
			}
		}
	}

	/**
	 * Loads specified plugin and configures it.
	 *
	 * @param string $par_PluginClass
	 * @param string $par_PluginLibrary
	 * @param string $par_Options
	 * @return bool|null
	 */
	private function _LoadPlugin($par_PluginClass, $par_PluginLibrary, $par_Options)
	{
		if ($par_PluginClass === '') {
			return null;
		}

		$loader = new PSK_PluginLoader();

		try {
			$loader->LoadPlugin($par_PluginClass, $par_PluginLibrary);
			$plugin = $loader->getPlugin();
			$this->config->ConfigureObject($plugin, $this->config->ReadOption($par_Options));
			return $plugin;
		} catch (Exception $e) {
			$this->log->WriteException($e);
		}
	}

	/**
	 * Loads requested controller class.
	 *
	 * @param string $par_Controller Name of the controller.
	 *
	 * @throws Exception
	 * @return PSK_Controller
	 */
	private function _ConstructController($par_Controller)
	{
		$controllerClass = $par_Controller . 'Controller';
		$controllerFile = $this->uri->EndWithSlash($this->_applicationPath) .
			$this->uri->EndWithSlash($this->uri->module) .
			$this->uri->EndWithSlash($this->_controllerPath) .
			$controllerClass . '.php';

		if (is_file($controllerFile)) {

			if (count($this->_locales) > 0) {
				$i18nFile = $this->uri->EndWithSlash($this->_applicationPath) .
					$this->uri->EndWithSlash($this->uri->module) .
					$this->uri->EndWithSlash($this->_i18nPath) .
					$par_Controller . '_';

				$currentI18N = $i18nFile . $this->locale . '.php';

				if (is_file($currentI18N)) {
					include($currentI18N);
				} else {
					$primeryI18N = $i18nFile . $this->_locales[0] . '.php';
					if (is_file($primeryI18N)) {
						include($primeryI18N);
					}
				}
			}
			require_once $controllerFile;
			if (class_exists($controllerClass)) {
				$instance = new $controllerClass($par_Controller);
			} else {
				throw new Exception(sprintf(PSK_STR_APP_NOCONTROLLERCLASS, $controllerClass));
			}
			return $instance;
		} else {
			throw new Exception(sprintf(PSK_STR_APP_NOCONTROLLER, $par_Controller), 404);
		}
	}

	/**
	 * Determines which controller will use which layout options.
	 *
	 * @param string $par_Section Configuration options section for the layout.
	 * @param string $par_Controller Controller or module name which uses the
	 *                               layout options.
	 * @param string $par_Module Module name of the controller. If you want use
	 *                               same layout option for all controllers under same module. Use module name
	 *                               as the value of the par_Controller parameter.
	 */
	function DefineLayout($par_Section, $par_Controller, $par_Module = '__MAIN__')
	{
		$this->_controllerLayouts[$par_Module][$par_Controller] = $par_Section;
	}

	/**
	 * Writes the control states into an array for saving against posting or
	 * revisiting.
	 *
	 * @param <type> $par_StateKey Key value for the state.
	 * @param <type> $par_StateVal Value of the state
	 * @param integer $par_StateDest Where to save state values.
	 *
	 * @see   psk_const.php for available values under <b>State storage</b>.
	 */
	function WriteControlState($par_StateKey, $par_StateVal, $par_StateDest = PSK_SS_DEFAULT)
	{
		if ($par_StateDest == PSK_SS_DEFAULT)
			$par_StateDest = $this->_defaultStateStorage;

		switch ($par_StateDest) {
			case PSK_SS_HIDDEN :
				$this->_controlStatesHidden[$par_StateKey] = $par_StateVal;
				break;
			case PSK_SS_SESSION :
				$this->_controlStatesSession[$par_StateKey] = $par_StateVal;
				break;
			case PSK_SS_COOKIE :
				$this->_controlStatesCookie[$par_StateKey] = $par_StateVal;
				break;
		}
	}

	/**
	 * Reads the state of a control if the page had been refreshed or posted back.
	 *
	 * @param $par_StateKey
	 * @param int $par_DefaultValue
	 * @param int $par_StateSource
	 * @internal param $ <type> $par_StateKey Key value for the state.
	 * @internal param $ <type> $par_DefaultValue Default value of the state. If nothing
	 * read then this value will be returned.
	 * @internal param $ <type> $par_StateSource Where the state values stored in.
	 *
	 * @return bool|int <type>@see    psk_const.php for available values under <b>State storage</b>.
	 */
	function ReadControlState($par_StateKey, $par_DefaultValue = 0, $par_StateSource = PSK_SS_DEFAULT)
	{
		if ($par_StateSource == PSK_SS_DEFAULT)
			$par_StateSource = $this->_defaultStateStorage;

		$state = $par_DefaultValue;
		switch ($par_StateSource) {
			case PSK_SS_HIDDEN :
				if (isset($_POST[$par_StateKey]))
					$state = $_POST[$par_StateKey];
				break;
			case PSK_SS_SESSION :
				$s = $this->session->Read($par_StateKey, 'CONTROL_STATES');
				if ($s)
					$state = $s;
				break;
			case PSK_SS_COOKIE :
				break;
		}

		return $state;
	}

	/**
	 * Calls Show method of a control if it is exist in current controller.
	 * Use this function for safe calls.
	 * If $par_ExcepOnFailure has been set to true then throw an exception
	 * else returns false.
	 *
	 * @param string $par_AControlName Control to get rendered.
	 * @param boolean $par_ExcepOnFailure Defines the behaviour of function
	 *                                        on failure - generaly a control that is not exists in current controller...
	 *                                        If set true then throws an exception else returns false.
	 *
	 * @throws Exception
	 * @return boolean Returns true if control get rendered otherwise false
	 */
	function ShowControl($par_AControlName, $par_ExcepOnFailure = false)
	{
		if (is_object($this->controller)) {
			if (isset($this->controller->$par_AControlName)) {
				$this->controller->$par_AControlName->Show();
				return true;
			} else {
				if ($par_ExcepOnFailure) {
					throw new Exception(PSK_STR_CTL_CONTROLNOTFOUND);
				}
			}
		}
		return false;
	}

	function AddJSON(array $par_JSON) {
		$this->_responseType = PSK_RT_JSON;
		$this->_jsonData[] = $par_JSON;
	}

	/**
	 * Handles the errors inside the application.
	 *
	 * @param integer $par_Code The code level of the raised error.
	 * @param string $par_Message The error message
	 * @param string $par_File The file name that the error raised at.
	 * @param integer $par_Line The line number where the error raised at.
	 *
	 * @throws Exception
	 */
	function ErrorHandler($par_Code, $par_Message, $par_File, $par_Line)
	{
		if ($par_Code & error_reporting()) {
			restore_error_handler();
			restore_exception_handler();

			/*
			  $trace=debug_backtrace();

			  $traceData = '';
			  foreach($trace as $i=>$step) {
			  $traceData .= $i." ";
			  if (isset($step['file']))
			  $traceData .= $step['file'];
			  if (isset($step['line']))
			  $traceData .= "(".$step['line'].")";
			  if (isset($step['function']))
			  $traceData .= ": ".$step['function'];
			  $traceData.="<br/>\n";
			  }

			  $this->log->WriteArray(array(
			  PSK_STR_LOG_EXCEPTION => $par_Message,
			  PSK_STR_LOG_EXCEPCODE => $par_Code,
			  PSK_STR_LOG_EXCEPFILE => $par_File,
			  PSK_STR_LOG_EXCEPLINE => $par_Line,
			  PSK_STR_LOG_EXCEPTRACE => $traceData));

			 */

			throw new Exception($par_Message, $par_Code);
		}
	}

	/**
	 * Handles exceptions inside the application.
	 *
	 * @param Exception $par_Exception The exception that occurred.
	 */
	function ExceptionHandler($par_Exception)
	{
		restore_error_handler();
		restore_exception_handler();

		$this->log->WriteException($par_Exception);
	}

	/**
	 * Final cleanup for the application.
	 */
	function End()
	{
		if (isset($this->db)) {
			$this->db->DisConnect();
		}
	}

	/**
	 * Set the application path.
	 *
	 * @param string $par_ApplicationPath Path of application
	 */
	function setApplicationPath($par_ApplicationPath)
	{
		$this->_applicationPath = $par_ApplicationPath;
	}

	/**
	 * Sets the path of controller files.
	 *
	 * @param string $par_ControllerPath Path of controller files.
	 */
	function setControllerPath($par_ControllerPath)
	{
		$this->_controllerPath = $par_ControllerPath;
	}

	/**
	 * Sets the path of view files.
	 *
	 * @param string $par_ViewPath Path of view files.
	 */
	function setViewPath($par_ViewPath)
	{
		$this->_viewPath = $par_ViewPath;
	}

	/**
	 * Sets the path of the model files.
	 *
	 * @param $par_ModelPath Path of the model files.
	 */
	function setModelPath($par_ModelPath)
	{
		$this->_modelPath = $par_ModelPath;
	}

	/**
	 * Sets the path of template files.
	 *
	 * @param string $par_TemplatePath Path of template files.
	 */
	function setTemplatePath($par_TemplatePath)
	{
		$this->_templatePath = $par_TemplatePath;
	}

	/**
	 * Sets the path of localization files.
	 *
	 * @param string $par_i18nPath Path of template files.
	 */
	function seti18nPath($par_i18nPath)
	{
		$this->_i18nPath = $par_i18nPath;
	}

	/**
	 * Sets the locales property.
	 *
	 * @param array $par_Locales Array of supported locales by the application.
	 */
	function setLocales(array $par_Locales)
	{
		$this->_locales = $par_Locales;
	}

	/**
	 * Sets the default state storage.
	 *
	 * @param integer $par_DefaultStateStorage
	 *
	 * @see psk_const.php for available values under <b>State storage</b>.
	 */
	function setDefaultStateStorage($par_DefaultStateStorage)
	{
		$this->_defaultStateStorage = $par_DefaultStateStorage;
	}

	/**
	 * Sets the name of the database driver class.
	 *
	 * @param string $par_DbClass Name of the database driver.
	 */
	function setDbClass($par_DbClass)
	{
		$this->_dbClass = $par_DbClass;
	}

	/**
	 * Sets the name of the authentication management object.
	 *
	 * @param string $par_AuthClass Name of the authentication management
	 *                              object.
	 */
	function setAuthClass($par_AuthClass)
	{
		$this->_authClass = $par_AuthClass;
	}

	/**
	 * Sets the name of the authorization management object.
	 *
	 * @param string $par_AutzClass Name of the authorization management object.
	 */
	function setAutzClass($par_AutzClass)
	{
		$this->_autzClass = $par_AutzClass;
	}

	/**
	 * @param $par_ResponseType
	 * @throws Exception
	 */
	function setResponseType($par_ResponseType)
	{
		if ($this->_responseType == PSK_RT_NONE || $this->_responseType == $par_ResponseType) {
			$this->_responseType = $par_ResponseType;
		} else {
			throw new Exception(PSK_STR_APP_RESPONSE_ERROR);
		}
	}

	/**
	 * @return int
	 */
	function getResponse()
	{
		return $this->_responseType;
	}

	/**
	 * Returns the full path of template files.
	 *
	 * @return string
	 */
	function getFullTemplatePath()
	{
		return
			$this->uri->EndWithSlash($this->_applicationPath) .
			$this->uri->EndWithSlash($this->_templatePath);
	}

	/**
	 * Returns the full path of model files.
	 *
	 * @param bool $par_FromBase If true, ignores the module path else
	 *                           includes the module path.
	 *
	 * @return string
	 */
	function getFullModelPath($par_FromBase = false)
	{
		return
			$this->uri->EndWithSlash($this->_applicationPath) .
			($par_FromBase ? '' : $this->uri->EndWithSlash($this->uri->module)) .
			$this->uri->EndWithSlash($this->_modelPath);
	}

	function getViewFile($par_View)
	{
		return $this->uri->EndWithSlash($this->_applicationPath) .
		$this->uri->EndWithSlash($this->uri->module) .
		$this->uri->EndWithSlash($this->_viewPath) .
		$par_View . '.phtml';
	}

}
