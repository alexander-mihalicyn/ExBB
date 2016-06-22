<?php

/*
	Reputation Mod for ExBB FM 1.0 RC1
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) die('Emo sucks;)');

$fm->_LoadModuleLang('reputation', 1);

define('CONFIG', 'modules/reputation/data/config.php');

if ($fm->_POST !== TRUE) {

	$config = $fm->_Read(CONFIG);
	
	$msg				= $config['msg'];
	$wait_days			= $config['wait_days'];
	$wait_hours			= $config['wait_hours'];
	$wait_minutes		= $config['wait_minutes'];
	$protect_days		= $config['protect_days'];
	$protect_hours		= $config['protect_hours'];
	$protect_minutes	= $config['protect_minutes'];
	$size_min			= $config['size_min'];
	$size_max			= $config['size_max'];
	$per_page			= $config['per_page'];
	
	$guest_yes			= ($config['guest']) ? 'checked="checked"' : '';
	$guest_no			= (!$config['guest']) ? 'checked="checked"' : '';
	
	$denied_yes			= ($config['denied']) ? 'checked="checked"' : '';
	$denied_no			= (!$config['denied']) ? 'checked="checked"' : '';
	
	$blacklist			= (($config['blacklist']) ? "\n\n" : '').implode("\n", $config['blacklist']);
	
	include('admin/all_header.tpl');
	include('admin/nav_bar.tpl');
	include('modules/reputation/admintemplates/index.tpl');
	include('admin/footer.tpl');
}
else {
	
	$fm->_Intvals(array('msg', 'wait_days', 'wait_hours', 'wait_minutes', 'protect_days',
		'protect_hours', 'protect_minutes', 'size_min', 'size_max', 'per_page'));
	$fm->_Boolean1('guest');
	$fm->_Boolean1('denied');
	$fm->_String('blacklist');
	
	if ($fm->input['wait_minutes'] >= 60) {
		$razn = intval($fm->input['wait_minutes'] / 60);
		$fm->input['wait_minutes'] -= $razn * 60;
		$fm->input['wait_hours'] += $razn;
	}
	if ($fm->input['wait_hours'] >= 24) {
		$razn = intval($fm->input['wait_hours'] / 24);
		$fm->input['wait_hours'] -= $razn * 24;
		$fm->input['wait_days'] += $razn;
	}
	if ($fm->input['protect_minutes'] >= 60) {
		$razn = intval($fm->input['protect_minutes'] / 60);
		$fm->input['protect_minutes'] -= $razn * 60;
		$fm->input['protect_hours'] += $razn;
	}
	if ($fm->input['protect_hours'] >= 24) {
		$razn = intval($fm->input['protect_hours'] / 24);
		$fm->input['protect_hours'] -= $razn * 24;
		$fm->input['protect_days'] += $razn;
	}
	
	$fm->input['blacklist'] = explode("\n", $fm->input['blacklist']);
	$blacklist = array();
	foreach ($fm->input['blacklist'] as $name) {
		if ($name) $blacklist[] = $name;
	}
	if ($blacklist) sort($blacklist);
	
	// Формируем новый массив конфига
	$config = array(
		'msg'				=> $fm->input['msg'],
		'wait_days'			=> $fm->input['wait_days'],
		'wait_hours'		=> $fm->input['wait_hours'],
		'wait_minutes'		=> $fm->input['wait_minutes'],
		'protect_days'		=> $fm->input['protect_days'],
		'protect_hours'		=> $fm->input['protect_hours'],
		'protect_minutes'	=> $fm->input['protect_minutes'],
		'size_min'			=> $fm->input['size_min'],
		'size_max'			=> $fm->input['size_max'],
		'per_page'			=> $fm->input['per_page'],
		'guest'				=> $fm->input['guest'],
		'denied'			=> $fm->input['denied'],
		'blacklist'			=> $blacklist
	);
	
	$fm->_Read2Write($fp_config, CONFIG);
	$fm->_Write($fp_config, $config);
	
	$fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=reputation', 1);
}

?>
