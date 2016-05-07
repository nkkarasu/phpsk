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
 * PSK_UI_Login class.
 *
 * Login box control.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_UI_Button class documentation link
 */

class PSK_UI_Button extends PSK_UI_Base
{
	/**
	 * The text will be shown on submit button.
	 *
	 * @var string
	 */
	private $_text = '';

	private $_tag = 'button';

	private $_postBackFunction = '_postBack';

	/**
	 * <strong>Event </strong>The name of the function which will be called
	 * after a click event occurs.
	 *
	 * @var string
	 */
	public $onClick = '';

	/**
	 * OnClick event my invoke another controls method. To do that set
	 * clickTargetObject as target objects name, clickTargetMethod as
	 * target object's related method name and clickTargetParams as
	 * target object's related method's params.
	 *
	 * @var string
	 */
	public $clickTargetObject = '';

	/**
	 * OnClick event my invoke another controls method. To do that set
	 * clickTargetObject as target objects name, clickTargetMethod as
	 * target object's related method name and clickTargetParams as
	 * target object's related method's params.
	 *
	 * @var string
	 */
	public $clickTargetMethod = '';

	/**
	 * OnClick event my invoke another controls method. To do that set
	 * clickTargetObject as target objects name, clickTargetMethod as
	 * target object's related method name and clickTargetParams as
	 * target object's related method's params.
	 *
	 * @var string
	 */

	public $clickTargetParams = array();

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::__construct()
	 */
	function __construct(PSK_Controller $par_Owner, $par_CSSClass = '',
	                     $par_Name = '')
	{
		parent::__construct($par_Owner, $par_CSSClass, $par_Name);
		$this->clickTargetObject = $this->getName();
		$this->clickTargetMethod = 'click';
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Initialize()
	 */
	function Initialize()
	{

	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::Render()
	 */
	function Render()
	{
		if ($this->__ready) return;

		$this->__Write("\n<".$this->_tag . $this->__getIdName() .
			$this->__getCSSClass() . $this->__getStyle() ."onclick=\"".
			PSK_Uri::getInstance()->CustomPostHref($this->_postBackFunction, $this->clickTargetObject,
				$this->clickTargetMethod, $this->clickTargetParams).
			"\">");
		$this->__Write($this->_text);
		$this->__Write("</".$this->_tag.">\n");
	}

	/**
	 * (non-PHPdoc)
	 * @see PSK_UI_Base::ExportState()
	 */
	function ExportState()
	{
	}

	/**
	 * Assigns the text property of the control.
	 *
	 * @param string $par_Text Value of the text proeprty.
	 */
	function setText($par_Text)
	{
		$this->_text = $par_Text;
	}

	function setTag($par_Tag)
	{
		$this->_tag = $par_Tag;
	}

	function setPostBackFunction($par_PostBackFunction)
	{
		$this->_postBackFunction = $par_PostBackFunction;
	}

	function click()
	{
		if ($this->onClick != '') {
			$args = array();
			$this->CallOwnerMethod($this->onClick, $args);
		}
	}
}
