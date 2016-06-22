<?php

/*
	Advanced Visit Stats for ExBB FM 1.0 RC1
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) die('Emo sucks;)');
$fm->_LoadModuleLang('statvisit');

define('CONFIG', 'modules/statvisit/data/config.php');

if ($fm->_POST !== TRUE) {
	
	$config = $fm->_Read(CONFIG);
	
	$forum_yes		= ($config['forum']) ? ' checked="checked"' : '';
	$forum_no		= (!$config['forum']) ? ' checked="checked"' : '';
	
	$topic_yes		= ($config['topic']) ? ' checked="checked"' : '';
	$topic_no		= (!$config['topic']) ? ' checked="checked"' : '';
	
	$numbers_yes	= ($config['numbers']) ? ' checked="checked"' : '';
	$numbers_no		= (!$config['numbers']) ? ' checked="checked"' : '';
	
	$day_yes		= ($config['day']) ? ' checked="checked"' : '';
	$day_no			= (!$config['day']) ? ' checked="checked"' : '';
	
	include('admin/all_header.tpl');
	include('admin/nav_bar.tpl');
	include('modules/statvisit/admintemplates/index.tpl');
	include('admin/footer.tpl');
}
else {
	
	// Формируем новый массив конфига мода
	$config = array(
		'forum'		=> $fm->_Boolean1('forum'),
		'topic'		=> $fm->_Boolean1('topic'),
		'numbers'	=> $fm->_Boolean1('numbers'),
		'day'		=> $fm->_Boolean1('day')
	);
	
	$fm->_Read2Write($fp_config, CONFIG);
	$fm->_Write($fp_config, $config);
	
	$fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=statvisit', 1);
}

?>