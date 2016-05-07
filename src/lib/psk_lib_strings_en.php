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
 * PSK String Constants File.
 * This is a localizable file for different languages and includes only
 * production related strings. This means that if have completed your
 * application your users/clients/wisitors will not face of these strings.
 *
 * @package        PSK
 * @subpackage     Application
 * @category       String Constants for production.
 * @author         Namık Kemal Karasu
 */

// This defination has been added to check if localization file has get loaded
// or not.
define ('STRINGS', true);

// Generel purpose strings:
define ('PSK_STR_ERR_NOOWNERMETHOD', 'Owner method <strong>%s</strong> is not implemented.');
define ('PSK_STR_APP_NOCONTROLLERCLASS', 'Controller class has not been implemented.');
define ('PSK_STR_APP_NOVIEW', 'Specified view is not exist.<br/><strong>%s</strong>');
define ('PSK_STR_APP_NOMODEL', 'There is no such a model:<br/><strong>%s</strong>');
define ('PSK_STR_APP_NOMODELCLASS', '<strong>%s</strong> model class has not been implemented.');
define('PSK_STR_APP_RESPONSE_ERROR', 'Response type already set for another type.');

// Configuration strings:
define ('PSK_STR_CONF_INVALIDOPT', 'Configuration option is invalid.');
define ('PSK_STR_CONF_INVALIDSECT', 'Configuration section is invalid.');
define ('PSK_STR_CONF_INVALIDCONFDATA', 'par_Config is not an array or not an instance of PSK_Config');

// Logging strings:
define ('PSK_STR_LOG_FILEERROR', 'Log file can not be accessed or created.');
define ('PSK_STR_LOG_INVALIDEXCEPTION', 'Not a valid Exception object.');
define ('PSK_STR_LOG_EXCEPTION', 'Exception: ');
define ('PSK_STR_LOG_EXCEPCODE', 'Code: ');
define ('PSK_STR_LOG_EXCEPFILE', 'File: ');
define ('PSK_STR_LOG_EXCEPLINE', 'Line: ');
define ('PSK_STR_LOG_EXCEPTRACE', 'Trace: ');

// Layout strings:
define('PSK_STR_LYT_INVALIDTEMPLATEFILE', 'Template file can not be located.');

// Controller strings:
define ('PSK_STR_CTL_USEDOBJECTNAME', 'Object name is allready used.<br/><strong>%s</strong>');
define ('PSK_STR_CTL_UNDEFINEDMETHOD', 'Method <strong>%s</strong> is not exist in object <strong>%</strong>');
define ('PSK_STR_CTL_CONTROLNOTFOUND', 'Control can not found.');

// Plugin loader strings:
define ('PSK_STR_PLG_NOLIBRARY', 'Plugin library has not been set.');
define ('PSK_STR_PLG_NOCLASS', 'Plugin class has not been set.');
define ('PSK_STR_PLG_CLASSNOTIMPLEMENTED', 'Plugin class not implemented.<br/><strong>%s</strong>');
define ('PSK_STR_PLG_BASEFILENOTEXIST', 'Plugin base class file could not be included.<br/><strong>%s</strong>');
define ('PSK_STR_PLG_CLASSFILENOTEXIST', 'Plugin class file could not be included.<br/><strong>%s</strong>');

// Database strings:
define ('PSK_STR_DB_NOOPENCONNECTION', 'There is no open connection.');
define ('PSK_STR_DB_NOSERVER', 'Connection can not established with the server.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_DB_ACCESSDENIED', 'Access denied for the given user name and password. <br/>Probably you have miss typed the user name or password.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_DB_NODATABASE', 'There is no such a database in this server.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_DB_NODATABASESELECTED', 'Please provide a database name to connect.<br/><em>%s</em>');
define ('PSK_STR_DB_CANTEXECQUERY', 'Could not execute query.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_DB_NOFIELD', 'Query does not include such a field.<br/><strong>%s</strong><br/><em>%s</em>');
define ('PSK_STR_TBL_MISSINGTABLENAME', 'Table name has not been set. Use <strong>setTable</strong> method.');
define ('PSK_STR_TBL_NOSUCHCOLUMN', 'There is no column called <strong>%s</strong> in the <strong>%s</strong> table.');

// Debug Messages.
define ('PSK_DM_MISSINGPIECE', 'Sorry. You see this warning because of that you have found a missing piece of PSK. Please send this message to <a href="mailto:psk@nkkarasu.net">psk@nkkarasu.net</a><br/><strong><code>%s</code></strong><br/><small>This warning is only shown in DEBUG mode.</small>');

// Authentication and authorization strings.
define ('PSK_STR_A_CANTLOAD', 'Authentication class can not load.');
define ('PSK_STR_A_NOCP', 'There is no authentication class enabled. Authentication imposible.');
define ('PSK_STR_A_AUTHNEEDED', 'Authorization requires authentication. Enable one of the authentication providers.');
define ('PSK_STR_A_NODATA', 'No authentication data on database.');

// Login control strings.
define ('PSK_STR_LC_NOAUTH', 'Login control requires an authentication class if self validate enabled.');
?>
