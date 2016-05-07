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
 * PSK_UI_LinkList class.
 *
 * .
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_UI_LinkList class documentation link
 */

class PSK_UI_LinkList extends PSK_UI_Base
{
	/**
	 * The array wich contains the link texts and paths. Link paths are the
	 * keys of the array.
	 *
	 * @var array
	 */
	private $_links = array();

	/**
	 * Determines which link will have the "psk_active" as class property.
	 * That allows you to show active link with another style.
	 *
	 * @var <type>
	 */
	private $_activeKey = null;

	/**
	 * Determines the target action if link target set to PSK_LT_ACTION
	 *
	 * @var string
	 */
	private $_targetAction = '';

	/**
	 * The data that will be rendered before the links.
	 *
	 * @var string
	 */
	public $preContent = '';

	/**
	 * The data that will be rendered after the links.
	 *
	 * @var string
	 */
	public $endContent = '';

	/**
	 * The data that will be rendered before each link.
	 *
	 * @var string
	 */
	public $preItemContent = '';

	/**
	 * The data that will be rendered after each link.
	 *
	 * @var string
	 */
	public $endItemContent = '';

	/**
	 * <strong>Event </strong>The name of the function which will be called
	 * before a link get rendered.
	 *
	 * @var string
	 */
	public $onRenderLink = '';

	/**
	 * <strong>Event </strong>The name of the function which will be called
	 * after a click happens on a link. [This handler can be used only if
	 * link target has been set to PSK_LT_POSTBACK.] not sure.
	 *
	 * @var string
	 */
	public $onClick = '';

	public $wrap = true;

	/**
	 * Constructor of the PSK_UI_LinkList class.
	 *
	 * @param PSK_Controller $par_Owner    Owner controller of the control.
	 * @param string         $par_CSSClass CSS class name of wrapper tag.
	 * @param string         $par_Name     Optional name of the control. If not set,
	 *                                     it will be generated.
	 */
	function __construct(PSK_Controller $par_Owner, $par_CSSClass = '',
	                     $par_Name = '')
	{
		parent::__construct($par_Owner, $par_CSSClass, $par_Name);

		$this->__propertyState['activeKey'] = 'c';
	}

	/**
	 * Assigns links property.
	 *
	 * @param array $par_Links
	 * @param <type> $par_ActiveKey The key of the link wich will be marked as
	 * active. If you set a link as active that makes it has a class property
	 * as "psk_active". So you can show active link with another style.
	 */
	function setLinks(array $par_Links, $par_ActiveKey = null)
	{
		$this->_links = $par_Links;
		$this->_activeKey = $par_ActiveKey;
	}

	/**
	 * Assigns links from a query. First field in query will be used as link id
	 * and second field in the query will be used as link text, other will ignored.
	 *
	 * @param PSK_DBQuery_Base $par_Query
	 */
	function setLinksFromQuery(PSK_DBQuery_Base $par_Query)
	{
		while ($r = $par_Query->FetchNum()) {
			$this->_links[$r[0]] = $r[1];
		}
	}

	/**
	 * Adds a new link into links array.
	 *
	 * @param string $par_Link    Title of the link.
	 * @param string $par_LinkKey Link key of the link.
	 */
	function AddLink($par_Link, $par_LinkKey)
	{
		$this->_links[$par_LinkKey] = $par_Link;
	}

	/**
	 * Set that which link will be marked as active. If you set a link as
	 * active that makes it has a class property as "psk_active". So you can
	 * show active link with another style.
	 *
	 * @param <type> $par_Args
	 */
	function setActiveKey($par_Args)
	{
		//PSK_Log::getInstance()->WriteDebug($this->getName().'->SetActiveKey');
		$ak = '';
		if (is_array($par_Args)) {
			$ak = $par_Args[0];
		} else {
			$ak = $par_Args;
		}

		if ($this->onClick != '') {
			$args = array('linkId' => &$ak);
			$this->CallOwnerMethod($this->onClick, $args);
		}

		$this->_activeKey = $ak;
		$this->__propertyState['activeKey'] = 's';
	}

	/**
	 * Sets the first link as active link. If you set a link as
	 * active that makes it has a class property as "psk_active". So you can
	 * show active link with another style.
	 */
	function setFirstActive()
	{
		if (count($this->_links) > 0) {
			$keys = array_keys($this->_links);
			$this->_activeKey = $keys[0];
		}
	}

	/**
	 * Sets the target action. Use this setter if you set link target as
	 * PSK_LT_ACTIONPARAM. If not set the current action will be used as
	 * target action.
	 *
	 * @param <type> $par_TargetAction
	 */
	function setTargetAction($par_TargetAction)
	{
		$this->_targetAction = $par_TargetAction;
	}

