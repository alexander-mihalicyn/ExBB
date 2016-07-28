<?php
/****************************************************************************
 * ExBB v.1.9                                                                *
 * Copyright (c) 2002-20хх by Alexander Subhankulov aka Warlock                *
 *                                                                            *
 * http://www.exbb.net                                                        *
 * email: admin@exbb.net                                                    *
 *                                                                            *
 ****************************************************************************/
/****************************************************************************
 *                                                                            *
 *   This program is free software; you can redistribute it and/or modify    *
 *   it under the terms of the GNU General Public License as published by    *
 *   the Free Software Foundation; either version 2 of the License, or        *
 *   (at your option) any later version.                                    *
 *                                                                            *
 ****************************************************************************/
define('IN_ADMIN', true);
define('IN_EXBB', true);

include( './include/common.php' );
$fm->_GetVars();
$fm->_String('action');
$fm->_LoadLang('setmembers', true);

if ($fm->input['action'] == 'updatecount') {
	$total = UpdateAllusersInfo();
	$fm->_WriteLog($fm->LANG['LogRecountUsers'], 1);
	$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['UserCountUpd'] . $total, 'setmembers.php', 1);
}
elseif ($fm->input['action'] == 'edit_user') {

	if ($fm->_Intval('userid') === 0) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost'], '', 1);
	}

	if (( $user_id = $fm->_Intval('userid') ) === 0 || !file_exists(EXBB_DATA_DIR_MEMBERS . '/' . $user_id . '.php')) {
		$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['UserNotFound'], '', 1);
	}

	$user = $fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $user_id . '.php', false);
	$forums = $fm->_Read(EXBB_DATA_FORUMS_LIST, false);

	if ($fm->_String('checkaction') === 'yes' && $fm->_POST === true) {

		if ($fm->_String('deleteuser') === 'yes') {
			$fm->_FcloseAll();
			unset( $user, $forums );
			deletemember();
		}


		$trans_table = get_html_translation_table(HTML_SPECIALCHARS);
		$newname = ( $user['name'] == $fm->input['newname'] || $fm->input['newname'] == '' ) ? false : true;
		$newpassword = ( $fm->input['password'] == '' || $user['pass'] === md5($fm->input['password']) ) ? false : true;
		$newemail = ( $fm->input['emailaddress'] == '' || strtolower($fm->input['emailaddress']) == $user['mail'] ) ? false : true;

		if ($newemail === true && $fm->_Chek_Mail('emailaddress') === false) {
			$fm->_FcloseAll();
			$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['WrongEmail'], '', 1);
		}

		$user['pass'] = ( $newpassword === true ) ? md5($fm->input['password']) : $user['pass'];
		$user['name'] = ( $newname === true ) ? $fm->input['newname'] : $user['name'];
		$user['mail'] = $fm->input['emailaddress'];
		$user['title'] = strtr($fm->input['membertitle'], array_flip($trans_table));
		$user['www'] = $fm->_Chek_WWW('homepage');
		$user['aim'] = $fm->input['aolname'];
		$user['icq'] = $fm->input['icqnumber'];
		$user['location'] = $fm->input['location'];
		$user['interests'] = $fm->input['interests'];
		$user['sig'] = strtr($fm->input['signature'], array_flip($trans_table));
		$user['posts'] = intval($fm->input['numberofposts']);
		$user['avatar'] = ( $fm->input['avatar'] != '' && preg_match("#^(personal/\d{1,5}-avatar|[A-Za-z0-9\.-_]{1,50})\.(jpg|jpeg|gif|png|bmp|pjpeg)$#is", $fm->input['avatar']) && file_exists('im/avatars/' . $fm->input['avatar']) ) ? $fm->input['avatar'] : "noavatar.gif";
		$user['upload'] = ( $fm->_Intval('doupload') === 1 ) ? true : false;

		$user['private'] = array();
		$fm->_Array('privforum');
		foreach ($fm->input['privforum'] as $forum_id => $chek) {
			if ($chek == 'yes' && isset( $forums[$forum_id] ) && $forums[$forum_id]['private'] === true) {
				$user['private'][$forum_id] = true;
			}

		}

		BunUnban($fm->input['memberstatus']);
		$fm->_Write($fp_user, $user);

		if ($newname === true || $newemail === true) {
			$allusers = $fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST, false);
			$allusers[$user_id]['n'] = $fm->_LowerCase($user['name']);
			$allusers[$user_id]['m'] = $user['mail'];
			$fm->_Write($fp_allusers, $allusers);
			unset( $allusers );
		}

		if ($newname === true || $newemail === true || $newpassword === true) {
			$username = strtr($user['name'], array_flip(get_html_translation_table(HTML_SPECIALCHARS, ENT_QUOTES)));
			$newpassword = ( $newpassword === true ) ? $fm->input['password'] : $fm->LANG['PassNotChanged'];
			$emailtext = sprintf($fm->LANG['EmailNewPassName'], date("d-m-Y H:i:s", time()), $username, $newpassword, $fm->exbb['boardurl']);
			$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $user['mail'], $fm->LANG['AdminPassNotify'], $emailtext);
		}
		$fm->_WriteLog($fm->LANG['LogEditUser'], 1);
		$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['UserUpdatedOk'], 'setmembers.php?action=edit_user&amp;userid=' . $user_id, 1);
	}
	else {
		$fm->_Fclose($fp_user);

		$private = '';
		foreach ($forums as $id => $infa) {
			if ($infa['private']) {
				if (stristr($infa['catid'], 'f')) {
					continue;
				}
				$checked = ( isset( $user['private'][$id] ) && $user['private'][$id] === true ) ? ' checked' : '';
				$private .= '<input type="checkbox" name="privforum[' . $id . ']" value="yes"' . $checked . '>' . $infa['name'] . '<br>';
			}
			foreach ($forums as $p_id => $p_infa) {
				if ($p_infa['private'] && $p_infa['catid'] == 'f' . $id) {
					$checked = ( isset( $user['private'][$p_id] ) && $user['private'][$p_id] === true ) ? ' checked' : '';
					$private .= '<input type="checkbox" name="privforum[' . $p_id . ']" value="yes"' . $checked . '>' . $infa['name'] . ' :: ' . $p_infa['name'] . '<br>';
				}
			}
		}

		if (empty( $private )) {
			$private = $fm->LANG['NoPrivateForums'];
		}
		/* День рождения */
		$includemode = 'edit';
		include( 'modules/birstday/setmembers.php' );
		/* День рождения */

		$user['title'] = $fm->Clean_Value($user['title']);
		$user['sig'] = $fm->Clean_Value(str_replace('<br>', "\n", $user['sig']));
		$user['www'] = ( $user['www'] == 'http://' || $user['www'] == '' ) ? '' : str_replace('http://', '', $user['www']);
		$homepage = ( $user['www'] != '' ) ? '<a href="http://' . $user['www'] . '" target="_blank">' . $fm->LANG['VisitUserWWW'] . '</a>' : '';

		$checked = ( $user['upload'] ) ? 'checked' : '';
		$lastvisitdate = ( $user['last_visit'] != 0 ) ? $fm->_DateFormat($user['last_visit'] + $fm->user['timedif'] * 3600) : $fm->LANG['NeverLogged'];
		$regdate = $fm->_DateFormat($user['joined']);
		$selectstatus = " <option value=\"banned\">" . $fm->LANG['BannedUser'] . "</option>
									<option value=\"me\">" . $fm->LANG['User'] . "</option>
									<option value=\"sm\">" . $fm->LANG['SuperModer'] . "</option>
									<option value=\"ad\">" . $fm->LANG['Admin'] . "</option>";
		$selectstatus = str_replace("value=\"{$user['status']}\"", "value=\"{$user['status']}\" selected", $selectstatus);
		include( './admin/all_header.tpl' );
		include( './admin/nav_bar.tpl' );
		include( './admin/edit_user.tpl' );
		include( './admin/footer.tpl' );
	}
}
elseif ($fm->input['action'] == 'log') {
	if ($fm->_String('DelLog') != '' && $fm->_POST === true && ( $log_name = $fm->_Intval('log_name') ) !== 0 && preg_match("#^[0-9]{10}$#is", $log_name) && file_exists(EXBB_DATA_DIR_LOGS . '/' . $log_name . '.php')) {

		unlink(EXBB_DATA_DIR_LOGS . '/' . $log_name . '.php');
		$fm->_WriteLog(sprintf($fm->LANG['ClearLogInFile'], date("d.m.y", $log_name)), 1);
	}
	clearstatcache();

	if ($fm->_String('DelAllLog') != '') {
		$d = dir(EXBB_DATA_DIR_LOGS);
		while (false !== ( $file = $d->read() )) {
			if (preg_match("#^([0-9]{10})\.php$#is", $file)) {
				unlink(EXBB_DATA_DIR_LOGS . '/' . $file);
			}
		}
		$d->close();
		$fm->_WriteLog(sprintf($fm->LANG['ClearLogInFile'], $fm->LANG['DelAllLogs']), 1);
	}

	$log_name = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
	if ($fm->_Intval('logdate') !== 0 && preg_match("#^[0-9]{10}$#is", $log_name)) {
		$log_name = $fm->input['logdate'];
	}

	$logARRAY = array();
	$d = dir(EXBB_DATA_DIR_LOGS);
	while (false !== ( $file = $d->read() )) {
		if (preg_match("#^([0-9]{10})\.php$#is", $file, $match)) {
			$logARRAY[intval($match[1])] = 1;
		}
	}
	$d->close();

	ksort($logARRAY);
	$selectlog = '';
	foreach ($logARRAY as $date => $flag) {
		$selected = ( intval($date) === $log_name ) ? ' selected' : '';
		$selectlog .= "<option value=\"" . $date . "\"" . $selected . "> " . date("d.m.y", $date) . " </option>\n";

	}

	if (file_exists(EXBB_DATA_DIR_LOGS . '/' . $log_name . '.php')) {
		$logdata = file(EXBB_DATA_DIR_LOGS . '/' . $log_name . ".php");
		unset( $logdata[0] );
		$logdata = implode("<br>", array_reverse($logdata));
	}
	else {
		$logdata = $fm->LANG['LogFileNotFound'];
	}

	$logdata = preg_replace(array( "#\(ad\) :: #is", "#\(mo\) :: #is" ), array( "(<font color=\"red\">{$fm->LANG['LogAdmin']}</font>) :: ", "(<font color=\"#0000ff\">{$fm->LANG['Moderation']}</font>) :: " ), $logdata);

	$fm->LANG['LogTitle'] = sprintf($fm->LANG['LogTitle'], ( $fm->exbb['log'] === true ) ? $fm->LANG['ForumLogOn'] : $fm->LANG['ForumLogOff']);
	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/logfile.tpl' );
	include( './admin/footer.tpl' );
}
elseif ($fm->input['action'] == 'massmail') {
	if ($fm->_Intval('dosend') === 1) {
		if ($fm->input['subject'] == '' || $fm->input['message'] == '') {
			$fm->_Message($fm->LANG['AdminMassMail'], $fm->LANG['EmailNotEmpty'], '', 1);
		}
		$allusers = $fm->_Read(EXBB_DATA_USERS_LIST, false);
		SkipMails();
		array_filter($allusers, 'MAP_MAIL');
		unset( $allusers );

		$total_mails = sizeof($usersmails);
		if ($total_mails > 0) {
			$message = sprintf($fm->LANG['MassMailText'], $fm->exbb['boardname'], $fm->exbb['boardurl'], $fm->input['message']);
			$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $usersmails, $fm->input['subject'], $message);
			$fm->_WriteLog($fm->LANG['LogMassMail'], 1);
			$fm->_Message($fm->LANG['AdminMassMail'], sprintf($fm->LANG['MassMailSended'], $total_mails), 'setmembers.php?action=massmail', 1);
		}
		else {
			$fm->_Message($fm->LANG['AdminMassMail'], $fm->LANG['EmailAdminError'], '', 1);
		}
	}
	else {
		include( './admin/all_header.tpl' );
		include( './admin/nav_bar.tpl' );
		include( './admin/mass_mail.tpl' );
		include( './admin/footer.tpl' );
	}
}
elseif ($fm->input['action'] == 'censor') {
	if ($fm->_String('process') != '' && $fm->_POST === true) {
		$fp = fopen(EXBB_DATA_BADWORDS, 'a+');
		flock($fp, 2);
		ftruncate($fp, 0);
		fwrite($fp, "<? die; ?>\n" . $fm->input['wordarray']);
		fflush($fp);
		flock($fp, 3);
		fclose($fp);
		if (file_exists(EXBB_DATA_BADWORDS)) {
			$fm->_WriteLog($fm->LANG['LogCensor'], 1);
			$fm->_Message($fm->LANG['Censor'], $fm->LANG['BadfilterOk'], 'setmembers.php?action=censor', 1);
		}
		else {
			$fm->_Message($fm->LANG['Censor'], $fm->LANG['BadfilterFail'], '', 1);
		}
	}
	else {
		$bads = '';
		if (file_exists(EXBB_DATA_BADWORDS)) {
			$bads = file(EXBB_DATA_BADWORDS);
			unset( $bads[0] );
			$bads = implode("", $bads);
		}
		$bads = ( $bads != '' ) ? $bads : "damn=d*amn\nhell=h*ll";

		include( './admin/all_header.tpl' );
		include( './admin/nav_bar.tpl' );
		include( './admin/badword.tpl' );
		include( './admin/footer.tpl' );
	}
}
else {
	$_FirstStep = false;
	if ($fm->input['action'] == 'find') {
		if ($fm->_String('username') == "") {
			if ($fm->_String('usermail') == "") {
				$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['NoSearchVars'], '', 1);
			}
			if ($fm->_Chek_Mail('usermail') === false) {
				$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['WrongEmail'], '', 1);
			}
		}
		$allusers = $fm->_Read(EXBB_DATA_USERS_LIST, false);

		$username = preg_quote($fm->_LowerCase($fm->input['username']));
		$usermail = preg_quote($fm->input['usermail']);
		$select_data = '';
		ksort($allusers);
		foreach ($allusers as $id => $info) {
			if (preg_match("#^" . $username . "#is", $info['n']) && preg_match("#^" . $usermail . "#is", $info['m'])) {
				$select_data .= '<option value="' . $id . '">' . $info['n'] . '</option>';
			}
		}

		if (empty( $select_data )) {
			$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['UserNotFound'], '', 1);
		}
	}
	else {
		$_FirstStep = true;
	}

	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/user_select.tpl' );
	include( './admin/footer.tpl' );
}
include( 'page_tail.php' );


