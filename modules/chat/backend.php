<?php

/*
	Chat for ExBB FM 1.0 RC2
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) die('Emo sucks;)');
require_once('modules/chat/common.php');

switch ($fm->_String('action')) {
	case 'update':		update();
							break;
	case 'send':		send();
							break;
	case 'informer': 	informer();
							break;
}

function status_class($st) {
	switch ($st) {
		case 'ad':		return ' class="admin"';
		case 'sm':		return ' class="supmoder"';
		default:		return '';
	}
}

function update() {
	global $fm;
	
	if (!$fm->user['id']) die;
	
	$time = $fm->_Nowtime;
	
	$online		= $fm->_Read2Write($fp_online, CHAT_ONLINE);
	$messages	= $fm->_Read2Write($fp_messages, CHAT_MESSAGES);
	
	$login = !isset($online[$fm->user['id']]);
	
	$online[$fm->user['id']] = array(
		'name'		=> $fm->user['name'],
		'st'		=> $fm->user['status'],
		'time'		=> $time
	);
	
	foreach ($online as $id => $user)
		if ($time - $user['time'] > 30) {
			$messages[md5($user['time'].$user['id'].'logout')] = array(
				'act'	=> 'logout',
				'id'	=> $user['id'],
				'name'	=> $user['name'],
				'st'	=> $user['st'],
				'time'	=> $user['time'] + 30
			);
			
			unset($online[$id]);
		}
	
	if ($login)
		$messages[md5($time.$fm->user['id'].'login')] = array(
			'act'	=> 'login',
			'id'	=> $fm->user['id'],
			'name'	=> $fm->user['name'],
			'st'	=> $fm->user['status'],
			'time'	=> $time
		);
	
	$fm->_Write($fp_online, $online);
	$fm->_Write($fp_messages, $messages);
	
	$fm->_String('last');
	
	$show_messages = '';
	$show = $last_day = 0;
	foreach ($messages as $msg_id => $msg) {
		if ($show && ($msg['id'] != $fm->user['id'] || isset($msg['act'])) || $fm->input['last'] === '') {
			if ($last_day != ($day = date('j', $msg['time']))) {
				list($month, $year) = explode(' ', date('n Y', $msg['time']));
				$show_messages .= '<div class="chat_info">&bull; '.$day.' <span id="month'.$month.'"></span>&nbsp;'.$year.' &bull;</div>';
				
				$last_day = $day;
			}
			
			if (!isset($msg['act'])) {
				$class_name = status_class($msg['st']);
				
				$class_msg = '';
				
				if (strstr($fm->_LowerCase($msg['text']), $fm->_LowerCase($fm->user['name']).':') !== FALSE && $fm->user['id'] != $msg['id'])
					$class_msg = ' class="chat_foryou"';
				
				$show_messages .= '<div id="msg_'.$msg_id.'"'.$class_msg.'>['.date('H:i:s', $msg['time'] + $fm->user['timedif'] * 3600).'] <a href="#" onClick="return pasteN(\''.$msg['name'].'\');"'.$class_name.'>'.$msg['name'].'</a>: <span id="msg">'.$msg['text'].'</span></div>';
			}
			else
				switch ($msg['act']) {
					case 'login':	$class_name = status_class($msg['st']);
									$show_messages .= '<div id="msg_'.$msg_id.'">['.date('H:i:s', $msg['time'] + $fm->user['timedif'] * 3600).'] <span id="login" class="chat_info"><a href="#" onClick="return pasteN(\''.$msg['name'].'\');"'.$class_name.'>'.$msg['name'].'</a></span></div>';
										break;
					case 'logout':	$class_name = status_class($msg['st']);
									$show_messages .= '<div id="msg_'.$msg_id.'">['.date('H:i:s', $msg['time'] + $fm->user['timedif'] * 3600).'] <span id="logout" class="chat_info"><a href="#" onClick="return pasteN(\''.$msg['name'].'\');"'.$class_name.'>'.$msg['name'].'</a></span></div>';
										break;
				}
		}
		
		if ($fm->input['last'] == $msg_id) {
			$show = 1;
			$last_day = date('j', $msg['time']);
		}
	}
	
	$show_online = '';
	foreach ($online as $id => $user) {
		$class = status_class($user['st']);
		
		$show_online .= '&bull; <a href="#" onClick="return pasteN(\''.$user['name'].'\');"'.$class.'>'.$user['name'].'</a><br>';
	}
	
	$GLOBALS['_RESULT'] = array(
		'error'		=> 0,
		'messages'	=> $show_messages,
		'last'		=> (isset($msg_id)) ? $msg_id : '',
		'now'		=> count($online),
		'online'	=> $show_online
	);
}

function send() {
	global $fm;
	
	if (!$fm->user['id'] || $fm->_String('msg') === '') die;
$maxlength = 150;
 if (strlen($fm->input['msg']) > $maxlength)
 $fm->input['msg'] = substr($fm->input['msg'], 0, $maxlength).'...';
	
	$time = $fm->_Nowtime;
	
	$config = $fm->_Read(CHAT_CONFIG);
	
	$messages = $fm->_Read2Write($fp_messages, CHAT_MESSAGES);
	$total = count($messages);
	$empty = (!$total) ? 1 : 0;
	
	if ($total)
		foreach ($messages as $msg)
			if (isset($msg['act']))
				$total--;
	
	foreach ($messages as $msg_id => $msg)
		if ($total > $config['history'] - 1) {
			unset($messages[$msg_id]);
			
			$total--;
		}
		else
			break;
	
	$last_day = end($messages);
	$last_day = date('j', $last_day['time']);
	
	$newdate = '';
	if ($last_day != ($day = date('j', $time))) {
		list($month, $year) = explode(' ', date('n Y', $time));
		
		$newdate = '<div class="chat_info">&bull; '.$day.' <span id="month'.$month.'"></span>&nbsp;'.$year.' &bull;</div>';
	}
	
	$messages[$msg_id = md5($time.$fm->user['id'].$fm->input['msg'])] = array(
		'id'		=> $fm->user['id'],
		'name'		=> $fm->user['name'],
		'st'		=> $fm->user['status'],
		'text'		=> $fm->input['msg'],
		'time'		=> $time
	);
	
	$fm->_Write($fp_messages, $messages);
	
	$class = status_class($fm->user['status']);
	
	$GLOBALS['_RESULT'] = array(
		'error'		=> 0,
		'messages'	=> $newdate.'<div id="msg_'.$msg_id.'" class="chat_your">['.date('H:i:s', $time + $fm->user['timedif'] * 3600).'] <a href="#" onClick="return pasteN(\''.$fm->user['name'].'\');"'.$class.'>'.$fm->user['name'].'</a>: <span id="msg">'.$fm->input['msg'].'</span></div>',
		'last'		=> ($empty) ? $msg_id : '',
		'now'		=> 0
	);
}

function informer() {
global $fm;

$online = $fm->_Read(CHAT_ONLINE);

$now = 0;
$show_online = '';
foreach ($online as $id => $user)
if ($fm->_Nowtime - $user['time'] <= 30) {
$now++;

$show_online[] = '<a href="profile.php?action=show&member='.$id.'"'.status_class($user['st']).'>'.$user['name'].'</a>';
}

$GLOBALS['_RESULT'] = array(
'error' => 0,
'now' => $now,
'online' => implode(', ', $show_online)
);
}
?>