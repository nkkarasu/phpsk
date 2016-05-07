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
 * PSK constants
 *
 * @package     PSK
 * @subpackage  Libraries
 * @category    Constants
 * @author      Namık Kemal Karasu
 * @link        TODO add constants documentation link.
 */

//Log event types.

/**
 * Event type constant for system errors.
 */
define('PSK_ET_SYSERROR', 0);

/**
 * Event type constant for system warnings.
 */
define('PSK_ET_SYSWARNING', 1);

/**
 * Event type constant for application errors.
 */
define('PSK_ET_APPERROR', 10);

/**
 * Event type constant for application warnings.
 */
define('PSK_ET_APPWARNING', 11);

/**
 * Event type constant for information messages.
 */
define('PSK_ET_APPINFORMATION', 12);

/**
 * Event type constant for debug messages.
 * Debug messages get processed only if <b>DEBUG</b> directive defined.
 */
define('PSK_ET_DEBUGMESSAGE', 20);

// Log message destinations.

/**
 * Enables system log messages to be saved in a file.
 */
define('PSK_LD_FILE', 0);

/**
 * Enables system log messages to be saved in memory. So you can echo
 * system messages in your web pages.
 */
define('PSK_LD_VARIABLE', 1);

// Link types. (for PSK_UI_LinkList)

/**
 * Link type constant for custom links. If you set LinkType of a control as
 * PSK_LT_CUSTOM you should define an onCustomLink event handler. And generate
 * your own links for the control.
 */
define('PSK_LT_CUSTOM', 0);

/**
 * Link type constant for to generate links that targets actions in controller.
 */
define('PSK_LT_ACTION', 1);

/**
 * Link type constant for to generate links that targets a controller and its
 * index action.
 */
define('PSK_LT_CONTROLLER', 2);

/**
 * Link type constant for to generate links that targets a module, its
 * index controller and its index action.
 */
define('PSK_LT_MODULE', 3);

/**
 * Link type constat for to generate links that couses a post back (like a
 * submit button).
 */
define('PSK_LT_POSTBACK', 4);

/**
 * Link type constat for to generate links that targets actions in controller.
 * And passes its keys as action parameters.
 */
define('PSK_LT_ACTIONPARAM', 5);

// State storages.

/**
 * State storage constant. To use application wide state storage option, set
 * controls state storage to PSK_SS_DEFAULT
 */
define('PSK_SS_DEFAULT', 0);

/**
 * State storage constant. To save control's states in hidden fields set
 * controls state storage to PSK_SS_HIDDEN
 */
define('PSK_SS_HIDDEN', 1);

/**
 * State storage constant. To save control's states in user session set
 * controls state storage to PSK_SS_SESSION
 */
define('PSK_SS_SESSION', 2);

/**
 * State storage constant. To save control's states in a cookie set
 * controls state storage to PSK_SS_COOKIE.
 */
define('PSK_SS_COOKIE', 3);

// Session states.

/**
 * Session state constant. Determines that session is closed.
 */
define('PSK_SS_CLOSED', 0);

/**
 * Session state constant. Determines that session is opened.
 */
define('PSK_SS_OPEN', 1);

// Database Constants.

// Key constants.

/**
 * Database key constraint constant. Indicates that field is dependent to key
 * constraint but it has not been known by PSK. If you see such a situation
 * please repeort that to psk@nkkarasu.net.
 */
define('PSK_DBK_UNKNOWN', 'unknown');

/**
 * Database key constraint constant. Indicates that field is not a depends to
 * any key constraint.
 */
define('PSK_DBK_NONE', '');

/**
 * Database key constraint constant. Indicates that field is depends to a
 * primary key constraint.
 */
define('PSK_DBK_PRIMARY', 'primary');

/**
 * Database key constraint constant. Indicates that field is depends to a
 * unique key constraint.
 */
define('PSK_DBK_UNIQUE', 'unique');

/**
 * Database key constraint constant. Indicates that field is depends to a
 * foreign key constraint.
 */
define('PSK_DBK_FOREIGN', 'foreign');

/**
 * Database key constraint constant. Indicates that field is depends to more
 * than one key constraint.
 */
define('PSK_DBK_MULTIPLE', 'multiple');

// Field type constants.

/**
 * Database field type constant. Indicates that field has not been mapped to
 * any constant by PSK. If you see such a situation
 * please repeort that to psk@nkkarasu.net.
 */
define('PSK_DBT_UNKNOWN', 'unknown');

/**
 * Database field type constant. This is a fake constant for
 * PSK_DBDriver_Base::__MapParser() to run properly.
 */
