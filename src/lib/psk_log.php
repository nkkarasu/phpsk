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
 * PSK_Log class.
 *
 * Log and message management class.
 *
 * @package        PSK
 * @subpackage     Libraries
 * @category       Log
 * @author         Namık Kemal Karasu
 * @link           TODO add PSK_Log class documentation link.
 */
class PSK_Log extends PSK_Object
{
	/**
	 * Singleton instance of PSK_Log.
	 *
	 * @var PSK_Log
	 */
	protected static $__instance = null;

	/**
	 * Describes where to store system log messages.
	 * @see psk_const.php for available values under <b>Log message
	 *      destinatons</b>.
	 * @var Integer
	 */
	private $_logDestination = PSK_LD_VARIABLE;

	/**
	 * Describes date time format for log events.
	 *
	 * @var string
	 */
	private $_logDateTimeFormat = 'Ymd-His';

	/**
	 * Path for log files <b>(!) Write access should be granted for
	 * selected path.</b>
	 *
	 * @var string
	 */
	private $_logPath = './log';

	/**
	 * Logging fields. Log events will be append to that variables.
	 *
	 * @var string
	 */
	private $_SYSERR = '';
	private $_SYSWRN = '';
	private $_APPERR = '';
	private $_APPWRN = '';
	private $_APPINF = '';
	private $_DBGMSG = '';

	/**
	 * Message container variable names.
	 *
	 * @var array
	 */
	private $_logVars = array(
		PSK_ET_SYSERROR => '_SYSERR',
		PSK_ET_SYSWARNING => '_SYSWRN',
		PSK_ET_APPERROR => '_APPERR',
		PSK_ET_APPWARNING => '_APPWRN',
		PSK_ET_APPINFORMATION => '_APPINF',
		PSK_ET_DEBUGMESSAGE => '_DBGMSG');

	private $_logClasses = array(
		PSK_ET_SYSERROR => 'danger',
		PSK_ET_SYSWARNING => 'warning',
		PSK_ET_APPERROR => 'danger',
		PSK_ET_APPWARNING => 'warning',
		PSK_ET_APPINFORMATION => 'info',
		PSK_ET_DEBUGMESSAGE => 'success');

	/**
	 * Let you to avoid to show log twice.
	 *
	 * @var boolean
	 */
	private $_logRendered = false;

	/**
	 * Contructor of PSK_Log.
	 *
	 * @param string $par_Name A name for the object.
	 */
	function  __construct($par_Name = '')
	{
		parent::__construct($par_Name);
	}

	/**
	 * Returns singleton instance of PSK_Log.
	 *
	 * @return PSK_Log
	 */
	public static function getInstance()
	{
		if (null === self::$__instance) {
			self::$__instance = new self();
		}
		return self::$__instance;
	}

	/**
	 *    Appends log events to log fields.
	 *
	 * @param string  $par_Event     Log message to store.
	 * @param integer $par_EventType Log event type.
	 *
	 * @see Log event constants in psk_const.php file for details of
	 *      event types.
	 */
	private function _WriteLog($par_Event, $par_EventType = PSK_ET_APPERROR)
	{
		$logVar = $this->_logVars[$par_EventType];
		$this->$logVar .= "<li>" . $par_Event . "</li>\n";
	}

	/**
	 * Converts an array to defination list.
	 *
	 * @param array $par_Array Array to converted to defination list.
	 *
	 * @return string
	 */
	private function _ArrayToDefinationList(array $par_Array)
	{
		$out = "<dl>\n";

		foreach ($par_Array as $key => $item) {
			$out .= "\t<dt><small>" . $key . "</small></dt><dd>";
			if (is_array($item)) $out .= $this->_ArrayToDefinationList($item);
			else $out .= $item;
			$out .= "</dd>\n";
		}

		$out .= "</dl>\n";
		return $out;
	}

	/**
	 *    Writes events to log.
	 *
	 * @param $par_Event
	 * @param int $par_EventType Log event type.
	 *
	 * @throws Exception
	 * @internal param $ <type> $par_Event Log message to store.
	 * @see   Log event constants in psk_const.php file for details of
	 *        event types.
	 */
	function WriteLog($par_Event, $par_EventType = PSK_ET_APPERROR)
	{
		if (is_array($par_Event))
			$event = $this->_ArrayToDefinationList($par_Event);
		else
			$event = $par_Event;

		if ($par_EventType < PSK_ET_APPERROR) {
			if ($this->_logDestination === PSK_LD_FILE) {
				$logData = "<strong>" . date($this->_logDateTimeFormat) . "|" .
					ltrim($this->_logVars[$par_EventType], '_') . "</strong> - " .
					$event . "<br/>\n";
				rtrim($this->_logPath, '/');
				$logFile = $this->_logPath . '/' . date('Ymd') . '.html';
				$fp = @fopen($logFile, "a");
				if (!$fp) {
					throw new Exception(PSK_STR_LOG_FILEERROR);
				}
				flock($fp, LOCK_EX);
				if (filesize($logFile) == 0) {
					fwrite($fp, "<style>body { font-family: monospace; } </style>\n");
				}
				fwrite($fp, $logData);
				flock($fp, LOCK_UN);
				fclose($fp);
				clearstatcache();
			} else {
				$this->_WriteLog($event, $par_EventType);
			}
		} else {
			$this->_WriteLog($event, $par_EventType);
		}
	}

