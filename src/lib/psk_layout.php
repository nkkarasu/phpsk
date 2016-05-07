<?php

class PSK_Layout extends PSK_Object
{

	protected static $__instance = null;
	private $_scripts = array();
	private $_styles = array();
	private $_views = array();
	private $_template = '';
	private $_templateName = '';

	public static function getInstance()
	{
		if (null === self::$__instance) {
			self::$__instance = new self();
		}
		return self::$__instance;
	}

	function __construct($par_Name = '')
	{
		parent::__construct($par_Name);
	}

	function AddScript($par_Script, $par_Section = '__MAIN__')
	{
		$this->_scripts[] = array(
			'section' => $par_Section,
			'script' => $par_Script,
			'rendered' => FALSE);
	}

	function AddStyle($par_Style, $par_Section = '__MAIN__')
	{
		$this->_styles[] = array(
			'section' => $par_Section,
			'style' => $par_Style,
			'rendered' => FALSE);
	}

	/**
	 * @param $par_View
	 * @param string $par_Section
	 * @throws Exception
	 */
	function AddView($par_View, $par_Section = '__MAIN__')
	{
		$app = PSK_Application::getInstance();

		$viewFile = $app->getViewFile($par_View);

		if (is_file($viewFile)) {
			$this->_views[] = array('section' => $par_Section,
				'view' => $par_View, 'viewfile' => $viewFile,
				'rendered' => FALSE);
		} else {
			throw new Exception(sprintf(PSK_STR_APP_NOVIEW, $viewFile));
		}
	}

	function IncludeView($par_View = '', $par_Section = '__MAIN__')
	{
		foreach ($this->_views as &$view) {
			if ($par_Section == $view['section']) {
				if (($par_View == '') && ($par_Section == $view['section'])) {
					if ($view['rendered'] === FALSE) {
						include $view['viewfile'];
						$view['rendered'] = TRUE;
					}
				} else {
					if (($par_View == $view['view']) &&
						($view['rendered'] === FALSE) &&
						($par_Section == $view['section'])
					) {
						include $view['viewfile'];
						$view['rendered'] = TRUE;
					}
				}
			}
		}
	}

	function RenderStyleLinks($par_Style = '', $par_Section = '__MAIN__')
	{
		foreach($this->_styles as &$style) {
			if ($par_Section == $style['section']) {
				if (($par_Style == '') && ($par_Section == $style['section'])) {
					if ($style['rendered'] === false) {
						echo "<link rel=\"stylesheet\" href=\"" .
							PSK_Uri::getInstance()->IncludePath($style['style']) . "\" />\n";
						$style['rendered'] = true;
					}
				} else {
					if (($par_Style == $style['style']) &&
						($style['rendered'] === true) &&
						($par_Section == $style['section'])
					) {
						echo "<link rel=\"stylesheet\" href=\"" .
							PSK_Uri::getInstance()->IncludePath($style['style']) . "\" />\n";
						$style['rendered'] = true;
					}
				}
			}
		}
	}

	function RenderScriptLinks($par_Script = '', $par_Section = '__MAIN__')
	{
		foreach($this->_scripts as &$script) {
			if ($par_Section == $script['section']) {
				if (($par_Script == '') && ($par_Section == $script['section'])) {
					if ($script['rendered'] === false) {
						echo "<script type=\"text/javascript\" src=\"" .
							PSK_Uri::getInstance()->IncludePath($script['script']) . "\"></script>\n";
						$script['rendered'] = true;
					}
				} else {
					if (($par_Script == $script['style']) &&
						($script['rendered'] === true) &&
						($par_Section == $script['section'])
					) {
						echo "<script type=\"text/javascript\" src=\"" .
							PSK_Uri::getInstance()->IncludePath($script['script']) . "\"></script>\n";
						$script['rendered'] = true;
					}
				}
			}
		}
	}

	function setTemplate($par_Template)
	{
		if (trim($par_Template) == '') {
			return;
		}
		$tf = PSK_Application::getInstance()->getFullTemplatePath() .
			$par_Template . '.phtml';
		if (is_file($tf)) {
			$this->_template = $tf;
			$this->_templateName = $par_Template;
		} else {
			throw new Exception(PSK_STR_LYT_INVALIDTEMPLATEFILE . ' : ' . $tf);
		}
	}

	function setStyles(array $par_Styles)
	{
		$this->_styles = array();
		foreach ($par_Styles as $style) {
			$this->AddStyle($style);
		}
	}

	function setScripts(array $par_Scripts)
	{
		$this->_scripts = array();
		foreach ($par_Scripts as $script) {
			$this->AddScript($script);
		}
	}

	function getTemplate()
	{
		return $this->_template;
	}

	function getTemplateName()
	{
		return $this->_templateName;
	}

}
