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

require('WatchesAdmin.php');

$fm->_LoadModuleLang('watches', true);

switch ($fm->_String('action')) {
	default:
		changeConfig();
	break;
}

function changeConfig() {
	global $fm;
	
	if (!$fm->_Boolean1('doSend')) {
		$watches = new Watches;
		$days = $watches->config['days'];
		unset($watches);
		
		include('admin/all_header.tpl');
		include('admin/nav_bar.tpl');
		include('admintemplates/config.tpl');
		include('admin/footer.tpl');
		
		return;
	}
	
	$fm->_Intval('days');
	
	$watchesAdmin = new WatchesAdmin;
	$watchesAdmin->saveConfig($fm->input['days']);
	unset($watchesAdmin);
	
	$fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=watches', 1);
}

?>