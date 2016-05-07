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
 * PSK_UI_Base class.
 *
 * Abstract base class for Controls. If you need to develop a control that is
 * maneged by controlles, you should inherit it from this class.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface Controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_UI_Base class documentation link.
 */

abstract class PSK_UI_Base extends PSK_OwnedObject
{
	/**
	 * The output data which will be send to client browser by controls.
	 *
	 * @var string
	 */
	protected $__output = '';

	/**
	 * CSS class name for the tag that surrounds the controller.
	 *
	 * @var string
	 */
	protected $__cssClass = '';

	protected $__style = '';

	/**
	 * Determines if the control's output rendered for output or not.
	 * You may use this option to avoid unnecassary renders of a controls output.
	 *
	 * @var boolean
	 */
	protected $__ready = false;

	/**
	 * Determines that contrl's <strong>Initialize<strong> method executed or
	 * not.
	 * @var boolean
	 */
	protected $__initialized = false;
	/**
	 * Determines the generated link types of a control.
	 *
	 * @see psk_const.php for available values under Link types.
	 * @var integer
	 */
	protected $__linkTarget = PSK_LT_ACTION;

	/**
	 * Determines where to store the state data of the control.
	 *
	 * @see psk_const.php for available values under State storages.
	 * @var integer
	 */
	protected $__stateStorage = PSK_SS_DEFAULT;

	/**
	 * Includes property states. A property state identifies that value of any
	 * property set by a setter or not. If it has been set by a setter
	 * Initialize method will not load its stored value.
	 *
	 * @var array
	 */
	protected $__propertyState = array();

	/**
	 * Determines that a control will be rendered or not.
	 *
	 * @var boolean
	 */
	protected $__visible = true;

	/**
	 * Constructor of the PSK_Control class.
	 *
	 * @param PSK_Controller $par_Owner    Owner controller of the control.
	 * @param string         $par_CSSClass CSS class name of wrapper tag.
	 * @param string         $par_Name     Optional name of the control. If not set,
	 *                                     it will be generated.
	 */
	function __construct(PSK_Controller $par_Owner, $par_CSSClass = '',
	                     $par_Name = '')
	{
		if ($par_Name == '') {
			$par_Name = $par_Owner->NewObjectName();
		}
		parent::__construct($par_Owner, $par_Name);
		$par_Owner->AddUIControl($this);
		$this->__cssClass = $par_CSSClass == '' ? $par_Name : $par_CSSClass;
	}

	/**
	 * Echos the output of the control.
	 */
	function Show()
	{
		echo $this->__output;
	}

	/**
	 * Appends par_Data to controls output.
	 *
	 * @param <type> $par_Data
	 */
	protected function __Write($par_Data)
	{
		$this->__output .= $par_Data;
	}

	/**
	 * Retursn the id and name propertyis of the HTML element for rendering the
	 * control.
	 * @return string
	 */
	protected function __getIdName()
	{
		return " id=\"" . $this->__objectName . "\" name=\"" . $this->__objectName . "\" ";
	}

	/**
	 * Retursn the class propertyis of the HTML element for rendering the
	 * control.
	 */
	protected function __getCSSClass()
	{
		return $this->__cssClass == '' ? '' : " class=\"" . $this->__cssClass . "\" ";
	}

	protected function __getStyle()
	{
		return $this->__style == '' ? '' : " style=\"" . $this->__style . "\" ";
	}

	/**
	 * Sets the CSSClass property.
	 *
	 * @param string $par_CssClass
	 */
	function setCssClass($par_CssClass)
	{
		$this->__cssClass = $par_CssClass;
	}

	function setStyle($par_Style)
	{
		$this->__style = $par_Style;
	}

	/**
	 * Sets the LinkTarget property. LinkTarget determines the generated link
	 * types of a controller.
	 *
	 * @see  psk_const.php for available values under Link types.
	 *
	 * @param integer $par_SetLinkTarget
	 */
	function setLinkTarget($par_LinkTarget)
	{
		$this->__linkTarget = $par_LinkTarget;
		/*
		if ($par_LinkTarget == PSK_LT_ACTION)
			$this->__stateStorage = PSK_SS_SESSION;
		*/
	}

	/**
	 * Sets the StateStorage property. StateStorage determines where to store
	 * the state data of the control.
	 *
	 * @param integer $par_StateStorage
	 *
	 * @see psk_const.php for available values under State storages.
	 */
	function setStateStorage($par_StateStorage)
	{
		$this->__stateStorage = $par_StateStorage;
	}

	/**
	 * Sets the Visible property. Visible determines that a control will be
	 * rendered or not.
	 *
	 * @param boolean $par_Visible
	 */
	function setVisible($par_Visible)
	{
		$this->__visible = $par_Visible;
	}

	/**
	 * Returns the link target of the control.
	 *
	 * @return integer
	 */
	function getLinkTarget()
	{
		return $this->__linkTarget;
	}

	/**
	 * Returns the Visible property. Visible determines that a control will be
	 * rendered or not.
	 *
	 * @return Boolen.
	 */
	function getVisible()
	{
		return $this->__visible;
	}

	/**
	 * This method called by owner controller of the control. Implement this
	 * method to initialize your control. You can use this method to load the
	 * state of the control against a post back occurres.
	 */
	abstract function Initialize();

	/**
	 * This method called by owner controller of the control. Implement this
	 * method to generate output of the control.
	 */
	abstract function Render();

	/**
	 * This method called by owner controller of the control. Implement this
	 * method to save the state of the control.
	 */
	abstract function ExportState();
}
