<?php

define('PRODUCTION', false);
define('DEBUG', !PRODUCTION);

if (PRODUCTION) {
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . './lib');
} else {
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . '../src/lib');
}

error_reporting(E_ALL);

date_default_timezone_set('Europe/Istanbul');

include 'psk_const.php';

include 'config/config.php';

require_once 'psk_application.php';

$app = PSK_Application::getInstance();

$app->config->setConfigArray($psk_conf);

$app->DefineLayout('loginLayout', 'index');
$app->DefineLayout('layout', 'PSK_APP');

$app->Run(basename(__FILE__));
$app->End();