<?php
/****************************************************************************
 * ExBB v.1.1                                                                *
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
define('IN_EXBB', true);
include( './include/common.php' );

$fm->_GetVars();
$fm->_String('action');
$fm->_LoadLang('register');

if ($fm->input['action'] === 'show') {
	if (( $user_id = $fm->_Intval('member') ) === 0) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if (( $user = $fm->_Getmember($user_id) ) === false) {
		$fm->_Message($fm->LANG['UserInfo'], $fm->LANG['UserDeleted']);
	}

	$user['joined'] = $fm->_DateFormat($user['joined'] + $fm->user['timedif'] * 3600);
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);

	/* Moderator's Ban Panel for ExBB FM 1.0 RC2 by yura3d
	http://www.exbb.org/ */
	$is_moder = $to_moder = 0;
	$moders_ban = '';

	foreach ($allforums as $id => $data) {
		if (( $fm->user['status'] == 'ad' || $fm->user['status'] == 'sm' || isset( $data['moderator'][$fm->user['id']] ) ) && !$is_moder) {
			$is_moder = 1;
		}
		if (( $user['status'] == 'ad' || $user['status'] == 'sm' || isset( $data['moderator'][$user_id] ) ) && !$to_moder) {
			$to_moder = 1;
		}
	}

	if ($is_moder && !$to_moder) {
		$moders_ban = '';

		if ($user['status'] == 'banned' AND $fm->_CheckBanMember($user_id)) {
			$usrban = $fm->_Read('data/banned_users/' . $user_id . '.php');
			$moders_ban .= '<br />' . $fm->LANG['BanReason'] . ': <b>' . $usrban['reason'] . '</b> ' . $fm->LANG['BanDays'] . ' <b>' . $usrban['days'] . '</b> ' . $fm->LANG['BanDateEnd'] . ': <b>' . $fm->_DateFormat($usrban['end']) . '</b>';
		}

		$moders_ban .= '<hr/><form action="profile.php?action=show&member=' . $user_id . '&ban=yes" method="POST">';

		if ($user['status'] == 'banned') {
			$moders_ban .= '<input type="hidden" name="unban" value="1">';
		}
		else {
			$moders_ban .= $fm->LANG['BanDays'] . ':&nbsp;<input type="text" size="5" name="days">&nbsp;';
			$moders_ban .= $fm->LANG['BanReason'] . ':&nbsp;<input type="text" size="70" name="reason">';
		}

		$moders_ban .= '<input type="submit" value="' . ( ( $user['status'] == 'banned' ) ? $fm->LANG['BanUnSet'] : $fm->LANG['BanSet'] ) . '">';
		$moders_ban .= '</form>';

		if ($fm->_Boolean1('ban') AND ( $user['status'] == 'me' OR $user['status'] == 'banned' )) {
			$days = isset( $fm->input['days'] ) ? intval($fm->input['days']) : 0;
			$reason = isset( $fm->input['reason'] ) ? $fm->_String('reason') : '';
			$unban = isset( $fm->input['unban'] ) ? intval($fm->input['unban']) : 0;
			if ($reason != '' OR $unban == 1) {
				if ($days > 0 OR $unban == 1) {
					$days = $unban == 1 ? 0 : $days;
					$user_ban = $fm->_Read2Write($fp_ban, 'data/banned_users/' . $user_id . '.php');
					$user_ban['user_id'] = $user_id;
					$user_ban['user_name'] = $user['name'];
					$user_ban['days'] = $days;
					$user_ban['date'] = time();
					$user_ban['end'] = mktime(0, 0, 0, date('m'), date('d') + $days, date('Y'));
					$user_ban['reason'] = $unban == 1 ? $user_ban['reason'] : ( $reason ? $reason : '' );
					$user_ban['who_id'] = $fm->user['id'];
					$user_ban['who_name'] = ( isset( $fm->LANG['Pun' . $fm->user['status']] ) ? $fm->LANG['Pun' . $fm->user['status']] . ' ' : '' ) . $fm->user['name'];

					$banlist = $fm->_Read2Write($fp_banlist, EXBB_DATA_BANNED_USERS_LIST, false);

					if ($unban == 1) {
						$user_ban['whounban_id'] = $fm->user['id'];
						$user_ban['whounban_name'] = ( isset( $fm->LANG['Pun' . $fm->user['status']] ) ? $fm->LANG['Pun' . $fm->user['status']] . ' ' : '' ) . $fm->user['name'];

						$user_ban['permanently'] = false;//перманентный бан снят

						if (isset( $banlist[$user['id']] )) {
							unset( $banlist[$user['id']] );
						}
					}
					else {
						$banlist[$user['id']]['m'] = $user['mail'];
						$banlist[$user['id']]['ip'] = $user['ip'];

						unset( $user_ban['permanently'] );//бан не перманентный
					}

					$fm->_Write($fp_banlist, $banlist);
					$fm->_Write($fp_ban, $user_ban);
				}
				elseif ($days === -1) {
					$user_ban = $fm->_Read2Write($fp_ban, 'data/banned_users/' . $user_id . '.php');
					$user_ban['permanently'] = true; //вечный бан
					$user_ban['user_id'] = $user_id;
					$user_ban['user_name'] = $user['name'];
					$user_ban['days'] = -1;
					$user_ban['date'] = time();
					$user_ban['end'] = -1;
					$user_ban['reason'] = $reason ? $reason : '';
					$user_ban['who_id'] = $fm->user['id'];
					$user_ban['who_name'] = ( isset( $fm->LANG['Pun' . $fm->user['status']] ) ? $fm->LANG['Pun' . $fm->user['status']] . ' ' : '' ) . $fm->user['name'];

					$banlist = $fm->_Read2Write($fp_banlist, EXBB_DATA_BANNED_USERS_LIST, false);

					$banlist[$user['id']]['m'] = $user['mail'];
					$banlist[$user['id']]['ip'] = $user['ip'];

					$fm->_Write($fp_banlist, $banlist);
					$fm->_Write($fp_ban, $user_ban);
				}
				else {
					$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['BanErrorDays']);
				}
			}
			else {
				$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['BanErrorReason']);
			}
		}
		if ($fm->_Boolean1('ban')) {
			$user = $fm->_Read2Write($fp_user, 'members/' . $user_id . '.php');
			$user['status'] = ( $user['status'] == 'me' ) ? 'banned' : 'me';
			$fm->_Write($fp_user, $user);

			$fm->_WriteLog(sprintf(( $user['status'] == 'banned' ) ? $fm->LANG['UserBanLog'] : $fm->LANG['UserUnbanLog'], '<b>' . $fm->user['name'] . '</b>', '<b>' . $user['name'] . '</b>'), 2); // Запись в лог
			$fm->_Message($fm->LANG['UsersBan'], sprintf(( $user['status'] == 'banned' ) ? $fm->LANG['UserBanned'] : $fm->LANG['UserUnbanned'], $user['name']), 'profile.php?action=show&member=' . $user_id);
		}
	}

	if (!isset( $user['title'] ) || !$user['title']) {
		switch ($user['status']) {
			case 'ad' :
				$user['title'] = $fm->LANG['Admin'];
			break;
			case 'sm' :
				$user['title'] = $fm->LANG['SuperModer'];
			break;
			case 'me' :
				$user['title'] = $fm->LANG['User'];
			break;
			case 'banned' :
				$user['title'] = $fm->LANG['Banned'];
			break;
		}
	}

	$user['avatar'] = ( file_exists('im/avatars/' . $user['avatar']) ) ? $user['avatar'] : 'noavatar.gif';
	$avatar = '<img src="im/avatars/' . $user['avatar'] . '" style="padding: 2px;border: solid 1px grey;">';

	if (isset( $user['lastpost']['date'] )) {
		$postdate = $fm->_DateFormat($user['lastpost']['date'] + $fm->user['timedif']['usertime'] * 3600);
		$topicTitle = preg_replace("#([^\s]{32})(.+)#is", "$1&shy;$2", $user['lastpost']['name']);
		$lastpostdetails = $fm->LANG['LastPost'] . ': <a href="' . $user['lastpost']['link'] . '">' . $topicTitle . '</a> - ' . $postdate;
	}
	else {
		$lastpostdetails = $fm->LANG['NoPosts'];
	}

	$fm->_BOARDSTATS();
	$days_reged = max(1, round(( time() - $user['joined'] ) / 86400));
	$posts_per_day = sprintf($fm->LANG['PostsPerDay'], $user['posts'] / $days_reged);
	$percentage = ( $fm->_Stats['totalposts'] > 0 ) ? min(100, ( $user['posts'] / $fm->_Stats['totalposts'] ) * 100) : 0;
	$percentage = sprintf($fm->LANG['ProcTotal'], $percentage);

	$emailaddress = '&nbsp;';
	if ($fm->exbb['emailfunctions'] === true) {
		$emailaddress = '<a href="tools.php?action=mail&member=' . $user['id'] . '">' . $fm->LANG['ForumEml'] . '</a>';
		$emailaddress .= ( $user['showemail'] === true ) ? '-- <a href="mailto:' . $user['mail'] . '">' . $user['mail'] . '</a>' : '';
	}
	$_www = $user['www'];
	if ($fm->exbb['redirect'] && $user['www'] !== '' && $user['www'] != 'http://' && !stristr($user['www'], 'http://www.' . $fm->exbb_domain) && !stristr($user['www'], 'http://' . $fm->exbb_domain)) {
		$user['www'] = $fm->out_redir . $user['www'];
	}
	$homepage = ( $user['www'] == 'http://' || $user['www'] == '' ) ? '&nbsp;' : '<a href="' . $user['www'] . '" target="_blank">' . $_www . '</a>';
	$icqlogo = ( $user['icq'] !== '' ) ? '<img src="http://people.icq.com/scripts/online.dll?icq=' . $user['icq'] . '&img=5" align="abscenter" width="18" height="18" border="0">' : '';

	include( 'modules/birstday/profile_show.php' );
	include( 'modules/belong/profile_show.php' );
	include( 'modules/punish/profile.php' );
	$output = '';
	$countposts = 0;
	if (isset( $user['posted'] )) {
		arsort($user['posted']);
		$countposts = array_sum($user['posted']);
		foreach ($user['posted'] as $inforum => $posts) {
			$subforum = '';
			$pcatid = @$allforums[$inforum]['catid'];
			if (stristr($pcatid, 'f')) {
				$pforum = substr($pcatid, 1, strlen($pcatid) - 1);
				$subforum = '<a href="forums.php?forum=' . $pforum . '">' . $allforums[$pforum]['name'] . '</a> :: ';
			}
			$forumname = ( !isset( $allforums[$inforum] ) ) ? $fm->LANG['NoData'] : $subforum . '<a href="forums.php?forum=' . $inforum . '">' . $allforums[$inforum]['name'] . '</a>';
			$percent = sprintf('%.2f', $posts / $countposts * 100);
			$color = ( $percent >= 10 ) ? 'row1' : 'row2';
			$output .= <<<EOD
				<tr class="normal" valign=middle align=center>
					<td class="$color">{$forumname}</td>
					<td class="$color"><b>{$posts}</b></td>
					<td class="$color"><b>{$percent}%</b></td>
				</tr>
EOD;
		}
	}
	$fm->_Title = ' :: ' . $fm->LANG['UserInfo'];
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/logos.tpl' );
	include( './templates/' . DEF_SKIN . '/profile_show.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
elseif ($fm->input['action'] === 'lostpassword') {
	if ($fm->exbb['emailfunctions'] === false) {
		$fm->_Message($fm->LANG['SendPassTitle'], $fm->LANG['SendPassDisabled']);
	}

	if ($fm->_POST === true) {

		if ($fm->exbb['anti_bot'] === true && ( $fm->_String('captcha') == '' || !isset( $_SESSION['captcha'] ) || $fm->input['captcha'] !== $_SESSION['captcha'] )) {
			$fm->_Message($fm->LANG['SendPassTitle'], $fm->LANG['CaptchaError']);
		}
		if ($fm->_String('membername') === '') {
			$fm->_Message($fm->LANG['SendPassTitle'], $fm->LANG['NameEmpty']);
		}

		$membername = $fm->_LowerCase($fm->input['membername']);
		$allusers = $fm->_Read(EXBB_DATA_USERS_LIST);

		$m_id = 0;
		foreach ($allusers as $id => $info) {
			if ($info['n'] == $membername) {
				$m_id = $id;
				break;
			}
		}

		if ($fm->_Checkuser($m_id) === false) {
			$fm->_Message($fm->LANG['SendPassTitle'], $fm->LANG['SorryUserNotExists']);
		}

		$user = $fm->_Read2Write($fp_user, 'members/' . $m_id . '.php');

		if ($fm->_Boolean($fm->input, 'resend') === true) {
			if (!isset( $user['sendpass'] )) {
				$fm->_Fclose($fp_user);
				$fm->_Message($fm->LANG['SendPassTitle'], $fm->LANG['CannotSendPass']);
			}

			if (( $user['sendpass']['t'] + 86400 ) < $fm->_Nowtime) {
				unset( $user['sendpass'] );
				$fm->_Write($fp_user, $user);
				$fm->_Message($fm->LANG['SendPassTitle'], $fm->LANG['CannotSendPass']);
			}
			$user['sendpass']['t'] = $fm->_Nowtime;

			$fm->_Write($fp_user, $user);
			unset( $_SESSION['captcha'] );

			$email = sprintf($fm->LANG['SendPassEmail'], $fm->exbb['boardname'], $fm->exbb['boardurl'], $user['name'], $user['id'], $user['sendpass']['i'], $user['sendpass']['c'], $user['sendpass']['p']);
			$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $user['mail'], $fm->LANG['SendPassSubject'] . $fm->exbb['boardname'], $email);

			$fm->user['name'] = $user['name'];
			$fm->_WriteLog($fm->LANG['SendPassTitle']);

			$fm->_Message($fm->LANG['SendPassTitle'], $fm->LANG['NewPassSended'], 'index.php');
		}
		else {
			$user['sendpass']['i'] = mt_rand(10000, 99999);
			$user['sendpass']['c'] = md5(mt_rand($m_id, time()));
			$user['sendpass']['t'] = $fm->_Nowtime;
			$user['sendpass']['p'] = Generate_pass();

			$fm->_Write($fp_user, $user);
			unset( $_SESSION['captcha'] );

			$email = sprintf($fm->LANG['SendPassEmail'], $fm->exbb['boardname'], $fm->exbb['boardurl'], $user['name'], $user['id'], $user['sendpass']['i'], $user['sendpass']['c'], $user['sendpass']['p']);
			$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $user['mail'], $fm->LANG['SendPassSubject'] . $fm->exbb['boardname'], $email);

			$fm->user['name'] = $user['name'];
			$fm->_WriteLog($fm->LANG['SendPassTitle']);

			$fm->_Message($fm->LANG['SendPassTitle'], $fm->LANG['NewPassSended'], 'index.php');
		}
	}
	else {
		$fm->_Title = ' :: ' . $fm->LANG['SendPassTitle'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/logos.tpl' );
		include( './templates/' . DEF_SKIN . '/send_pass.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
}
elseif ($fm->input['action'] === 'activate') {
	if ($fm->_Intval('user') !== 0 && $fm->_String('code') !== '' && $fm->_Intval('actid') !== 0) {
		if ($fm->_Checkuser($fm->input['user']) === false) {
			$fm->_Message($fm->LANG['ActivatePass'], $fm->LANG['SorryUserNotExists']);
		}

		$user = $fm->_Read2Write($fp_user, 'members/' . $fm->input['user'] . '.php');

		if (!isset( $user['sendpass'] )) {
			$fm->_Fclose($fp_user);
			$fm->_Message($fm->LANG['ActivatePass'], $fm->LANG['CannotActivatePass']);
		}

		if (( $user['sendpass']['t'] + 86400 ) < $fm->_Nowtime) {
			unset( $user['sendpass'] );
			$fm->_Write($fp_user, $user);
			$fm->_Message($fm->LANG['ActivatePass'], $fm->LANG['CannotActivatePass']);
		}

		if ($fm->input['actid'] !== $user['sendpass']['i'] || $fm->input['code'] !== $user['sendpass']['c']) {
			$fm->_Fclose($fp_user);
			$fm->_Message($fm->LANG['ActivatePass'], $fm->LANG['ActWrongEntered']);
		}
		$user['pass'] = md5($user['sendpass']['p']);
		unset( $user['sendpass'] );
		$fm->_Write($fp_user, $user);

		$fm->user['name'] = $user['name'];
		$fm->_WriteLog($fm->LANG['ActivatePass']);

		$fm->_Message($fm->LANG['ActivatePass'], $fm->LANG['ActivatePassOk'], 'loginout.php');
	}
	$PageTitle = $fm->LANG['ActivatePass'];
	$ActIdTitle = $fm->LANG['PassActId'];
	$IdFiledName = 'actid';
	$PassActivated = true;
	$fm->_Title = ' :: ' . $fm->LANG['ActivatePass'];
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/logos.tpl' );
	include( './templates/' . DEF_SKIN . '/activate.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
elseif ($fm->input['action'] === 'savemodify') {
	if ($fm->user['id'] === 0) {
		$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['AuthNeed'], 'loginout.php');
	}

	if ($fm->_POST === false || !isset( $_SESSION['token'] ) || $_SESSION['token'] != $fm->_Intval('token')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	unset( $_SESSION['token'] );
	$fm->_Strings(array( 'password' => '', 'emailaddress' => '', 'icqnumber' => '', 'aolname' => '', 'homepage' => '', 'location' => '', 'interests' => '', 'signature' => '', 'noavatar' => '', 'useravatar' => '', 'timedifference' => '' ));

	if ($fm->input['password'] !== '') {
		if (strlen($fm->input['password']) < 6) {
			$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['PassLitle']);
		}
		if (strlen($fm->input['password']) > 16) {
			$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['PassBig']);
		}
		define("FM_NEWPASS", true);
		$fm->user['pass'] = md5($fm->input['password']);
	}

	/* Email validation */
	if ($fm->exbb['emailfunctions'] === true) {
		if ($fm->input['emailaddress'] === '') {
			$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['EmailEmpty']);
		}
		if ($fm->_Chek_Mail('emailaddress') === false) {
			$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['WrongEmail']);
		}
	}
	validate_items();
	//prints($fm->input);exit;
	if ($fm->exbb['avatars'] === true) {
		if ($fm->input['noavatar'] === '') {
			if ($fm->exbb['avatar_upload'] === true && ( $attach = $fm->Upload($fm->exbb['avatar_size'], 'personal/' . $fm->user['id'] . '-avatar', 'im/avatars/', 'avatar') ) !== false) {
				if (defined("UP_ERROR")) {
					$fm->_WriteLog(UP_ERROR);
					$fm->_Message($fm->LANG['ProfileEditing'], UP_ERROR);
				}
				else {
					$fm->input['useravatar'] = $attach['STORAGE'];;
				}
			}
			elseif ($fm->input['useravatar'] === '') {
				$fm->input['useravatar'] = $fm->user['avatar'];
			}
		}
		else {
			if (preg_match("#personal/#is", $fm->user['avatar']) && file_exists('im/avatars/' . $fm->user['avatar'])) {
				unlink('im/avatars/' . $fm->user['avatar']);
			}
			$fm->input['useravatar'] = 'noavatar.gif';
		}
	}
	else {
		$fm->input['useravatar'] = 'noavatar.gif';
	}

	if ($fm->exbb['emailfunctions'] === true && $fm->user['mail'] !== $fm->input['emailaddress']) {
		$allusers = $fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST);
		foreach ($allusers as $u_id => $info) {
			if ($info['m'] == $fm->input['emailaddress']) {
				$fm->_Fclose($fp_allusers);
				$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['EmailExist']);
			}
		}
		$allusers[$fm->user['id']]['m'] = $fm->input['emailaddress'];
		$fm->_Write($fp_allusers, $allusers);
	}

	$user = $fm->_Read2Write($fp_user, 'members/' . $fm->user['id'] . '.php');

	$user['pass'] = $fm->user['pass'];
	$user['mail'] = $fm->input['emailaddress'];
	$user['showemail'] = $fm->_Boolean($fm->input, 'showemail');
	$user['www'] = $fm->input['homepage'];
	$user['icq'] = $fm->input['icqnumber'];
	$user['aim'] = $fm->input['aolname'];
	$user['location'] = $fm->input['location'];
	$user['interests'] = $fm->input['interests'];
	$user['sig'] = $fm->input['signature'];
	$user['sig_on'] = $fm->_Boolean($fm->input, 'sig_on');
	$user['lang'] = Check_DefLangSkin('language', 'default_lang', $fm->input['default_lang']);
	$user['skin'] = Check_DefLangSkin('templates', 'default_style', $fm->input['default_style']);
	$user['timedif'] = $fm->input['timedifference'];
	$user['avatar'] = $fm->input['useravatar'];
	$user['visible'] = ( $fm->exbb['visiblemode'] === true && $fm->_Boolean($fm->input, 'visiblemode') === true ) ? true : false;
	$user['sendnewpm'] = ( $fm->exbb['pmnewmes'] === true && $fm->_Boolean($fm->input, 'pm_newmes') === true ) ? true : false;
	$user['posts2page'] = ( $fm->exbb['userperpage'] === true && $fm->_Intval('posts2page') !== 0 && $fm->input['posts2page'] <= 40 ) ? $fm->input['posts2page'] : $fm->exbb['posts_per_page'];
	$user['topics2page'] = ( $fm->exbb['userperpage'] === true && $fm->_Intval('topics2page') !== 0 && $fm->input['topics2page'] <= 50 ) ? $fm->input['topics2page'] : $fm->exbb['topics_per_page'];

	include( 'modules/birstday/profile_save.php' );
	$fm->_Write($fp_user, $user);

	$_SESSION['iden'] = md5($user['name'] . $user['pass'] . _SESSION_ID);
	$fm->_setcookie('exbbp', md5($user['pass']));

	$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['ProfileUpdated'], 'profile.php');
}
else {
	if ($fm->user['id'] === 0) {
		$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['AuthNeed'], 'loginout.php');
	}
	$_SESSION['token'] = $token = mt_rand(100000, 999999);
	$hidden = ( $fm->exbb['avatars'] === true && $fm->exbb['avatar_upload'] === true ) ? '<input type="hidden" name="MAX_FILE_SIZE" value="' . $fm->exbb['avatar_size'] . '">' : '';
	$langs_select = $style_select = $avatars_select = $hidden = $enctype = '';
	if ($fm->exbb['avatars']) {
		if ($fm->exbb['avatar_upload'] === true) {
			$enctype = ' enctype="multipart/form-data"';
		}

		$avatarsdir = 'im/avatars';
		$d = dir($avatarsdir);
		while (false !== ( $file = $d->read() )) {
			if (is_dir($avatarsdir . '/' . $file) || !preg_match("#\.(gif|jpg|bmp|png|jpeg|pjpeg)$#is", $file)) {
				continue;
			}

			if ($file == $fm->user['avatar']) {
				$avatars_select .= '<option value="' . $file . '" selected>' . $file . "</option>\n";
			}
			else {
				$avatars_select .= '<option value="' . $file . '">' . $file . "</option>\n";
			}
		}
		$d->close();

		$avatar_info = sprintf($fm->LANG['AvatarInfo'], $fm->exbb['avatar_size'], $fm->exbb['avatar_max_width'], $fm->exbb['avatar_max_height']);
	}

	$languagedir = 'language';
	$d = dir($languagedir);
	while (false !== ( $file = $d->read() )) {
		if (is_dir($languagedir . '/' . $file) && $file != '.' && $file != '..') {
			$selected = ( $file == $fm->user['lang'] ) ? ' selected="selected"' : '';
			$langs_select .= '<option value="' . trim($file) . '"' . $selected . '>' . ucfirst($file) . '</option>';
		}
	}
	$d->close();

	$styledir = 'templates';
	$d = dir($styledir);
	while (false !== ( $file = $d->read() )) {
		if (is_dir($styledir . '/' . $file) && $file != '.' && $file != '..') {
			$selected = ( $file == $fm->user['skin'] ) ? ' selected="selected"' : '';
			$style_select .= '<option value="' . trim($file) . '"' . $selected . '>' . $file . '</option>';
		}
	}
	$d->close();

	include( 'language/' . DEF_LANG . '/lang_tz.php' );
	$timezones = '';
	foreach ($tz as $shift => $zona) {
		if ($shift == $fm->user['timedif']) {
			$timezones .= '<option value="' . $shift . '" selected>' . $zona . '</option>';
		}
		else {
			$timezones .= '<option value="' . $shift . '">' . $zona . '</option>';
		}
	}
	$basetimes = $fm->_DateFormat($fm->_Nowtime);
	if ($fm->exbb['emailfunctions'] === true) {
		$showmyno = ( !$fm->user['showemail'] ) ? 'checked' : '';
		$showmyes = ( $fm->user['showemail'] ) ? 'checked' : '';

		/* Уведомления по E-mail о новых ЛС */
		if ($fm->exbb['pmnewmes'] == true) {
			$pm_newmes_no = ( !$fm->user['sendnewpm'] ) ? 'checked' : '';
			$pm_newmes_yes = ( $fm->user['sendnewpm'] ) ? 'checked' : '';
		}
		/* Уведомления по E-mail о новых ЛС */
	}

	$sig_onno = ( !$fm->user['sig_on'] ) ? 'checked' : '';
	$sig_onyes = ( $fm->user['sig_on'] ) ? 'checked' : '';

	/* Скрытый режим пребывания на форуме */
	if ($fm->exbb['visiblemode'] == 1) {
		$visiblemode_no = ( !$fm->user['visible'] ) ? 'checked' : '';
		$visiblemode_yes = ( $fm->user['visible'] ) ? 'checked' : '';
	}
	/* Скрытый режим пребывания на форуме */

	/* День рождения */
	$requirepass = false;
	include( 'modules/birstday/select.php' );
	/* День рождения */

	$fm->_Title = ' :: ' . $fm->LANG['ProfileEditing'];
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/logos.tpl' );
	include( './templates/' . DEF_SKIN . '/profile.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
include( 'page_tail.php' );

/*
	Functions
*/
function validate_items() {
	global $fm;

	$fm->_Chek_WWW('homepage');
	$fm->input['icqnumber'] = ( preg_match("/^[0-9]{5,9}$/", $fm->input['icqnumber']) ) ? $fm->input['icqnumber'] : '';
	$fm->input['aolname'] = ( ( $l = strlen($fm->input['aolname']) ) >= 3 && $l <= 32 ) ? $fm->input['aolname'] : '';
	$fm->input['location'] = ( ( $l = strlen($fm->input['location']) ) >= 3 && $l <= 100 ) ? $fm->input['location'] : '';
	$fm->input['interests'] = ( ( $l = strlen($fm->input['interests']) ) >= 3 && $l <= 100 ) ? $fm->input['interests'] : '';
	$fm->input['signature'] = ( strlen($fm->input['signature']) >= 3 ) ? $fm->input['signature'] : '';

	include( 'language/' . DEF_LANG . '/lang_tz.php' );
	$fm->input['timedifference'] = ( isset( $tz[$fm->input['timedifference']] ) ) ? $fm->input['timedifference'] : 0;

	if ($fm->exbb['wordcensor'] === true && $fm->bads_filter($fm->input['signature'], 0) === true) {
		$fm->_Message($fm->LANG['ProfileEditing'], $fm->LANG['NoProfanity']);
	}

	$siglines = explode("\n", $fm->input['signature']);

	if (!defined('IS_ADMIN') && ( count($siglines) > $fm->exbb['max_sig_lin'] || strlen($fm->input['signature']) > $fm->exbb['max_sig_chars'] )) {
		$fm->_Message($fm->LANG['ProfileEditing'], sprintf($fm->LANG['SigOptions'], $fm->exbb['max_sig_lin'], $fm->exbb['max_sig_chars']));
	}
	if (defined('IS_ADMIN')) {
		$fm->input['signature'] = $fm->html_replace($fm->input['signature']);
	}

	if ($fm->input['useravatar'] != '' && ( !preg_match("#^[A-Za-z0-9-_]{1,64}\.[A-Za-z]{3,4}$#is", $fm->input['useravatar']) || !file_exists('im/avatars/' . $fm->input['useravatar']) )) {
		$fm->input['useravatar'] = 'noavatar.gif';
	}

	return;
}

?>