define('PSK_DBT_NONE', 'none');

/**
 * Database field type constant. Indicates that field has been mapped as Integer
 * All integer field types (such as SmallInt, Integer, Long Integer ...)has been
 * mapped to that constant.
 */
define('PSK_DBT_INTEGER', 'integer');

/**
 * Database field type constant. Indicates that field has been mapped as Float
 * All floating point field types (such as Single, Double, Decimal ...) has been
 * mapped to that constant.
 */
define('PSK_DBT_FLOAT', 'float');

/**
 * Database field type constant. Indicates that field has been mapped as String
 * All string field types (such as Char, VarChar, Text...) has been
 * mapped to that constant.
 */
define('PSK_DBT_STRING', 'string');

/**
 * Database field type constant. Indicates that field has been mapped as Text
 * All text field types (such as text, mediumtext, longtext...) has been
 * mapped to that constant.
 */
define('PSK_DBT_TEXT', 'text');

/**
 * Database field type constant. Indicates that field has been mapped as
 * DateTime All date time field types (such as Date, Time, DateTime, TimeStamp)
 * has been mapped to that constant.
 */
define('PSK_DBT_DATETIME', 'datetime');

/**
 * Database field type constant. Indicates that field has been mapped as BLOB
 * All BLOB field types has been mapped to that constant.
 */
define('PSK_DBT_BLOB', 'blob');

// Read Methods

/**
 * Read method type constant. Use this to retrive data as an associative array.
 */
define('PSK_RM_ASSOC', 1);

/**
 * Read method type constant. Use this to retrive data as a numeric array.
 */
define('PSK_RM_NUM', 2);

/**
 * Read method type constant. Use this to retrive data as an object.
 */
define('PSK_RM_OBJECT', 3);

// User Types.

/**
 * User type constant. Indicates that user is a none authenticated guest user.
 */
define('PSK_UT_GUEST', 0);

/**
 * User type constant. Indicates that user is an authenticated user.
 */
define('PSK_UT_AUTHUSER', 1);

/**
 * Rendering mode constant of a data in a table. Indicates that value will be
 * rendered for viewing.
 */
define('PSK_DM_VIEW', 0);

/**
 * Rendering mode constant of a data in a table. Indicates that value will be
 * rendered as selected.
 */
define('PSK_DM_SELECT', 1);

/**
 * Rendering mode constant of a data in a table. Indicates that value will be
 * rendered for insertion.
 */
define('PSK_DM_INSERT', 2);

/**
 * Rendering mode constant of a data in a table. Indicates that value will be
 * rendered for editing.
 */
define('PSK_DM_EDIT', 3);

/**
 * Rendering mode constant of a data in a table. Indicates that value will be
 * rendered for deletion.
 */
define('PSK_DM_DELETE', 4);

/**
 * View mode constant for PSK_UI_DBEditor. Indicates that PSK_UI_DBEditor will
 * be rendered as a list.
 */
define('PSK_VM_LIST', 0);

/**
 * View mode constant for PSK_UI_DBEditor. Indicates that PSK_UI_DBEditor will
 * be rendered as a form.
 */
define('PSK_VM_FORM', 1);

/**
 * Text type constant for PSK_UI_Text. Indicates that the type of PSK_UI_TEXT
 * will be text.
 */
define ('PSK_IT_TEXT', 'text');

define ('PSK_IT_EMAIL', 'email');

define ('PSK_IT_URL', 'url');

define ('PSK_IT_TEL', 'tel');

define ('PSK_IT_RANGE', 'range');

define ('PSK_IT_COLOR', 'color');

define ('PSK_IT_NUMBER', 'number');

define ('PSK_IT_DATE', 'date');

define ('PSK_IT_DATETIME', 'datetime');

define ('PSK_IT_MONTH', 'month');

define ('PSK_IT_WEEK', 'week');

define ('PSK_IT_TIME', 'time');

/**
 * Text type constant for PSK_UI_Text. Indicates that the type of PSK_UI_TEXT
 * will be password.
 */
define ('PSK_IT_PASSWORD', 'password');

// Image File Types

define ('PSK_IFT_NONE', 0);
define ('PSK_IFT_JPEG', 1);
define ('PSK_IFT_GIF', 2);
define ('PSK_IFT_PNG', 3);

// Response types

define('PSK_RT_NONE', 0);
define('PSK_RT_VIEW', 1);
define('PSK_RT_PARTIAL_VIEW', 2);
define('PSK_RT_JSON', 3);
define('PSK_RT_BLANK', 4);