	/**
	 * Writes messages directly as debug messages if DEBUG is defined.
	 *
	 * @param string $par_Event Log message to write.
	 */
	function WriteDebug($par_Event)
	{
		if (defined('DEBUG')) {
			$this->_WriteLog($par_Event, PSK_ET_DEBUGMESSAGE);
		}
	}

	/**
	 * Writes information about an exception.
	 *
	 * @param Exception $par_Exception Exception object to keep log data.
	 */
	function WriteException(Exception $par_Exception)
	{
		$log_Data = "<dl>\n\t" .
			"<dt><small>" . PSK_STR_LOG_EXCEPTION . "</small></dt>" .
			"<dd>" . $par_Exception->getMessage() . "</dd>\n\t" .
			"<dt><small>" . PSK_STR_LOG_EXCEPCODE . "</small></dt>" .
			"<dd>" . $par_Exception->getCode() . "</dd>\n\t" .
			"<dt><small>" . PSK_STR_LOG_EXCEPFILE . "</small></dt>" .
			"<dd>" . $par_Exception->getFile() . "</dd>" .
			"<dt><small>" . PSK_STR_LOG_EXCEPLINE . "</small></dt>" .
			"<dd>" . $par_Exception->getLine() . "</dd>\n\t" .
			"<dt><small>" . PSK_STR_LOG_EXCEPTRACE . "</small></dt>" .
			"<dd>" . str_replace('#', '<br/>',
			ltrim($par_Exception->getTraceAsString(), '#')) .
			"</dd>\n</dl>\n";
		if (!defined('DEBUG')) {
			if ($par_Exception->getCode() === 0) {
				$this->WriteLog(PSK_STR_EXP_GENERALERROR);
				if ($this->_logDestination === PSK_LD_FILE) {
					$this->WriteLog($log_Data, PSK_ET_SYSERROR);
				}
			} else {
				$this->WriteLog($par_Exception->getMessage());
			}
		} else {
			$this->WriteLog($log_Data);
		}
	}

	/**
	 * Converts an array to defination list an writes it into log.
	 *
	 * @param array   $par_Array     Array to be written into log.
	 * @param integer $par_EventType Log event type.
	 *
	 * @see Log event constants in psk_const.php file for details of
	 *      event types.
	 */
	function WriteArray(array $par_Array, $par_EventType = PSK_ET_DEBUGMESSAGE)
	{
		$this->WriteLog($this->_ArrayToDefinationList($par_Array),
			$par_EventType);
	}

	/**
	 * Echos specified log event.
	 *
	 * @param integer $par_EventType Log event type.
	 *
	 * @see Log event constants in psk_const.php file for details of
	 *      event types.
	 */
	function ShowEvents($par_EventType = PSK_LT_APPERROR)
	{
		$logVar = $this->_logVars[$par_EventType];
		$logClass = $this->_logClasses[$par_EventType];
		if ($this->$logVar != '') {
			echo '<div class="alert alert-' . $logClass .
				'"><ul>' . $this->$logVar . "</ul></div>\n";
		}
	}

	/**
	 * Echos all log messages.
	 */
	function ShowLog()
	{
		if ($this->_logRendered) return;
		if (defined('DEBUG')) {
			$this->ShowEvents(PSK_ET_DEBUGMESSAGE);
		}
		if ($this->_logDestination === PSK_LD_VARIABLE) {
			$this->ShowEvents(PSK_ET_SYSERROR);
			$this->ShowEvents(PSK_ET_SYSWARNING);
		}
		$this->ShowEvents(PSK_ET_APPERROR);
		$this->ShowEvents(PSK_ET_APPWARNING);
		$this->ShowEvents(PSK_ET_APPINFORMATION);
		$this->_logRendered = true;
	}

	/**
	 * Sets the log destination.
	 *
	 * @param integer $par_LogDestination Destianation of system log messages.
	 *
	 * @see Log destination constants in psk_const.php file for details of
	 *      log destianation types.
	 */
	function setLogDestination($par_LogDestination)
	{
		$this->_logDestination = $par_LogDestination;
	}

	/**
	 * Sets date time format for log events.
	 *
	 * @param string $par_LogDateTimeFormat Log date time format.
	 */
	function setLogDateTimeFormat($par_LogDateTimeFormat)
	{
		$this->_logDateTimeFormat = $par_LogDateTimeFormat;
	}

	/**
	 * Sets the path for log files.
	 *
	 * @param string $par_LogPath Path for the log files.
	 */
	function setLogPath($par_LogPath)
	{
		$this->_logPath = $par_LogPath;
	}
}