/*
	Functions
*/

function UpdateAllusersInfo() {
	global $fm;
	@set_time_limit(360);

	$users = array();
	$dirtoread = EXBB_DATA_DIR_MEMBERS . '/';
	$d = dir($dirtoread);
	$fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST);
	while (false !== ( $file = $d->read() )) {
		if (preg_match("#^([0-9]+)\.php$#is", $file, $match)) {
			if (filesize($dirtoread . $file) <= 100) {
				unlink($dirtoread . $file);
			}
			else {
				$uid = $match[1];
				$userinfo = $fm->_Getmember($uid);
				if ($userinfo && !empty( $userinfo['name'] )) {
					$users[$userinfo['id']]['n'] = $fm->_LowerCase($userinfo['name']);
					$users[$userinfo['id']]['m'] = $userinfo['mail'];
					$users[$userinfo['id']]['p'] = $userinfo['posts'];
				}
				else {
					unlink($dirtoread . $file);
				}
			}
		}
	}
	$d->close();

	ksort($users);
	end($users);
	$last_id = key($users);
	$totalusers = count($users);
	reset($users);

	$last_name = GetName($last_id);

	$fm->_SAVE_STATS(array( "lastreg" => array( $last_name, 0 ), "last_id" => array( $last_id, 0 ), "totalmembers" => array( $totalusers, 0 ) ));
	$fm->_Write($fp_allusers, $users);

	$includemode = 'update';
	include( 'modules/birstday/setmembers.php' );

	return $totalusers;
}

