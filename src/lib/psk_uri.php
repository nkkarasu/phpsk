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
 * PSK_Uri class.
 *
 * Explodes action and parameter segments of an uri and
 * builds uris for application.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Uri
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Uri class documentation link
 */

class PSK_Uri extends PSK_Object
{
	/**
	 * Singleton instance of PSK_Uri class.
	 *
	 * @var PSK_Uri
	 */
	protected static $_instance = null;

	/**
	 * Uri which excluded server adress and application path or alias.
	 *
	 * @var string
	 */
	private $_fullUri;

	/**
	 * Application path or alias. This field has to be initialized for proper
	 * routing. Other wise application will not work as expected. To initialize
	 * this property use setBasePath method or add [uri][basePath] option to
	 * your configuration.
	 *
	 * @var string
	 */
	private $_basePath = '';

	/**
	 * File name of the front controller.
	 *
	 * @var string
	 */
	private $_basePage = '';

	/**
	 * Defines if rewrite module is active or not. This property is required for
	 * uri generating. If it is true then front controller file name will not be
	 * included generated uris.
	 *
	 * @var boolean
	 */
	private $_rewriteActive = true;

	/**
	 * The protocol which applcation is serving under.
	 * @var string
	 */
	private $_protocol = 'http';

	private $_reRoute = false;

	/**
	 * Module names of the application.
	 *
	 * @var array
	 */
	private $_modules = array();

	/**
	 * An array which contains uri parts exploded by slash
	 *
	 * @var array
	 */
	private $_uriBlocks = array();

	/**
	 * An array which contains url parts exploded by slash except server address,
	 * module, controller and action name.
	 *
	 * @var array
	 */
	private $_params = array();

	/**
	 * The array that contains locales supported by the application.
	 * This is actually a copy of _locales property of PSK_Application.
	 * Contents of this array will be used to determine the locale
	 * identified by the url address.
	 *
	 * @var array
	 */
	private $_locales = array();

	/**
	 * Requested module.
	 *
	 * @var string
	 */
	public $module = '';

	/**
	 * Requested controller.
	 *
	 * @var string
	 */
	public $controller = 'index';

	/**
	 * Requested action.
	 *
	 * @var string
	 */
	public $action = 'index';

	/**
	 * The locale identifier of the uri address.
	 * Be aware of that this is not locale of the whole application. It just
	 * only identifies the locale section of the uri. To get the whole
	 * application's locale use <code>PSK_Application::getInstance->locale
	 * </code>
	 *
	 * @var type string
	 */
	public $locale = '';

	/**
	 * Contructor of PSK_Uri.
	 *
	 * @param string $par_Name A name for the object.
	 */
	function  __construct($par_Name = '')
	{
		parent::__construct($par_Name);
	}

	/**
	 * Returns singleton instance of PSK_Uri
	 *
	 * @return PSK_Uri
	 */
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Checks a uri block is exist in an array or not. This function searches
	 * locale identifier and module name in the uri. If it founds the searched
	 * block then it returns that block other wise returns an empty string.
	 *
	 * @param type  $par_BlockIndex Reference block index. If search gets
	 *                              succeded then this reference will get incremented by 1.
	 * @param array $par_SerchArray The search array where the uri block
	 *                              will be searched. This parameter may take only two values:
	 * <code>$this->_modules</code> or <code>$this->_locales</code>
	 */
	private function _CheckBlock(&$par_BlockIndex, array $par_SerchArray)
	{
		if (count($this->_uriBlocks) > $par_BlockIndex) {
			if (array_search($this->_uriBlocks[$par_BlockIndex],
				$par_SerchArray) !== false
			) {
				return $this->_uriBlocks[$par_BlockIndex++];
			}
		}
		return '';
	}

	/**
	 * Appends slash to the end of par_Value. If par_Value includes slashes
	 * then _EndWithSlash removes that slashes.
	 *
	 * @param string $par_Value Value to be concatanated with slash.
	 *
	 * @return string
	 */
	function EndWithSlash($par_Value)
	{
		$out = trim($par_Value, '/');
		if ($par_Value == '') {
			return $out;
		}
		return $out . '/';
	}

