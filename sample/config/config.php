<?php

$psk_conf = array();

// Application options...

$psk_conf['application']['applicationPath'] = 'application';
#$psk_conf['application']['controllerPath'] = 'controllers';
#$psk_conf['application']['viewPath'] = 'views';
#$psk_conf['application']['templatePath'] = 'templates';
#$psk_conf['application']['i18nPath'] = 'i18n';
#$psk_conf['application']['dbClass'] = 'MySQLi';
#$psk_conf['application']['authClass'] = 'SimpleDB';
#$psk_conf['application']['autzClass'] = 'Simple';
$psk_conf['application']['locales'] = array ('en', 'tr');

// Log options...

$psk_conf['log']['logDestination'] = PSK_LD_FILE;
$psk_conf['log']['logDateTimeFormat'] = 'Ymd-His';
$psk_conf['log']['logPath'] = './log';

// Uri options...

$psk_conf['uri']['basePath'] = 'sample/';
$psk_conf['uri']['rewriteActive'] = false;

// Database options...

#$psk_conf['database']['server'] = 'localhost';
#$psk_conf['database']['user'] = 'root';
#$psk_conf['database']['password'] = '12345';
#$psk_conf['database']['database'] = 'vetclick';
#$psk_conf['database']['persistent'] = true;
#$psk_conf['database']['charSet'] = 'utf8';
#$psk_conf['database']['collation'] = 'utf8_turkish_ci';
#$psk_conf['database']['autoConnect'] = true;

// Simple DB authentication options.
#$psk_conf['authentication']['loginPage'] = 'index';
#$psk_conf['authentication']['params'] = array(
#	'authTable' => 'user',
# 	'userField' => 'user_name',
# 	'passwordField' => 'password',
# 	'fullUserName' => 'VetClick Operator',
# 	'credential' => 'operator'
# );

// Simple authorization options.
#$psk_conf['authorization']['publicController'] = 'index';
#$psk_conf['authorization']['privateModule'] = '__MAIN__';

// Layout options...
#$psk_conf['loginLayout']['template'] = 'login';

$psk_conf['layout']['template'] = 'master';