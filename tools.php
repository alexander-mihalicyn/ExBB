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
$fm->_LoadLang('tools');

switch ($fm->input['action']) {
	case 'online'   :
		showOnline();
	break;
	case 'rules'    :
		showHelpRules('Rules');
	break;
	case 'help'    :
		showHelpRules('Help');
	break;
	case 'bbcode'    :
		showHelpBBCode();
	break;
	case 'smiles'   :
		showsmiles();
	break;
	case 'keyboard'    :
		showKeyboard();
	break;
	case 'mail'    :
		mailtouser();
	break;
	case 'members'  :
		memberslist();
	break;
	case 'banmembers'  :
		banmemberslist();
	break;
	default:
		loadModule();
	break;
}
include( 'page_tail.php' );

function get_smilescat($var) {
	global $curcatid;

	return ( $curcatid == $var['cat'] );
}

function showOnline() {
	global $fm;

	$onlinedata = $fm->_Read(EXBB_DATA_MEMBERS_ONLINE);
	$output = '';
	foreach ($onlinedata as $id => $online) {
		if ($fm->exbb['visiblemode'] === true && !defined('IS_ADMIN') && $online['v'] === true) {
			continue;
		}
		if ($online['pf'] !== false && !isset( $fm->user['private'][$online['pf']] ) && !defined('IS_ADMIN')) {
			$online['in'] = $fm->LANG['BoardMain'];
		}
		$actdate = $fm->_DateFormat($online['t'] + $fm->user['timedif'] * 3600);
		$bot = ( $online['b'] !== false ) ? ' ' . $online['b'] . ' bot гуляет' : '';
		$online['n'] = ( $online['id'] !== 0 ) ? '<a href="profile.php?action=show&member=' . $online['id'] . '"  target="_blank" title="' . $fm->LANG['UserProfile'] . ' ' . $online['n'] . '">' . $online['n'] . '</a>' : $online['n'];
		$online['ip'] = ( defined('IS_ADMIN') ) ? sprintf($fm->LANG['ViewIpInfo'], $online['ip']) : '';
		include( './templates/' . DEF_SKIN . '/showonline_data.tpl' );
	}

	$fm->_Title = ' :: ' . $fm->LANG['WhoOnline'];
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/logos.tpl' );
	include( './templates/' . DEF_SKIN . '/showonline.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function showHelpRules($mode) {
	global $fm;
	$fm->_LoadLang('help_rules');

	$PageTitle = ( $mode === 'Rules' ) ? $fm->LANG['ForumRules'] : $fm->LANG['Help'];
	$count = ( is_array($fm->LANG[$mode . 'TITLE']) ) ? count($fm->LANG[$mode . 'TITLE']) : 0;

	if ($count) {
		$topics = $content = '';
		foreach ($fm->LANG[$mode . 'TITLE'] as $id => $topic) {
			$text = $fm->LANG[$mode . 'TEXT'][$id];
			$desc = $fm->LANG[$mode . 'DESC'][$id];
			$desc = ( $desc === '' ) ? '' : '<br> &nbsp; ' . $desc;
			include( './templates/' . DEF_SKIN . '/helprules_data.tpl' );
			$color = ( !( $id % 2 ) ) ? 'row1' : 'row2';
		}
	}
	else {
		$rules_topics = "<center>Правила не установлены</center>";
		$rules_content = '';
	}
	$fm->_Title = ' :: ' . $PageTitle;
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/logos.tpl' );
	include( './templates/' . DEF_SKIN . '/helprules.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function showHelpBBCode() {
	global $fm;

	$fm->_LoadLang('formcode');

	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/bb_help.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function showsmiles() {
	global $fm, $curcatid;

	$sm_list = $fm->_Read(EXBB_DATA_SMILES_LIST);

	$smoption = '';
	if (count($sm_list['cats']) === 0) {
		$fm->_Message(' :-)', $fm->LANG['SmilesNoCats']);
	}

	if (( $curcatid = $fm->_Intval('cat') ) === 0 || !isset( $sm_list['cats'][$curcatid] )) {
		ksort($sm_list['cats'], SORT_NUMERIC);
		reset($sm_list['cats']);
		$curcatid = key($sm_list['cats']);
	}

	foreach ($sm_list['cats'] as $catid => $catname) {
		$selected = ( $curcatid === $catid ) ? ' selected' : '';
		$smoption .= '<option value="' . $catid . '"' . $selected . '>' . $catname . '</option>';
	}

	$smiles_list = array_filter($sm_list['smiles'], "get_smilescat");
	unset( $sm_list );

	if (count($smiles_list) === 0) {
		$fm->_Message(' :-)', $fm->LANG['NoSmilesInCat']);
	}

	$keys = array_keys($smiles_list);
	$k = 1;
	while (count($keys) < ( ceil(count($keys) / 3) ) * 3) {
		$keys[] = "emptysmile_" . $k++;
	}

	$get_param = 'tools.php?action=smiles&cat=' . $curcatid . '&p={_P_}';
	$pages = Print_Paginator(count($keys), $get_param, 30, 8, $first, true);
	$keys = array_slice($keys, $first, 30);

	$i = 0;
	$datashow = '';
	foreach ($keys as $code) {
		$smile = "cell" . $i;
		$$smile = ( isset( $smiles_list[$code] ) ) ? '<a href="#" onClick="opener.bbcode(0,\'' . $code . '\');self.focus();" title="' . $smiles_list[$code]['emt'] . '"><img src="./im/emoticons/' . $smiles_list[$code]['img'] . '" border="0" alt="' . $smiles_list[$code]['emt'] . '" /></a>' : '&nbsp;';
		$i++;
		if ($i === 3) {
			$i = 0;
			include( './templates/' . DEF_SKIN . '/smiles_data.tpl' );
		}
	}

	$fm->_Title = ' :-)';
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/smiles_show.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );

	return true;
}

function showKeyboard() {
	global $fm;

	$fm->_LoadLang('formcode');

	$fm->_Title = ' :: ' . $fm->LANG['HelpKeyboard'];
	$fm->_Link = "\n" . '<link rel="stylesheet" href="./templates/' . DEF_SKIN . '/keyboard.css" type="text/css">';
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/keyboard.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function mailtouser() {
	global $fm;

	if ($fm->exbb['emailfunctions'] !== true) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['MailFunctionClosed']);
	}

	if ($fm->user['id'] === 0) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['GuestMail']);
	}

	$users = $fm->_Read(EXBB_DATA_USERS_LIST);
	if (( $user_id = $fm->_Intval('member') ) === 0 || !isset( $users[$user_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	unset( $users );

	$user = $fm->_Getmember($user_id);
	if ($fm->_Boolean($fm->input, 'dosend') === true) {
		if (!defined('IS_ADMIN')) {
			if (isset( $_SESSION['lastposttime'] ) && ( $_SESSION['lastposttime'] + $fm->exbb['flood_limit'] ) > $fm->_Nowtime) {
				$fm->_Message($fm->LANG['MainMsg'], sprintf($fm->LANG['FloodLimitNew'], $fm->exbb['flood_limit']));
			}
			$_SESSION['lastposttime'] = $fm->_Nowtime;
		}

		if ($fm->_String('subject') === '' || $fm->_String('message') === '') {
			$fm->_Message($fm->LANG['MailByBoard'], $fm->LANG['NoEmptyFields']);
		}

		$fm->input['subject'] = $fm->bads_filter(substr($fm->input['subject'], 0, 255));
		$fm->input['message'] = $fm->bads_filter($fm->input['message']);

		$email = sprintf($fm->LANG['EmailByBordText'], $user['name'], $fm->user['name'], $fm->exbb['boardname'], $fm->exbb['boardurl']) . $fm->input['message'];
		$fm->_Mail($fm->exbb['boardname'], $fm->user['mail'], $user['mail'], $fm->input['subject'], $email);
		$fm->_Message($fm->LANG['MailByBoard'], $fm->LANG['SendMailOk'], 'index.php');
	}
	else {
		$fm->_Title = ' :: ' . $fm->LANG['MailByBoard'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/logos.tpl' );
		include( './templates/' . DEF_SKIN . '/mailform.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
}


function banmemberslist() {
	global $fm;
	$banlist = $fm->_Read(EXBB_DATA_BANNED_USERS_LIST);

	//$userskeys = array_keys($users);
	$banlist_keys = array_keys($banlist);

	$banmembers_data = '';
	$time_end = time();
	$is_moder = ( $fm->user['status'] == 'ad' || $fm->user['status'] == 'sm' ) ? true : false;

	foreach ($banlist_keys AS $key => $user_id) {
		//Если есть информация о бане - это второй цикл
		if ($fm->_CheckBanMember($user_id)) {
			continue;
		}

		$user_info = $fm->_Getmember($user_id);
		if (!$user_info) {
			continue;
		}

		$user = array();
		$user['user_name'] = $user_info['name'];
		$user_name = '<font color=\'red\'>' . $user_info['name'] . '</font>';
		$user['date'] = 'N/A';
		$user['end'] = 'никогда';
		$user['days'] = 'вечно';
		$user['whounban'] = '-';
		$user['reason'] = 'N/A';

		include( './templates/' . DEF_SKIN . '/banmemblist_data.tpl' );
		unset( $user );
		unset( $user_info );
	}

	$dirtoread = 'data/banned_users/';
	$d = dir($dirtoread);

	while (false !== ( $file = $d->read() )) {
		if (preg_match("#^([0-9]+)\.php$#is", $file, $matches)) {
			$user_id = $matches[1];

			$user_info = $fm->_Getmember($user_id);

			//Авторазбанивание
			$fm->_AutoUnBan($user_info);

			$user = $fm->_GetBanMember($user_id);

			if (( ( isset( $user['permanently'] ) && $user['permanently'] === false ) || !isset( $user['permanently'] ) ) && $user['end'] < $time_end && !$is_moder) {
				continue;
			}
			$user_name = ( ( ( isset( $user['permanently'] ) && $user['permanently'] === false ) || !isset( $user['permanently'] ) ) && ( $user['end'] < time() OR $user['days'] === 0 ) ) ? $user['user_name'] : '<font color=\'red\'>' . $user['user_name'] . '</font>';
			$user['date'] = $fm->_DateFormat($user['date']);

			if (( isset( $user['permanently'] ) && $user['permanently'] == false ) || !isset( $user['permanently'] )) {
				$user['end'] = $fm->_DateFormat($user['end']);
				$user['days'] = $user['days'] > 0 ? $user['days'] : $fm->LANG['BanUnSeted'];
			}
			else {
				$user['days'] = 'вечно';
				$user['end'] = 'никогда';
			}

			$user['whounban'] = ( isset( $user['whounban_id'] ) AND $user['whounban_id'] > 0 ) ? '<a href="profile.php?action=show&member=' . $user['whounban_id'] . '" title="' . $fm->LANG['UserProfile'] . ' ' . $user['whounban_name'] . '">' . $user['whounban_name'] . '</a>' : ( $user['days'] === 0 ? 'Auto' : '-' );
			// $user['who_name'] = (isset($fm->LANG['Pun'.$status]) ? $fm->LANG['Pun'.$status].' - ' : '').$user['who_name'];

			include( './templates/' . DEF_SKIN . '/banmemblist_data.tpl' );
			unset( $user );
			unset( $user_info );
		}
	}

	$d->close();

	$fm->_Title = ' :: ' . $fm->LANG['BanMemberlist'];
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/logos.tpl' );
	include( './templates/' . DEF_SKIN . '/banmemblist.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function memberslist() {
	global $fm;
	if (!$fm->user['id']) {
		$fm->_Message($fm->LANG['MainMsg'], "Гости не могут просматривать список пользователей.
<br>
<br>
<a href = \"loginout.php\">Войдите</a> или <a href = \"register.php\">зарегистрируйтесь</a>");
	}
	$sort = $fm->_String('s');
	$order = $fm->_String('order', 'ASC');

	$users = $fm->_Read(EXBB_DATA_USERS_LIST);
	switch ($sort) {
		case 'p':
			uasort($users, 'sort_by_post');
		break;
		case 'n':
			uasort($users, 'sort_by_name');
		break;
		default :
			ksort($users, SORT_NUMERIC);
		break;
	}

	if ($order == 'DESC') {
		$users = array_reverse($users, true);
	}

	$ASC_selcted = ( $order == 'ASC' ) ? ' selected="selected"' : '';
	$DESC_selcted = ( $order == 'DESC' ) ? ' selected="selected"' : '';

	$d_selected = ( $sort === 'd' ) ? ' selected="selected"' : '';
	$p_selected = ( $sort === 'p' ) ? ' selected="selected"' : '';
	$n_selected = ( $sort === 'n' ) ? ' selected="selected"' : '';

	$per_page = ( abs($fm->_Intval('pg', 25) > 100) ) ? 100 : abs($fm->input['pg']);
	$get_param = 'tools.php?action=members&s=' . $sort . '&order=' . $order . '&p={_P_}&pg=' . $per_page;
	$per_page = ( abs($per_page) > 100 ) ? 100 : abs($per_page);
	$pages = Print_Paginator(count($users), $get_param, $per_page, 8, $first, true);

	$userskeys = array_slice(array_keys($users), $first, $per_page);

	$members_data = '';
	foreach ($userskeys as $key => $user_id) {
		$user = $fm->_Getmember($user_id);
		switch ($user['status']) {
			case 'ad'        :
				$status = $fm->LANG['Admin'];
			break;
			case 'sm'        :
				$status = $fm->LANG['SuperModer'];
			break;
			case 'me'        :
				$status = $fm->LANG['User'];
			break;
			case 'banned'    :
				$status = $fm->LANG['Banned'];
			break;
		}
		$user['title'] = ( $user['title'] != '' ) ? $user['title'] : $status;
		$user['joined'] = date("d.m.Y", $user['joined']);
		$user['location'] = ( $user['location'] != '' ) ? $user['location'] : '&nbsp;';
		$user['mail'] = ( $user['showemail'] === true ) ? '<a href="mailto:' . $user['mail'] . '">' . $fm->LANG['Write'] . '</a>' : '<a href="tools.php?action=mail&member=' . $user_id . '">' . $fm->LANG['Write'] . '</a>';
		$user['mail'] = ( $fm->exbb['emailfunctions'] !== true || $fm->user['id'] === 0 ) ? '&nbsp;' : $user['mail'];
		if ($fm->exbb['redirect'] && $user['www'] !== '' && $user['www'] != 'http://' && !stristr($user['www'], 'http://www.' . $fm->exbb_domain) && !stristr($user['www'], 'http://' . $fm->exbb_domain)) {
			$user['www'] = $fm->out_redir . $user['www'];
		}
		$user['www'] = ( $user['www'] !== '' && $user['www'] !== 'http://' ) ? '<a href="' . $user['www'] . '" target="_blank">' . $fm->LANG['Looked'] . '</a>' : '&nbsp;';
		$user['icq'] = ( $user['icq'] != '' ) ? '<a href="' . ( ( $fm->exbb['redirect'] ) ? $fm->out_redir : '' ) . 'http://people.icq.com/' . $user['icq'] . '"><img src="http://people.icq.com/scripts/online.dll?icq=' . $user['icq'] . '&img=5" align=abscenter width=18 height=18 border=0></a>' : '&nbsp;';
		$class = ( !( $key % 2 ) ) ? 'row1' : 'row4';
		include( './templates/' . DEF_SKIN . '/memblist_data.tpl' );
		unset( $user );
	}

	$fm->_Title = ' :: ' . $fm->LANG['Memberlist'];
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/logos.tpl' );
	include( './templates/' . DEF_SKIN . '/memblist.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function loadModule() {
	global $fm;

	if ($fm->input['action'] === '' || !preg_match('#^[a-zA-Z0-9\-_]+$#is', $fm->input['action'])) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	$modulefile = 'modules/' . $fm->input['action'] . '/frontindex.php';

	if (!file_exists($modulefile) || $fm->exbb[$fm->input['action']] === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['ModNotInstalled']);
	}
	include( $modulefile );
}

?>