	/**
	 * Processes uri and extracts module, controller, action and other
	 * parameters.
	 */
	function CompileUri()
	{
		$this->_fullUri =
			trim(str_replace($this->_basePage, '', str_replace($this->_basePath,
				'', strtolower($_SERVER['REQUEST_URI']))), '/');

		if ($this->_fullUri !== '') {
			$this->_uriBlocks = explode('/', $this->_fullUri);
		}

		$bi = 0;

		$this->locale = $this->_CheckBlock($bi, $this->_locales);
		$this->module = $this->_CheckBlock($bi, $this->_modules);

		if (count($this->_uriBlocks) == $bi)
			$this->_uriBlocks[$bi] = 'index';
		if (count($this->_uriBlocks) == $bi + 1)
			$this->_uriBlocks[$bi + 1] = 'index';

		$this->controller = $this->_uriBlocks[$bi];
		$this->action = $this->_uriBlocks[$bi + 1];

		$this->_params = array_slice($this->_uriBlocks, $bi + 2);

		if ($this->_reRoute) {
			Route();
		}

//		PSK_Log::getInstance()->WriteArray($this->_uriBlocks);
//		PSK_Log::getInstance()->WriteArray($this->_params);
//		PSK_Log::getInstance()->WriteDebug('Locale : '. $this->locale);
//		PSK_Log::getInstance()->WriteDebug('Module : '. $this->module);
//		PSK_Log::getInstance()->WriteDebug('Controller : '. $this->controller);
//		PSK_Log::getInstance()->WriteDebug('Action : '. $this->action);
	}

	/**
	 * Genarates full path for requested relative path. Use this function to
	 * include other resources to your pages such as css or image files.
	 *
	 * @param string $par_RelPath Relative path to include.
	 *
	 * @return string Full path of requested relative path.
	 */
	function IncludePath($par_RelPath)
	{
		$path = $this->_basePath === '' ? '' : $this->_basePath . '/';
		return $this->_protocol . '://' . $_SERVER['SERVER_NAME'] . '/' .
			$path . $par_RelPath;
	}

	/**
	 * Returns uri parts as a parameter. If Param_Index is out of range then
	 * returns default vaalue as parameter.
	 *
	 * @param integer $par_ParamIndex Index of the parameter
	 * @param <type> $par_Default Default value if no parameter at the specified
	 *                                index.
	 *
	 * @return string Value of the parameter.
	 */
	function Parameter($par_ParamIndex, $par_Default = false)
	{
		if (array_key_exists($par_ParamIndex, $this->_params)) {
			return $this->_params[$par_ParamIndex];
		}
		return $par_Default;
	}

	/**
	 * Returns params as an array from a starting offset.
	 *
	 * @param integer $par_FromIndex Start offset of requested params.
	 *
	 * @return array
	 */
	function Parameters($par_FromIndex = 0)
	{
		return array_slice($this->_params, $par_FromIndex);
	}

	function ReplaceParams(array $par_NewParams)
	{
		$this->_params = $par_NewParams;
//		$this->_params = array();
//		foreach ($par_NewParams as $param) {
//			$this->_params[] = $param;
//		}
	}

	/**
	 * Generates href values which targets actions. Uses
	 * current module and current controller for generated href values.
	 *
	 * @param string $par_Action Name of the action which will be targeted.
	 *
	 * @return string Generated href value.
	 */
	function ActionHref($par_Action)
	{
		$f = $this->_rewriteActive ? '' : $this->_basePage . '/';
		$l = $this->EndWithSlash($this->locale);
		$m = $this->EndWithSlash($this->module);
		$c = $this->EndWithSlash($this->controller);
		return $this->IncludePath($f . $l . $m . $c . $par_Action);
	}

	/**
	 * Generates href values which targets controllers. Uses
	 * current module for generated href values.
	 *
	 * @param string $par_Controller Name of the controller which will be
	 *                               targeted.
	 *
	 * @return string Generated href value.
	 */
	function ControllerHref($par_Controller)
	{
		$f = $this->_rewriteActive ? '' : $this->_basePage . '/';
		$l = $this->EndWithSlash($this->locale);
		$m = $this->EndWithSlash($this->module);
		return $this->IncludePath($f . $l . $m . $par_Controller);
	}

	function PostHref ($par_Target, $par_Method, array $par_Params = array()){
		//$p = count($par_Params) > 0 ? implode('|', $par_Params) : '';
		//return "javascript:_postBack('" . $par_Target .
		//	"', '" . $par_Method . "', '" . $p . "')";

		return $this->CustomPostHref('_postBack', $par_Target, $par_Method, $par_Params);
	}

	function CustomPostHref ($par_Function, $par_Target, $par_Method, array $par_Params = array()){
		$p = count($par_Params) > 0 ? implode('|', $par_Params) : '';
		return "javascript:".$par_Function."('" . $par_Target .
		"', '" . $par_Method . "', '" . $p . "')";
	}

	/**
	 * Generates href values which targets modules.
	 *
	 * @param string $par_Module Name of the module which will be targeted.
	 *
	 * @return string Generated href value.
	 */
	function ModuleHref($par_Module)
	{
		$f = $this->_rewriteActive ? '' : $this->_basePage . '/';
		$l = $this->EndWithSlash($this->locale);
		$m = $par_Module === '__MAIN__' ? '' : $par_Module;
		return $this->IncludePath($f . $l . $m);
	}

