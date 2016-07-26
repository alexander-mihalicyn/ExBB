<?php
defined('IN_EXBB') or die;

// Минимальная версия PHP, необходимая для запуска форума
define('REQUIRED_PHP_VERSION', '5.4.0');

if (version_compare(PHP_VERSION, REQUIRED_PHP_VERSION, '<')) {
	include __DIR__.'/errors/phpversion.php';
	die;
}

include __DIR__ . '/autoloader.php';