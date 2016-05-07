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
 * This is a localizable file for different languages.
 *
 * @package        PSK
 * @subpackage     Application
 * @category       String Constants
 * @author         Namık Kemal Karasu
 */

// Generel purpose strings:
define ('PSK_STR_EXP_GENERALERROR', 'We are having troubles. Please come back again later.');

// Application strings:
if (@defined(DEBUG)) {
	define ('PSK_STR_APP_NOCONTROLLER', 'There is no such a controller:<br/><strong>%s</strong>');
	define ('PSK_STR_APP_NOACTION', 'Specified action is not exist:<br/><strong>%s</strong>');
} else {
	/**
	 * @ignore
	 */
	define ('PSK_STR_APP_NOCONTROLLER', '<big>404 Page not found.</big><br/><strong>%s</strong>');
	/**
	 * @ignore
	 */
	define ('PSK_STR_APP_NOACTION', '<big>404 Page not found.</big><br/><strong>%s</strong>');
}

// Authentication and authorization strings.
define ('PSK_STR_A_NOTAUTHORIZED', 'You are not allowed to access this page. Please %s');
define ('PSK_STR_A_LOGIN', '<strong>login</strong>');

// Login control strings.
define ('PSK_STR_LC_TITLE', 'Please login...');
define ('PSK_STR_LC_TITLEOPEN', 'Welcome...');
define ('PSK_STR_LC_USER', 'User name');
define ('PSK_STR_LC_PASSWORD', 'Password');
define ('PSK_STR_LC_LOGINCOMMAND', 'Log in');
define ('PSK_STR_LC_LOGOUTCOMMAND', 'Log out');
define ('PSK_STR_LC_USERREQUIRED', 'User name required.');
define ('PSK_STR_LC_PASSWORDREQUIRED', 'Password required.');
define ('PSK_STR_LC_WRONGCREDENTIALS', 'Your user name or password is not valid.');
define ('PSK_STR_LC_LOGGEDINAS', 'You have logged in as <em>%s</em>.');

// Database strings:
define ('PSK_STR_TBL_EMTYFIELDLIST', 'Field values cannot be empty at all.');

// DBEditor strings.
define ('PSK_STR_DBE_ADD', 'Add');
define ('PSK_STR_DBE_EDIT', 'Edit');
define ('PSK_STR_DBE_DELETE', 'Delete');
define ('PSK_STR_DBE_CANCEL', 'Cancel');
define ('PSK_STR_DBE_SELECT', 'Select');
define ('PSK_STR_DBE_SAVE', 'Save');
define ('PSK_STR_DBE_DELETEFILE', 'Delete');
define ('PSK_STR_DBE_SAVEFILE', 'Save');
define ('PSK_STR_DBE_CONFIRMDELETE', 'Are you sure to delete this record?');
define ('PSK_STR_DBE_DELETECOMPLETE', 'Record has been deleted.');
define ('PSK_STR_DBE_INSERTCOMPLETE', 'New record has been inserted.');
define ('PSK_STR_DBE_UPDATECOMPLETE', 'Record has been updated.');
define ('PSK_STR_DBE_NOFILE', 'No such a file <strong>%s</strong>.');
define ('PSK_STR_DBE_COULDNOTDELETE', 'File <strong>%s</strong> could not get deleted. Check file permissions.');
define ('PSK_STR_DBE_FILEDELETED', 'File <strong>%s</strong> has been deleted.');
define ('PSK_STR_DBE_FILESAVED', 'File saved.');
define ('PSK_STR_DBE_FILECOULDNOTSAVED', 'File could not saved. Check file permissions.');
define ('PSK_STR_DBE_IMAGEDELETED', 'Image <strong>%s</strong> has been deleted.');
define ('PSK_STR_DBE_IMAGEUPLOADED', 'Image uploaded.');
?>
