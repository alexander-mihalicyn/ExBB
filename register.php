<?php
/***************************************************************************
 * ExBB v.1.1                                                               *
 * Copyright (c) 2002-20хх by Alexander Subhankulov aka Warlock             *
 *                                                                          *
 * http://www.exbb.net                                                      *
 * email: admin@exbb.net                                                    *
 *                                                                          *
 ***************************************************************************/
/***************************************************************************
 *                                                                          *
 *   This program is free software; you can redistribute it and/or modify   *
 *   it under the terms of the GNU General Public License as published by   *
 *   the Free Software Foundation; either version 2 of the License, or      *
 *   (at your option) any later version.                                    *
 *                                                                          *
 ***************************************************************************/
define('IN_EXBB', true);
define('IS_REGISTER', true);

include( './include/common.php' );

$fm->_GetVars();
$fm->_String('action', 'agreement');
$fm->_LoadLang('register');

if ($fm->user['id'] !== 0) {
	header("Location: index.php");
}

if ($fm->exbb['reg_on'] === true) {
	$fm->_Message($fm->LANG['Registration'], $fm->LANG['RegistrDenied']);
}
$sesid = _SESSION_ID;
if ($fm->input['action'] == 'addmember') {
	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_Strings(array( 'captcha' => '', 'emailaddress' => '', 'inmembername' => '', 'password' => '', 'homepage' => '', 'icqnumber' => '', 'aolname' => '', 'location' => '', 'interests' => '', 'signature' => '', 'useravatar' => 'noavatar.gif', 'timedifference' => '' ));

	if ($fm->exbb['anti_bot'] && ( $fm->input['captcha'] == '' || !isset( $_SESSION['captcha'] ) || $fm->input['captcha'] !== $_SESSION['captcha'] )) {
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['CaptchaError']);
	}

	$requirepass = ( $fm->exbb['passwordverification'] === false && $fm->exbb['emailfunctions'] === true ) ? true : false;

	/* Email validation */
	if ($fm->exbb['emailfunctions'] === true) {
		if ($fm->input['emailaddress'] === '') {
			$fm->_Message($fm->LANG['Registration'], $fm->LANG['EmailEmpty']);
		}
		if ($fm->_Chek_Mail('emailaddress') === false) {
			$fm->_Message($fm->LANG['Registration'], $fm->LANG['WrongEmail']);
		}

	}
	/* Name validation */
	if ($fm->input['inmembername'] === '' || mb_strlen($fm->input['inmembername']) > 20) {
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['NameEmpty']);
	}

	$fm->input['inmembername'] = preg_replace("/\s{1,}/", " ", $fm->input['inmembername']);
	$wrongchars = ( $fm->exbb['ru_nicks'] === true ) ? $fm->LANG['WrongCharsRuYes'] : $fm->LANG['WrongCharsRuNo'];

	if ($fm->exbb['ru_nicks'] === false && preg_match("#[а-яёґєіїўі|А-ЯЁҐЄІЇЎІ]{1,}#isu", $fm->input['inmembername'])) {
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['RuNicksOff']);
	}
	if (preg_match("#[а-яёґєіїўі|А-ЯЁҐЄІЇЎІ]{1,}#is", $fm->input['inmembername']) && preg_match("#[a-z|A-Z]{1,}#isu", $fm->input['inmembername'])) {
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['IntNameRuOrEn']);
	}
	if (preg_match("#(guest|admin|moder|админ|" . $fm->LANG['Guest'] . "|модер|[^0-9A-Za-zА-Яа-я-_\.\s])#isu", mb_strtolower($fm->input['inmembername']))) {
		$fm->_Message($fm->LANG['Registration'], $wrongchars);
	}
	if ($fm->exbb['wordcensor'] === true && $fm->bads_filter($fm->input['inmembername'], 0) === true) {
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['NoProfanity']);
	}

	/* Pass validation */
	$fm->input['password'] = ( $requirepass === false ) ? $fm->input['password'] : Generate_pass();
	if ($fm->input['password'] === '') {
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['PassEmpty']);
	}

	check_banned();
	validate_items();

	$allusers = $fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST);
	if (count($allusers) <= 0) {
		$fm->_Fclose($fp_allusers);
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['RegistrDenied']);
	}
	$username = mb_strtolower($fm->input['inmembername']);
	foreach ($allusers as $u_id => $info) {
		if ($info['n'] == $username) {
			$fm->_Fclose($fp_allusers);
			$fm->_Message($fm->LANG['Registration'], $fm->LANG['NameExist']);
		}

		if ($fm->exbb['emailfunctions'] === true) {
			if ($info['m'] == $fm->input['emailaddress']) {
				$fm->_Fclose($fp_allusers);
				$fm->_Message($fm->LANG['Registration'], $fm->LANG['EmailExist']);
			}
		}
	}

	$user = array();
	$user['id'] = 0;
	$user['status'] = 'me';
	$user['name'] = $fm->input['inmembername'];
	$user['pass'] = $fm->input['password'];
	$user['mail'] = ( $fm->exbb['emailfunctions'] === true ) ? $fm->input['emailaddress'] : '';
	$user['title'] = '';
	$user['posts'] = 0;
	$user['joined'] = time();
	$user['ip'] = $fm->_IP;
	$user['showemail'] = $fm->_Boolean($fm->input, 'showemail');
	$user['www'] = $fm->input['homepage'];
	$user['icq'] = $fm->input['icqnumber'];
	$user['aim'] = $fm->input['aolname'];
	$user['location'] = $fm->input['location'];
	$user['interests'] = $fm->input['interests'];
	$user['sig'] = $fm->input['signature'];
	$user['sig_on'] = $fm->_Boolean($fm->input, 'sig_on');
	$user['lang'] = Check_DefLangSkin('language', 'default_lang', $fm->_String('default_lang'));
	$user['skin'] = Check_DefLangSkin('templates', 'default_style', $fm->_String('default_style'));
	$user['timedif'] = $fm->input['timedifference'];
	$user['avatar'] = $fm->input['useravatar'];
	$user['upload'] = ( $fm->exbb['autoup'] === true ) ? true : false;
	$user['visible'] = ( $fm->exbb['visiblemode'] === true && $fm->_Boolean($fm->input, 'visiblemode') === true ) ? true : false;
	$user['new_pm'] = false;
	$user['sendnewpm'] = ( $fm->exbb['pmnewmes'] === true && $fm->_Boolean($fm->input, 'pm_newmes') === true ) ? true : false;
	$user['posts2page'] = $fm->exbb['posts_per_page'];
	$user['topics2page'] = $fm->exbb['topics_per_page'];
	$user['last_visit'] = 0;

	if ($requirepass === true) {
		$tempusers = $fm->_Read2Write($fp_temp, EXBB_DATA_TEMP_USERS_LIST);
		if (count($tempusers) > 0) {
			foreach ($tempusers as $hash => $tinfo) {
				if ($tinfo['n'] == $username) {
					$fm->_FcloseAll();
					$fm->_Message($fm->LANG['Registration'], $fm->LANG['NameExist']);
				}
				if ($fm->exbb['emailfunctions'] === true && $tinfo['m'] == $fm->input['emailaddress']) {
					$fm->_FcloseAll();
					$fm->_Message($fm->LANG['Registration'], $fm->LANG['EmailExist']);
				}
			}
		}

		$filehash = md5($username . mt_rand(10000, 99999) . $fm->input['emailaddress']);
		$code = mt_rand(10000, 99999);

		$tempusers[$filehash]['n'] = $username;
		$tempusers[$filehash]['m'] = $fm->input['emailaddress'];
		$tempusers[$filehash]['c'] = $code;
		$tempusers[$filehash]['t'] = $fm->_Nowtime;

		$fm->_Write($fp_temp, $tempusers);
		$fm->_Read2Write($fp_tempuser, EXBB_DATA_DIR_MEMBERS . '/__' . $filehash . '.php');
		$fm->_Write($fp_tempuser, $user);
		unset( $_SESSION['captcha'] );

		$subject = sprintf($fm->LANG['RegOnBoard'], $fm->exbb['boardname']);
		$email = sprintf($fm->LANG['RegRequest'], $fm->exbb['boardname'], $fm->exbb['boardurl'], $fm->input['inmembername'], $fm->input['password'], $code, $filehash);
		$fm->_FcloseAll();
		$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $fm->input['emailaddress'], $subject, $email);
		$fm->LANG['RegThanks'] = sprintf($fm->LANG['RegThanks'], $fm->exbb['boardname']);
		$fm->_Refresh = 10;
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['RegThanks'] . $fm->LANG['PassSended'], 'register.php?action=activate');
	}
	else {
		ksort($allusers, SORT_NUMERIC);
		end($allusers);
		$id = key($allusers) + 1;
		$fm->_BOARDSTATS();
		$id = ( $fm->_Stats['last_id'] === $id ) ? $id + 1 : $id;
		$allusers[$id]['n'] = $username;
		$allusers[$id]['m'] = $fm->input['emailaddress'];
		$allusers[$id]['p'] = 0;
		$fm->_Write($fp_allusers, $allusers);
		unset( $allusers );
		$user['id'] = $id;
		$user['pass'] = md5($user['pass']);
		$user['last_visit'] = $fm->_Nowtime;

		/* День Рождения */
		include( 'modules/birstday/register_save.php' );
		/* День Рождения */

		/* Приветсвие нового пользователя */
		if ($fm->exbb['pm'] === true && $fm->exbb['newusergreatings'] === true) {
			$user['new_pm'] = true;
			$fm->LANG['NewUserPMMsg'] = sprintf($fm->LANG['NewUserPMMsg'], $fm->input['inmembername'], $fm->exbb['boardurl'], $fm->exbb['boardurl']);

			$inbox = $fm->_Read2Write($fp_inbox,EXBB_DATA_DIR_MESSAGES . '/' . $id . '-msg.php');

			$inbox[$fm->_Nowtime]['from'] = $fm->LANG['NewUserPMFrom'];
			$inbox[$fm->_Nowtime]['title'] = $fm->LANG['NewUserPMTitle'];
			$inbox[$fm->_Nowtime]['msg'] = $fm->LANG['NewUserPMMsg'];
			$inbox[$fm->_Nowtime]['frid'] = 1;
			$inbox[$fm->_Nowtime]['mail'] = false;
			$inbox[$fm->_Nowtime]['status'] = false;
			$fm->_Write($fp_inbox, $inbox);
			unset( $inbox );
		}
		/* Приветсвие нового пользователя */

		$fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $id . '.php');
		$fm->_Write($fp_user, $user);

		$_SESSION['mid'] = $id;
		$_SESSION['sts'] = 'me';
		$_SESSION['lastposttime'] = $fm->_Nowtime - 180;
		$_SESSION['iden'] = md5($user['name'] . $user['pass'] . _SESSION_ID);
		unset( $_SESSION['captcha'] );
		$fm->_setcookie('exbbn', $id);
		$fm->_setcookie('exbbp', md5($user['pass']));
		$fm->_setcookie('lastvisit', $fm->_Nowtime);

		$fm->_SAVE_STATS(array( 'totalmembers' => array( 1, 1 ), 'lastreg' => array( $user['name'], 0 ), 'last_id' => array( $id, 0 ) ));

		$fm->LANG['RegThanks'] = sprintf($fm->LANG['RegThanks'], $fm->exbb['boardname']);
		if ($fm->exbb['emailfunctions'] === true) {
			$email = sprintf($fm->LANG['EmailThanksRegistration'], $fm->exbb['boardname'], $fm->exbb['boardurl'] . '/index.php', $fm->input['inmembername'], $fm->input['password']);
			$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $fm->input['emailaddress'], $fm->LANG['RegThanks'], $email);

			if ($fm->exbb['newusernotify'] === true) {
				$subject = $fm->LANG['NewReged'];
				$email = sprintf($fm->LANG['EmailNewUserRegistered'], $fm->exbb['boardname'], $fm->exbb['boardurl'] . '/index.php', $fm->input['inmembername'], $fm->input['password'], $fm->input['emailaddress'], $fm->input['homepage'], $fm->_IP);
				$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $fm->exbb['adminemail'], $subject, $email);
			}
		}
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['RegThanks'] . $fm->LANG['ToChangePass'], get_rd());
	}
}
elseif ($fm->input['action'] == 'activate') {
	$requirepass = ( $fm->exbb['passwordverification'] === false && $fm->exbb['emailfunctions'] === true ) ? true : false;
	if ($requirepass === false) {
		header("Location: index.php");
	}

	if ($fm->_String('code') !== '' && $fm->_Intval('regid') !== 0) {
		$tempusers = $fm->_Read2Write($fp_temp, EXBB_DATA_TEMP_USERS_LIST);
		foreach ($tempusers as $hash => $tempinfo) {
			if (( $tempinfo['t'] + 86400 ) < $fm->_Nowtime) {
				if (file_exists(EXBB_DATA_DIR_MEMBERS . '/__' . $hash . '.php')) {
					unlink(EXBB_DATA_DIR_MEMBERS . '/__' . $hash . '.php');
				}
				unset( $tempusers[$hash] );
			}
		}

		if (!isset( $tempusers[$fm->input['code']] )) {
			$fm->_Write($fp_temp, $tempusers);
			$fm->_Message($fm->LANG['ActivateAccount'], $fm->LANG['WrongActivation'], 'register.php?action=activate');
		}

		if ($tempusers[$fm->input['code']]['c'] !== $fm->input['regid']) {
			$fm->_Write($fp_temp, $tempusers);
			$fm->_Message($fm->LANG['ActivateAccount'], $fm->LANG['WrongActivation'], 'register.php?action=activate');
		}

		$tempfile = EXBB_DATA_DIR_MEMBERS . '/__' . $fm->input['code'] . '.php';
		if (!file_exists($tempfile)) {
			$fm->_Write($fp_temp, $tempusers);
			$fm->_Message($fm->LANG['ActivateAccount'], $fm->LANG['WrongActivation'], 'register.php?action=activate');
		}
		$user = $fm->_Read($tempfile);
		if (count($user) <= 0) {
			$fm->_Write($fp_temp, $tempusers);
			unlink($tempfile);
			$fm->_Message($fm->LANG['ActivateAccount'], $fm->LANG['WrongActivation'], 'register.php?action=activate');
		}
		unset( $tempusers[$fm->input['code']] );
		unlink($tempfile);
		$fm->_Write($fp_temp, $tempusers);

		$allusers = $fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST);
		ksort($allusers, SORT_NUMERIC);
		end($allusers);
		$id = key($allusers) + 1;
		$fm->_BOARDSTATS();
		$id = ( $fm->_Stats['last_id'] === $id ) ? $id + 1 : $id;
		$allusers[$id]['n'] = mb_strtolower($user['name']);
		$allusers[$id]['m'] = $user['mail'];
		$allusers[$id]['p'] = 0;
		$fm->_Write($fp_allusers, $allusers);
		unset( $allusers );

		$password = $user['pass'];
		$user['id'] = $id;
		$user['pass'] = md5($user['pass']);
		$user['last_visit'] = $fm->_Nowtime;

		/* Приветсвие нового пользователя */
		if ($fm->exbb['newusergreatings'] === true) {
			$user['new_pm'] = true;
			$fm->LANG['NewUserPMMsg'] = sprintf($fm->LANG['NewUserPMMsg'], $user['name'], $fm->exbb['boardurl'], $fm->exbb['boardurl']);

			$inbox = $fm->_Read2Write($fp_inbox, EXBB_DATA_DIR_MESSAGES . '/' . $id . '-msg.php');

			$inbox[$fm->_Nowtime]['from'] = $fm->LANG['NewUserPMFrom'];
			$inbox[$fm->_Nowtime]['title'] = $fm->LANG['NewUserPMTitle'];
			$inbox[$fm->_Nowtime]['msg'] = $fm->LANG['NewUserPMMsg'];
			$inbox[$fm->_Nowtime]['frid'] = 1;
			$inbox[$fm->_Nowtime]['mail'] = false;
			$inbox[$fm->_Nowtime]['status'] = false;
			$fm->_Write($fp_inbox, $inbox);
			unset( $inbox );
		}
		/* Приветсвие нового пользователя */

		$fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $id . '.php');
		$fm->_Write($fp_user, $user);

		$fm->_SAVE_STATS(array( 'totalmembers' => array( 1, 1 ), 'lastreg' => array( $user['name'], 0 ), 'last_id' => array( $id, 0 ) ));

		$fm->LANG['RegThanks'] = sprintf($fm->LANG['RegThanks'], $fm->exbb['boardname']);
		if ($fm->exbb['emailfunctions'] === true && $fm->exbb['newusernotify'] === true) {
			$subject = $fm->LANG['NewReged'];
			$email = sprintf($fm->LANG['EmailNewUserRegistered'], $fm->exbb['boardname'], $fm->exbb['boardurl'] . '/index.php', $user['name'], $password, $user['mail'], $user['www'], $fm->_IP);
			$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $fm->exbb['adminemail'], $subject, $email);
		}
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['RegThanks'] . $fm->LANG['ActivatedOk'], 'loginout.php');
	}
	$PageTitle = $fm->LANG['ActivateAccount'];
	$ActIdTitle = $fm->LANG['RegId'];
	$IdFiledName = 'regid';
	$PassActivated = false;
}
elseif ($fm->input['action'] == 'agreed') {
	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$requirepass = ( $fm->exbb['passwordverification'] === false && $fm->exbb['emailfunctions'] === true ) ? true : false;
	$intern = ( $fm->exbb['ru_nicks'] ) ? '<br />' . $fm->LANG['RuYes'] : '<br />' . $fm->LANG['RuNo'];
	$avatarhtml = $anti_bot = '';

	if ($fm->exbb['reg_simple'] === false) {  //simple reg form
		$langs_select = $style_select = $avatars_select = '';

		if ($fm->exbb['avatars'] === true) {

			$avatarsdir = 'im/avatars';
			$d = dir($avatarsdir);
			while (false !== ( $file = $d->read() )) {
				if (is_dir($avatarsdir . '/' . $file) || !getimagesize($avatarsdir . '/' . $file)) {
					continue;
				}

				if ($file == 'noavatar.gif') {
					$avatars_select .= '<option value="' . $file . '" selected>' . $file . "</option>\n";
					$currentface = $file;
				}
				else {
					$avatars_select .= '<option value="' . $file . '">' . $file . "</option>\n";
				}
			}
			$d->close();
		}

		$languagedir = 'language';
		$d = dir($languagedir);
		while (false !== ( $file = $d->read() )) {
			if (is_dir($languagedir . '/' . $file) && $file != '.' && $file != '..') {
				$selected = ( $file == DEF_LANG ) ? ' selected="selected"' : '';
				$langs_select .= '<option value="' . trim($file) . '"' . $selected . '>' . ucfirst($file) . '</option>';
			}
		}
		$d->close();

		$styledir = 'templates';
		$d = dir($styledir);
		while (false !== ( $file = $d->read() )) {
			if (is_dir($styledir . '/' . $file) && $file != '.' && $file != '..') {
				$selected = ( $file == mb_strtolower(DEF_SKIN) ) ? ' selected="selected"' : '';
				$style_select .= '<option value="' . trim($file) . '"' . $selected . '>' . $file . '</option>';
			}
		}
		$d->close();

		include( 'language/' . DEF_LANG . '/lang_tz.php' );
		$timedifference = '0';
		$timezones = '';
		foreach ($tz as $shift => $zona) {
			if ($shift == $timedifference) {
				$timezones .= '<option value="' . $shift . '" selected>' . $zona . '</option>';
			}
			else {
				$timezones .= '<option value="' . $shift . '">' . $zona . '</option>';
			}
		}
		$basetimes = $fm->_DateFormat(time());

		/* День рождения */
		include( 'modules/birstday/select.php' );
		/* День рождения */
	} //simple reg form   end agree//
}
else {
	$fm->input['action'] = 'agreement';
}
$fm->_Title = ' :: ' . $fm->LANG['Registration'];
include( './templates/' . DEF_SKIN . '/all_header.tpl' );
include( './templates/' . DEF_SKIN . '/logos.tpl' );
include( './templates/' . DEF_SKIN . '/' . $fm->input['action'] . '.tpl' );
include( './templates/' . DEF_SKIN . '/footer.tpl' );
include( 'page_tail.php' );

