<?php
/**
 * PSK
 *
 * An open source PHP web application development framework.
 *
 * @package      PSK (PHP Sınıf Kütüphanesi)
 * @author       Namık Kemal Karasu
 * @copyright    Copyright (C) Namık Kemal Karasu
 * @license      GPLv3
 * @since        Version 0.
 * @link         http://nkkarasu.net/psk/
 * @link         http://code.google.com/p/phpsk/
 */

/**
 * PSK_HTMLTag class.
 *
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Database and UI integration controls
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_HTMLTag class documentation link.
 */

class PSK_HTMLTag
{
	/**
	 * The tag.
	 * @var string
	 */
	public $tag = '';

	/**
	 * Style of the tag.
	 *
	 * @var string
	 */
	public $style = '';

	/**
	 * CSS Class of the tag.
	 *
	 * @var string
	 */
	public $class = '';

	/**
	 * HTML properties of the tag.
	 *
	 * @var string
	 */
	public $properties = '';

	/**
	 * The inner HTML of the tag.
	 *
	 * @var string
	 */
	public $inner = '';

	/**
	 * Returns the rendered tag.
	 *
	 * @return string Rendered HTML tag.
	 */
	function Render()
	{
		$class = $this->class === '' ? '' : ' class="' . $this->class . '" ';
		$style = $this->style === '' ? '' : ' style="' . $this->style . '" ';
		$props = $this->properties === '' ? '' : ' ' . $this->properties . ' ';

		return "<" . $this->tag . $class . $style . $props . ">" . $this->inner . "</" .
			$this->tag . ">\n";
	}
}

/**
 * PSK_DBEC_Base class.
 *
 * Base class of Database Editor Column classes.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       User Interface
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_DBEC_Base class documentation link
 */
abstract class PSK_DBEC_Base extends PSK_OwnedObject
{
	/**
	 * Determines that a the column will be rendered or not.
	 *
	 * @var boolean
	 */
	public $visible = true;

	/**
	 * Determines that a column is read only or not.
	 *
	 * @var boolean
	 */
	public $readOnly = false;

	/**
	 * The HTML tag which represents the title of the column.
	 *
	 * @var PSK_HTMLTag
	 */
	public $titleTag = null;

	/**
	 * The HTML tag which represents th values of the column.
	 *
	 * @var PSK_HTMLTag
	 */
	public $valueTag = null;

	/**
	 * The event name that occurs when the title tag get rendered.
	 *
	 * @var string
	 */
	public $onTitleRender = '';

	/**
	 * The event name that occurs when the value tag get rendered.
	 *
	 * @var string
	 */
	public $onValueRender = '';

	/**
	 * SQL code that releted to that field. Use this for calculated or
	 * agregated fiels. Ex: <code>SUM(aField)</code> or <code>x + y AS z</code>
	 *
	 * @var string
	 */
	public $fieldSQL = '';

	/**
	 * The value of the field.
	 *
	 * @var <type>
	 */
	public $value = '';

	/**
	 * Parent control which will render the content of the column.
	 *
	 * @var PSK_UI_Base
	 */
	protected $__parent = null;

	/**
	 * Name and Id properties for HTML output.
	 *
	 * @var string
	 */
	protected $__id = '';

	/**
	 * Costructor of PSK_DBEditorColumn
	 *
	 * @param PSK_DBTable $par_Owner Owner database table of the field .
	 * @param string      $par_Name  Name of the object whic is same as field name
	 *                               in the corresponding database table.
	 */
	function __construct(PSK_DBTable $par_Owner, $par_Name, $par_FieldSQL = '')
	{
		parent::__construct($par_Owner, $par_Name);

		$this->titleTag = new PSK_HTMLTag();
		$this->valueTag = new PSK_HTMLTag();

		$this->titleTag->tag = 'th';
		$this->titleTag->inner = $par_Name;
		$this->valueTag->tag = 'td';

		$this->fieldSQL = $par_FieldSQL === '' ? $par_Name : $par_FieldSQL;

		$this->__id = $this->__owner->getTable() . "_" . $this->getName();
	}

	/**
	 * Calls a render event handler in case of a render event occurs.
	 *
	 * @param PSK_HTMLTag $par_HTMLTag
	 * @param string      $par_EventFunc
	 * @param string      $par_DataMode
	 */
	protected function __OnRender(PSK_HTMLTag $par_HTMLTag, $par_EventFunc,
	                              $par_DataMode)
	{
		if ($par_EventFunc != '') {
			$args = array(
				'dataMode' => $par_DataMode,
				'tag' => &$par_HTMLTag->tag,
				'inner' => &$par_HTMLTag->inner,
				'class' => &$par_HTMLTag->class,
				'style' => &$par_HTMLTag->style,
				'props' => &$par_HTMLTag->properties);
			$this->CallOwnerMethod($par_EventFunc, $args);
		}
	}

	/**
	 * Returns the rendered content of the title of the column.
	 *
	 * @param integer $par_DataMode
	 *
	 * @see psk_consts.php for Data Mode constants.
	 *
	 * @param integet $par_ViewMode
	 *
	 * @see psk_consts.php for View Mode constants.
	 */
	function RenderTitle($par_DataMode = PSK_DM_VIEW, $par_ViewMode = PSK_VM_LIST)
	{
		if (!$this->visible) return;

		$this->__OnRender($this->titleTag, $this->onTitleRender, $par_DataMode);

		return $this->titleTag->Render();
	}

	/**
	 * Returns the renderend content of the value of the column.
	 *
	 * @param integer $par_DataMode
	 *
	 * @see psk_consts.php for Data Mode constants.
	 *
	 * @param integer $par_ViewMode
	 *
	 * @see psk_consts.php for View Mode constants.
	 */
	abstract function RenderValue($par_DataMode = PSK_DM_VIEW,
	                              $par_ViewMode = PSK_VM_LIST);

	/**
	 * Gets the value of the field from _POST array.
	 */
	function ReadFromPost()
	{
		if (isset($_POST[$this->__id]) && ($_POST[$this->__id] != '')) {
			return $_POST[$this->__id];
		}
		return null;
	}

	/**
	 * Sets the parent control of the column. The parent control is actually a
	 * DBEditor that responsible to visualize this field.
	 *
	 * @param PSK_UI_Base $par_Parent
	 */
	function setParent(PSK_UI_Base $par_Parent)
	{
		$this->__parent = $par_Parent;
	}

	/**
	 * Returns the id of the colunm.
	 *
	 * @return string
	 */
	function getId()
	{
		return $this->__id;
	}
}
