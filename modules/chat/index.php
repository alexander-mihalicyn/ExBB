<?php
/*
	Chat for ExBB FM 1.0 RC2
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB')) die('Emo sucks;)');
require_once('modules/chat/common.php');

$fm->_LoadModuleLang('chat', 1);

if ($fm->_POST !== TRUE) {
	
	$config = $fm->_Read(EXBB_MODULE_CHAT_DATA_CONFIG);
	
	$height		= $config['height'];
	$update		= $config['update'];
	$scroll		= $config['scroll'];
	$history	= $config['history'];
	
	include('admin/all_header.tpl');
	include('admin/nav_bar.tpl');
	include('modules/chat/admintemplates/index.tpl');
	include('admin/footer.tpl');
}
else {
	
	$fm->_Intvals(array('height', 'update', 'scroll', 'history'));
	
	$config = array(
		'height'	=> $fm->input['height'],
		'update'	=> $fm->input['update'],
		'scroll'	=> $fm->input['scroll'],
		'history'	=> $fm->input['history']
	);
	
	$fm->_Read2Write($fp_config, EXBB_MODULE_CHAT_DATA_CONFIG);
	$fm->_Write($fp_config, $config);
	
	$fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=chat', 1);
}

?>