/*
	Functions
*/

function check_banned() {
	global $fm;

	$bannedmembers = $fm->_Read(EXBB_DATA_BANNED_USERS_LIST);
	$bannedmember = false;
	foreach ($bannedmembers as $name => $infa) {
		if ($fm->exbb['emailfunctions'] && @$fm->input['emailaddress'] == @$infa['em'] || $fm->input['inmembername'] == $name /*|| $fm->_IP == $infa['ip']*/) {
			$bannedmember = true;
			break;
		}
	}
	unset( $bannedmembers );
	if ($bannedmember === true) {
		$fm->_Message($fm->LANG['Registration'], $fm->LANG['RegistrDenied']);
	}
}

function validate_items() {
	global $fm;

	if ($fm->exbb['reg_simple'] === false) {
		$fm->_Chek_WWW('homepage');
		$fm->input['icqnumber'] = ( preg_match("/^[0-9]{5,9}$/", $fm->input['icqnumber']) ) ? $fm->input['icqnumber'] : '';
		$fm->input['aolname'] = ( ( $l = mb_strlen($fm->input['aolname']) ) >= 3 && $l <= 32 ) ? $fm->input['aolname'] : '';
		$fm->input['location'] = ( ( $l = mb_strlen($fm->input['location']) ) >= 3 && $l <= 100 ) ? $fm->input['location'] : '';
		$fm->input['interests'] = ( ( $l = mb_strlen($fm->input['interests']) ) >= 3 && $l <= 100 ) ? $fm->input['interests'] : '';
		$fm->input['signature'] = ( mb_strlen($fm->input['signature']) >= 3 ) ? $fm->input['signature'] : '';

		include( 'language/' . DEF_LANG . '/lang_tz.php' );
		$fm->input['timedifference'] = ( isset( $tz[$fm->input['timedifference']] ) ) ? $fm->input['timedifference'] : 0;

		if ($fm->exbb['wordcensor'] === true && $fm->bads_filter($fm->input['signature'], 0) === true) {
			$fm->_Message($fm->LANG['Registration'], $fm->LANG['NoProfanity']);
		}

		$siglines = explode("\n", $fm->input['signature']);
		if (count($siglines) > $fm->exbb['max_sig_lin'] || mb_strlen($fm->input['signature']) > $fm->exbb['max_sig_chars']) {
			$fm->_Message($fm->LANG['Registration'], sprintf($fm->LANG['SigOptions'], $fm->exbb['max_sig_lin'], $fm->exbb['max_sig_chars']));
		}

		if (!preg_match("#^[A-Za-z0-9-_]{1,64}\.[A-Za-z]{3,4}$#is", $fm->input['useravatar']) || !file_exists('im/avatars/' . $fm->input['useravatar'])) {
			$fm->input['useravatar'] = 'noavatar.gif';
		}
	}
	else {
		$fm->input['homepage'] = $fm->input['icqnumber'] = $fm->input['aolname'] = '';
		$fm->input['location'] = $fm->input['interests'] = $fm->input['signature'] = '';
		$fm->input['timedifference'] = 0;
		$fm->input['useravatar'] = 'noavatar.gif';
	}

	return;
}

?>
