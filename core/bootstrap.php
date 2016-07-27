<?php
defined('IN_EXBB') or die;

// Debug flag
define('DEBUG', false);

header('Content-Type: text/html; charset=Windows-1251');

// Минимальная версия PHP, необходимая для запуска форума
define('REQUIRED_PHP_VERSION', '5.4.0');

if (version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<')) {
	include __DIR__.'/errors/phpversion.php';
	die;
}

if (DEBUG) {
	ini_set('display_errors', true);
	ini_set('error_reporting', true);
	error_reporting(E_ALL);
}

include __DIR__ . '/autoloader.php';