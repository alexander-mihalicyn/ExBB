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

function _watchesForums($forums, $forum, $topics) {
	global $fm, $allforums;
	
	$lasts = array();
	foreach ($forums as $f) {
		$lasts[$f] = (isset($allforums[$f]['last_post_id'])) ? $allforums[$f]['last_post_id'] : false;
	}
	
	$topics = array_flip($topics);
	array_walk($topics, '_watchesForumsWalk');
	
	$watches = new Watches;
	$return[0]	= $watches->watchingForums($forums, $lasts);
	$return[1]	= $watches->watchingTopics(array($forum => $topics));
	unset($watches);
	
	return $return;
}

function _watchesForumsFilter($topic) {
	global $topics;
	
	if ($topics[$topic]['state'] == 'moved') {
		return false;
	}
	
	return true;
}

function _watchesForumsWalk(&$info, $topic) {
	global $topics;
	
	$info = $topics[$topic];
}

if ($fm->exbb['watches'] && $fm->user['id']) {
	include_once('Watches.php');
	
	$_watchesForums = _watchesForums($allforums_keys, $forum_id, array_filter($keys, '_watchesForumsFilter'));
}

?>