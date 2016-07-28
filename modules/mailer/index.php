<?php

/*
	Mailer Mod for ExBB FM 1.0 RC1.01
	Copyright (c) 2005 - 2012 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

defined('IN_EXBB') or die;

require('MailerAdmin.class.php');

$fm->_LoadModuleLang('mailer', true);

if ($fm->_POST !== true || !$fm->_Boolean1('doSend')) {
	$mailerAdmin = new MailerAdmin;
	$config = $mailerAdmin->getConfig();
	
	$days		= intval($config['period'] / 86400);
	$hours		= intval(($config['period'] - $days * 86400) / 3600);
	$minutes	= intval(($config['period'] - $days * 86400 - $hours * 3600) / 60);
	$seconds	= $config['period'] - $days * 86400 - $hours * 3600 - $minutes * 60;
	$messages	= $config['messages'];
	$process	= $config['process'];
	$reserved	= $config['reserved'];
	$cron_yes	= $config['cron'] ? 'checked="checked" ' : '';
	$cron_no	= !$config['cron'] ? 'checked="checked" ' : '';
	$last		= !empty($config['last']) ? $fm->_DateFormat($config['last']) : $fm->LANG['MailerNever'];
	$through	= !empty($config['through']) ? $config['through'] : 0;
	$sent		= !empty($config['sent']) ? $config['sent'] + $through : 0;
	list($wSent, $wThrough) = $mailerAdmin->getWSentThrough();
	
	$mailerAdmin->closeConfig();
	unset($mailer);
	
	require('admin/all_header.tpl');
	require('admin/nav_bar.tpl');
	require('modules/mailer/admintemplates/index.tpl');
	require('admin/footer.tpl');
	
	die;
}

$fm->_Intvals(array('days', 'hours', 'minutes', 'seconds', 'messages', 'process', 'reserved'));
$fm->_Boolean1('cron');

$mailerAdmin = new MailerAdmin;
$config = $mailerAdmin->getConfig();

$config['period']		= abs($fm->input['days']) * 86400 + abs($fm->input['hours']) * 3600 + abs($fm->input['minutes']) * 60 + abs($fm->input['seconds']);
$config['messages']		= abs($fm->input['messages']);
$config['process']		= abs($fm->input['process']);
$config['reserved']		= abs($fm->input['reserved']);
$config['cron']			= $fm->input['cron'];

$mailerAdmin->saveConfig($config);

$fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=mailer', true);