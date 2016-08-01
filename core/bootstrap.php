<?php
defined('IN_EXBB') or die;

define('EXBB_VERSION', '1.2.0');
define('EXBB_VERSION_NAME', 'ExBB Forum Engine ' . EXBB_VERSION);

// Debug flag
define('DEBUG', false);

/* Установка внутренней кодировки в UTF-8 */
mb_internal_encoding("UTF-8");
header('Content-Type: text/html; charset=UTF-8');

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
include __DIR__ . '/templateAdapter.php';