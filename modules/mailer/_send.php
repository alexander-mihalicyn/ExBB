<?php

/*
	Mailer Mod for ExBB FM 1.0 RC1.01
	Copyright (c) 2005 - 2012 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

defined('IN_EXBB') or die;

if ($fm->exbb['mailer'] && preg_match('#index.php$#iu', $_SERVER['SCRIPT_FILENAME'])) {
	include_once('Mailer.class.php');
	$mailer = new Mailer;
	$mailer->send();
}