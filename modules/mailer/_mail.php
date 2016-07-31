<?php
/*
	Mailer Mod for ExBB FM 1.0 RC1.01
	Copyright (c) 2005 - 2012 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

defined('IN_EXBB') or die;

$backtrace = debug_backtrace();
$file = $backtrace[1]['file'];

if (preg_match('#setmembers\.php$#i', $file)) {
	$method = is_array($args[2]) ? 'toMassQueue' : 'toAccountQueue';
}
else if (preg_match('#post\.php$#i', $file)) {
	$method = 'toSubscribersQueue';
}
else if (preg_match('#(?:profile|register)\.php$#i', $file)) {
	$method = 'toAccountQueue';
}
else {
	$method = 'toPersonQueue';
}

require_once('Mailer.class.php');
$mailer = new Mailer;
call_user_func(array($mailer, $method), $args[0], $args[1], $args[2], $args[3], $args[4]);
