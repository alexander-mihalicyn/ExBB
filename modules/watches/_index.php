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

function _watchesIndex($forums) {
	global $allforums;
	
	$lasts = array();
	foreach ($forums as $f) {
		$lasts[$f] = (isset($allforums[$f]['last_post_id'])) ? $allforums[$f]['last_post_id'] : false;
	}
	
	$watches = new Watches;
	$return = $watches->watchingForums($forums, $lasts);
	unset($watches);
	
	return $return;
}

if ($fm->exbb['watches'] && $fm->user['id']) {
	include_once('Watches.php');
	
	$_watchesIndex = _watchesIndex($allforums_keys);
}