	/**
	 * Returns the active link's text.
	 */
	function getActiveText()
	{
		$this->Initialize();
		//PSK_Log::getInstance()->WriteDebug('getActiveText');
		if (is_array($this->_links)) {
			if (array_key_exists($this->_activeKey, $this->_links)) {
				return $this->_links[$this->_activeKey];
			}
		}
		return '';
	}

	function getActiveLinkId()
	{
		$this->Initialize();
		return $this->_activeKey;
	}

	function getLinkCount()
	{
		return count($this->_links);
	}

	/**
	 * Initializes the control. This method called by owner controller of the
	 * control.
	 *
	 * @return null
	 */
	function Initialize()
	{
		//PSK_Log::getInstance()->WriteDebug($this->getName().'->initialize');
		if ($this->__initialized) return;

		$a = null;

		if ($this->__propertyState['activeKey'] == 'c') {
			if ($this->__stateStorage == PSK_SS_SESSION) {
				$a = PSK_Application::getInstance()->ReadControlState(
					$this->__objectName . '_activeKey', '',
					$this->__stateStorage);
			} else
				switch ($this->__linkTarget) {
					case PSK_LT_ACTION:
						$a = PSK_Uri::getInstance()->action;
						break;
					case PSK_LT_ACTIONPARAM:
						$a = PSK_Uri::getInstance()->Parameter(0, 0);
						break;
					case PSK_LT_CONTROLLER:
						$a = PSK_Uri::getInstance()->controller;
						break;
					case PSK_LT_MODULE:
						$a = PSK_Uri::getInstance()->module;
						if ($a == '') $a = 'index';
						break;
					case PSK_LT_POSTBACK:
						$a = PSK_Application::getInstance()->ReadControlState(
							$this->__objectName . '_activeKey', '',
							$this->__stateStorage);
						break;
				}
		}
		if (array_key_exists($a, $this->_links)) {
			$this->_activeKey = $a;
			//PSK_Log::getInstance()->WriteDebug($a);
		}

		//PSK_Log::getInstance()->WriteArray($this->_links);

		$this->__initialized = true;
	}

	/**
	 * Renders the output of the controller. This method called by owner
	 * controller of the control.
	 *
	 * @return null
	 */
	function Render()
	{
		if ($this->__ready) return;

		//PSK_Log::getInstance()->WriteArray($this->__propertyState);

		if ($this->wrap) {
			$this->__output = "\n<div id=\"" . $this->__objectName . "\" class=\"" . $this->__cssClass . "\">\n";			
		}
		
		$this->__Write($this->preContent . "\n");

		foreach ($this->_links as $linkId => $linkText) {
			$href = '';
			$ready = $this->CallOwnerMethod($this->onRenderLink, array(
				'href' => &$href,
				'linkId' => $linkId,
				'linkText' => &$linkText,
				'isActive' => $this->_activeKey === $linkId));
			if (!$ready) {

				$uri = PSK_Uri::getInstance();
				switch ($this->__linkTarget) {
					case PSK_LT_ACTION:
						$href = $uri->ActionHref($linkId);
						break;
					case PSK_LT_ACTIONPARAM:
						$ta = $this->_targetAction == '' ? $uri->action :
							$this->_targetAction;
						$href = $uri->ActionHref($ta) . "/" . $linkId;
						break;
					case PSK_LT_CONTROLLER:
						$href = $uri->ControllerHref($linkId);
						break;
					case PSK_LT_MODULE:
						$href = $uri->ModuleHref($linkId);
						break;
					case PSK_LT_POSTBACK:
						$href = "javascript:_postBack('" . $this->__objectName .
							"', 'setActiveKey', '" . $linkId . "')";
						break;
				}
			}

			//PSK_Log::getInstance()->WriteDebug($linkId);
			//PSK_Log::getInstance()->WriteDebug($this->_activeKey);

			//var_dump($linkId);echo "<br/>";
			//var_dump($this->_activeKey);echo "<br/>";

			$active = $this->_activeKey == $linkId
				? 'class="psk_active" ' : '';

			$this->__Write($this->preItemContent . "<a " . $active . "href=\"" .
				$href . "\">" . $linkText . "</a>" . $this->endItemContent . "\n");
		}

		$this->__Write($this->endContent);
		if ($this->wrap) {
			"\n</div>\n";
		};

		$this->__ready = true;
	}

	/**
	 * Saves the state of the control. This method called by owner controller
	 * of the control.
	 */
	function ExportState()
	{
		PSK_Application::getInstance()->WriteControlState(
			$this->__objectName . '_activeKey', $this->_activeKey,
			$this->__stateStorage);
	}
}