	/**
	 * Generates links that targets actions.
	 *
	 * @param string $par_ActionText   The text will be shown as link.
	 * @param array  $par_ActionParams The action parameters.
	 * @param array  $par_UriBlocks    Module, controller and action names which
	 *                                 link targets. If not set, current module, controller and action will be
	 *                                 used.
	 * @param string $par_Title        Hint text will be shown.
	 *
	 * @return string Generated link.
	 */
	function ActionLink($par_ActionText, array $par_ActionParams = array(),
	                    array $par_UriBlocks = array(), $par_Title = '')
	{
		$f = $this->_rewriteActive ? '' : $this->_basePage . '/';
		$l = array_key_exists('l', $par_UriBlocks) === true
			? $this->EndWithSlash($par_UriBlocks['l'])
			: $this->EndWithSlash($this->locale);
		$m = array_key_exists('m', $par_UriBlocks) === true
			? $this->EndWithSlash($par_UriBlocks['m'])
			: $this->EndWithSlash($this->module);
		$c = '';
		$a = '';
		if (array_key_exists('c', $par_UriBlocks) === true) {
			$c = $this->EndWithSlash($par_UriBlocks['c']);
			if (array_key_exists('a', $par_UriBlocks)) {
				$a = $this->EndWithSlash($par_UriBlocks['a']);
			}
		} else {
			$c = $this->EndWithSlash($this->controller);
			$a = array_key_exists('a', $par_UriBlocks) === true
				? $this->EndWithSlash($par_UriBlocks['a'])
				: $this->EndWithSlash($this->action);
		}

		$p = implode('/', $par_ActionParams);

		//PSK_Log::getInstance()->WriteDebug('f:'.$f);
		//PSK_Log::getInstance()->WriteDebug('m:'.$m);
		//PSK_Log::getInstance()->WriteDebug('c:'.$c);
		//PSK_Log::getInstance()->WriteDebug('a:'.$a);
		//PSK_Log::getInstance()->WriteDebug('p:'.$p);

		return '<a href="' . $this->IncludePath($f . $l . $m . $c . $a . $p) . '" title="' .
			$par_Title . '">' . $par_ActionText . '</a>';
	}

	/**
	 * Generates links that targets controllers.
	 *
	 * @param string $par_ControllerText The text will be shown as link.
	 * @param array  $par_UriBlocks      Module and controller names which link
	 *                                   targets. If not set, current module and controller will be used.
	 * @param string $par_Title          Hint text will be shown.
	 *
	 * @return string Generated link.
	 */
	function ControllerLink($par_ControllerText, array $par_UriBlocks = array(),
	                        $par_Title = '')
	{
		$f = $this->_rewriteActive ? '' : $this->_basePage . '/';

		$m = '';
		if (array_key_exists('m', $par_UriBlocks) === true) {
			if ($par_UriBlocks['m'] === '__MAIN__') {
				$m = '';
			} else {
				$m = $this->EndWithSlash($par_UriBlocks['m']);
			}
		} else {
			$m = $this->EndWithSlash($this->module);
		}

		$l = array_key_exists('l', $par_UriBlocks) === true
			? $this->EndWithSlash($par_UriBlocks['l'])
			: $this->EndWithSlash($this->locale);

		$c = array_key_exists('c', $par_UriBlocks) === true
			? $this->EndWithSlash($par_UriBlocks['c'])
			: $this->EndWithSlash($this->controller);
		return '<a href="' . $this->IncludePath($f . $l . $m . $c) . '" title="' .
			$par_Title . '">' . $par_ControllerText . '</a>';
	}

	/**
	 * Generates a javascript Submit link. That link generally used by
	 * PSK Controls for internal method calls.
	 *
	 * @param string $par_Text   The text will be shown as link.
	 * @param string $par_Target The target object wich will handle that
	 *                           submit request.
	 * @param string $par_Method The method will invoked inside the control.
	 * @param array  $par_Params Method parameters.
	 *
	 * @return string Generated link.
	 */
	function PostLink($par_Text, $par_Target, $par_Method,
	                  array $par_Params = array(), $par_Title = '')
	{
		$p = count($par_Params) > 0 ? implode('|', $par_Params) : '';
		$t = $par_Title == '' ? '' : ' title="' . $par_Title . '" ';
		return "<a href=\"javascript:_postBack('" . $par_Target .
			"', '" . $par_Method . "', '" . $p . "')\"" . $t . ">" . $par_Text . "</a>";
	}