function deletemember() {
	global $fm;

	if (( $userid = $fm->_Intval('userid') ) === 0 || !file_exists(EXBB_DATA_DIR_MEMBERS . '/'. $userid . '.php')) {
		$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['UserNotFound'], '', 1);
	}

	if (unlink(EXBB_DATA_DIR_MEMBERS . '/' . $userid . '.php')) {
		if (file_exists('messages/' . $userid . '-msg.php')) {
			unlink('messages/' . $userid . '-msg.php');
		}
		if (file_exists('messages/' . $userid . '-out.php')) {
			unlink('messages/' . $userid . '-out.php');
		}
		/*start clear birstday data file */
		if (file_exists('modules/birstday/data/birstday_data.php')) {
			$birstdays = $fm->_Read2Write($fp_birst, 'modules/birstday/data/birstday_data.php', false);
			$SaveFlag = false;
			foreach ($birstdays as $day => $users) {
				foreach ($users as $id => $info) {
					if ($id == $userid) {
						unset( $birstdays[$day][$userid] );
						$SaveFlag = true;
					}
				}
				if (count($birstdays[$day]) == 0) {
					unset( $birstdays[$day] );
					$SaveFlag = true;
				}
			}
			( $SaveFlag === true ) ? $fm->_Write($fp_birst, $birstdays) : $fm->_Fclose($fp_birst);
		}
		/*end clear birstday data file */
		UpdateAllusersInfo();
		$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['UserDeleted'], 'setmembers.php', 1);
	}
	else {
		$fm->_Message($fm->LANG['UserAdmin'], $fm->LANG['UserNotDeleted'], '', 1);
	}
}

function BunUnban($newstatus) {
	global $fm, $user;

	$SaveFlag = false;
	$banlist = $fm->_Read2Write($fp_ban, EXBB_DATA_BANNED_USERS_LIST, false);
	if ($user['status'] == 'banned' && $newstatus != 'banned') {
		if (isset( $banlist[$user['id']] )) {
			unset( $banlist[$user['id']] );
			$SaveFlag = true;
		}

	}
	elseif ($user['status'] != 'banned' && $newstatus == 'banned') {
		$banlist[$user['id']]['m'] = $user['mail'];
		$banlist[$user['id']]['ip'] = $user['ip'];
		$SaveFlag = true;
	}
	( $SaveFlag === true ) ? $fm->_Write($fp_ban, $banlist) : $fm->_Fclose($fp_ban);
	unset( $banlist );
	$user['status'] = $newstatus;

	return true;
}

?>
