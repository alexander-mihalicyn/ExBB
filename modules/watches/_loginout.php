<?php

/*
	Watches Mod for ExBB FM 1.0 RC2
	Copyright (c) 2004 - 2011 by Yuri Antonov aka yura3d
	Copyright (c) 2009 - 2011 by ExBB Group
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) {
	die;
}

function _watchesLoginout() {
	$watches = new Watches;
	$watches->upDeadlines();
	unset($watches);
}

if ($fm->exbb['watches']) {
	include_once('Watches.php');
	
	_watchesLoginout();
}