	/**
	 * Generetes a link that calls a method of a control.
	 *
	 * @param integer $par_LinkType Type of the link
	 *
	 * @see pks_const.php for link types.
	 *
	 * @param string  $par_Text     Text will be shown inside the anchor tag.
	 * @param string  $par_Target   Name of the target control
	 * @param string  $par_Method   Name of the target method.
	 * @param array   $par_Params   Additional method parameters.
	 *
	 * @return string An anchor tag.
	 */
	function MethodLink($par_LinkType, $par_Text, $par_Target, $par_Method,
	                    array $par_Params = array())
	{
		switch ($par_LinkType) {
			case PSK_LT_ACTION :
				$args = implode('/', $par_Params);
				return $this->ActionLink($par_Text, array('_ctlmth',
					$par_Target, $par_Method, $args), array(), $par_Text);
			case PSK_LT_POSTBACK:
				return $this->PostLink($par_Text, $par_Target, $par_Method,
					$par_Params);
		}
	}

	/**
	 * Generates a script that calls _postBack function of the page for element
	 * events e.g. onChange event.
	 *
	 * @param string $par_Target Name of the target control
	 * @param string $par_Method Name of the target method.
	 * @param array  $par_Params Additional method parameters.
	 *
	 * @return string Javascript code for _postBack function.
	 */
	function MethodScript($par_Target, $par_Method, array $par_Params = array())
	{
		$p = count($par_Params) > 0 ? implode('|', $par_Params) : '';
		return "javascript:_postBack('" . $par_Target . "', '" . $par_Method .
			"', '" . $p . "')";
	}

	/**
	 * Checks the request type is a POST request or not.
	 *
	 * @return boolean
	 */
	function isPost()
	{
		if (array_key_exists('REQUEST_METHOD', $_SERVER)) {
			return $_SERVER['REQUEST_METHOD'] == 'POST';
		}
		return false;
	}

	/**
	 * Checks the request type is a GET request or not.
	 * @return boolean
	 */
	function isGet()
	{
		if (array_key_exists('REQUEST_METHOD', $_SERVER)) {
			return $_SERVER['REQUEST_METHOD'] == 'GET';
		}
		return false;
	}

	/**
	 * Sends a redirect header to client.
	 *
	 * @param string  $par_RedirectURL The URL adress to be redirected.
	 * @param boolean $par_IsRelative  Type of the URL. Is a relative URL or not.
	 */
	function Redirect($par_RedirectURL, $par_IsRelative = true)
	{
		$f = $this->_rewriteActive ? '' : $this->_basePage . '/';
		if ($par_IsRelative) {
			header('Location: ' . $this->IncludePath($f . $par_RedirectURL));
		} else {
			header('Location: ' . $par_RedirectURL);
		}
	}

	/**
	 * Sets the application path or alias for handling uris.
	 *
	 * @param string $par_BasePath
	 */
	function setBasePath($par_BasePath)
	{
		$this->_basePath = $par_BasePath;
	}

	/**
	 * Sets the file name of the front controller.
	 *
	 * @param <type> $par_BasePage File name of the front controller.
	 */
	function setBasePage($par_BasePage)
	{
		$this->_basePage = $par_BasePage;
	}

	/**
	 * Sets the rewriteActive property of PSK_Uri class for uri genaration
	 * functions to include front controller file name into uris or not.
	 *
	 * @param boolean $par_RewriteActive Status of the rewrite module.
	 *                                   If active use <b>true</b> for the parameter value else use <b>false</b>.
	 */
	function setRewriteActive($par_RewriteActive)
	{
		$this->_rewriteActive = $par_RewriteActive;
	}

	/**
	 * Sets modules property.
	 *
	 * @param array $par_Modules An array which contains module names.
	 */
	function setModules(array $par_Modules)
	{
		$this->_modules = $par_Modules;
	}

	/**
	 * Sets the protocol for to help uri generation functions.
	 *
	 * @param string $par_Protocol The protocol which application serving under.
	 *                             Such like http or https...
	 */
	function setProtocol($par_Protocol)
	{
		$this->_protocol = $par_Protocol;
	}

	/**
	 * Sets the locales property.
	 *
	 * @param array $par_Locales Array of supported locales by the application.
	 * <b>Do not call this function directly.</b> It gets called by
	 *                           PSK_Application. If you want to set up locales use
	 * <code>PSK_Application::getInstance()->setLocales(array $par_Locales)
	 * </code>
	 */
	function setLocales(array &$par_Locales)
	{
		$this->_locales = &$par_Locales;
	}

	public function setReRoute($par_ReRoute)
	{
		$this->_reRoute = $par_ReRoute;
	}
}
