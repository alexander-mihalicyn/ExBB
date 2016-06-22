<?php

/*
	Ajax Verification Forms Mod for ExBB FM 1.0 RC2
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

if (!defined('IN_EXBB'))
	die('Emo sucks;)');

$verify_func = 'verify_'.$fm->_String('form').'_'.$fm->_String('name');

if (!function_exists($verify_func))
	die;

$_RESULT['error']	= 0;

$verify_func();

/*	Verification functions	*/

function verify_register_inmembername() {
	global $fm;
	
	$fm->_LoadLang('register');
	
	$fm->_String('value');
	$fm->input['value'] = preg_replace('/\s{1,}/', ' ', $fm->_LowerCase($fm->input['value']));
	
	if ($fm->input['value'] === '')
		verify_result(0, $fm->LANG['VerifyNameEmpty']);
	
	if ($fm->exbb['ru_nicks'] === FALSE && preg_match('#[à-ÿÀ-ß¸¨]{1,}#is', $fm->input['value']))
		verify_result(0, $fm->LANG['VerifyRuNicks']);
	
	if (preg_match('#[à-ÿÀ-ß¸¨]{1,}#is', $fm->input['value']) && preg_match('#[a-zA-Z]{1,}#is', $fm->input['value']))
		verify_result(0, $fm->LANG['VerifyRuOrEn']);
	
	if (preg_match('#(guest|admin|moder|'.$fm->LANG['Guest'].'|àäìèí|ìîäåð|[^0-9a-zA-Zà-ÿÀ-ß¸¨\-_\.\s])#is', $fm->input['value']))
		verify_result(0, $fm->LANG['VerifyNameProtect']);
	
	if ($fm->exbb['wordcensor'] === TRUE && $fm->bads_filter($fm->input['value'], 0) === TRUE)
		verify_result(0, $fm->LANG['VerifyProfanity']);
	
	$users = $fm->_Read(FM_USERS);
	
	foreach ($users as $id => $info)
		if ($fm->input['value'] === $info['n'])
			verify_result(0, $fm->LANG['VerifyNameExists']);
	
	verify_result(1);
}

function verify_register_password() {
	global $fm;
	
	$fm->_LoadLang('register');
	
	$fm->_String('value');
	
	if ($fm->input['value'] === '')
		verify_result(0, $fm->LANG['VerifyPasswordEmpty']);
	
	if (strlen($fm->input['value']) < 6)
		verify_result(0, $fm->LANG['VerifyPasswordShort']);
	
	verify_result(1);
}

function verify_register_emailaddress() {
	global $fm;
	
	$fm->_LoadLang('register');
	
	$fm->_String('value');
	
	if ($fm->input['value'] === '')
		verify_result(0, $fm->LANG['VerifyEmailEmpty']);
	
	if ($fm->_Chek_Mail('value') === FALSE)
		verify_result(0, $fm->LANG['VerifyEmailCorrect']);
	
	$users = $fm->_Read(FM_USERS);
	
	foreach ($users as $id => $info)
		if ($fm->input['value'] === $info['m'])
			verify_result(0, $fm->LANG['VerifyEmailExists']);
	
	verify_result(1);
}

function verify_register_captcha() {
	global $fm;
	
	$fm->_LoadLang('register');
	
	$fm->_String('value');
	
	if ($fm->input['value'] === '')
		verify_result(0, $fm->LANG['VerifyCaptchaEmpty']);
	
	if (!isset($_SESSION['captcha']) || $fm->input['value'] !== $_SESSION['captcha'])
		verify_result(0, $fm->LANG['VerifyCaptchaWrong']);
	
	verify_result(1);
}

/*	Result functions	*/

function verify_result($result, $text = '', $alert = '') {
	global $fm, $_RESULT;
	
	$_RESULT['result']	= $result;
	$_RESULT['name']	= $fm->input['name'];
	$_RESULT['text']	= $text;
	$_RESULT['alert']	= $alert;
	
	die;
}

?>