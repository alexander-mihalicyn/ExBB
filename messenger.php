<?php
/****************************************************************************
 * ExBB v.1.1                                                                *
 * Copyright (c) 2002-20хх by Alexander Subhankulov aka Warlock                *
 *                                                                            *
 * http://www.exbb.revansh.com                                                *
 * email: admin@exbb.revansh.com                                            *
 *                                                                            *
 * Modified in the PM Box Comfort v.1.1 by Mutalov Alisher aka Markus®        *
 *                                                                            *
 * http://www.tvoyweb.ru                                                    *
 * http://www.tvoyweb.ru/forums                                                *
 * email: admin@tvoyweb.ru                                                    *
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
$fm->_LoadLang('messenger');
$fm->_Title = ' :: ' . $fm->LANG['PM'];

if ($fm->exbb['pm'] === false && !defined('IS_ADMIN')) {
	$fm->_Message($fm->LANG['PM'], $fm->LANG['PMClosed']);
}
if ($fm->user['id'] === 0) {
	$fm->_Message($fm->LANG['PM'], $fm->LANG['UserUnreg'], 'loginout.php');
}

$InBoxIcon = './templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/inboxpm.gif';
$OutBoxIcon = './templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/outboxpm.gif';
$NewPMIcon = './templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/newpm.gif';

if ($fm->input['action'] == 'inbox') {
	if ($fm->user['new_pm'] === true) {
		$UserInfo = $fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $fm->user['id'] . '.php', false);
		$UserInfo['new_pm'] = $fm->user['new_pm'] = false;
		$fm->_Write($fp_user, $UserInfo);
		unset( $UserInfo );
	}
	$inboxfile = EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-msg.php';
	$inboxdata = $fm->_Read($inboxfile);
	$TotalInbox = count($inboxdata);
	krsort($inboxdata);

	$inbox_data = "";
	if ($TotalInbox != 0) {
		foreach ($inboxdata as $message_id => $msg) {
			$UserName = $msg['from'];
			$MessageTitle = '<a href="messenger.php?action=read&msg=' . $message_id . '">' . $msg['title'] . '</a>';
			$TextState = ( !$msg['status'] ) ? '<b>' . $fm->LANG['No'] . '</b>' : $fm->LANG['Yes'];
			$ImgState = ( $msg['status'] ) ? '<img src="./templates/' . DEF_SKIN . '/im/readed.gif" alt="readed" title="' . $fm->LANG['ReadedSts'] . '">' : '<img src="./templates/' . DEF_SKIN . '/im/not_readed.gif"  alt="new" title="' . $fm->LANG['NotReadedSts'] . '">';
			$MessageDate = date("d.m.Y - H:i", $message_id + $fm->user['timedif'] * 3600);
			include( './templates/' . DEF_SKIN . '/pm_inbox_data.tpl' );
		}
	}
	else {
		@unlink($inboxfile);
	}

	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/pm_inbox.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
elseif ($fm->input['action'] == 'outbox') {
	$outboxfile = EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-out.php';
	$outboxdata = $fm->_Read($outboxfile);
	$totaloutbox = count($outboxdata);
	krsort($outboxdata);

	//Выводим список сообщений
	$inbox_data = "";
	if ($totaloutbox != 0) {
		foreach ($outboxdata as $message_id => $msg) {
			$UserName = $msg['to'];
			$MessageTitle = '<a href="messenger.php?action=outread&msg=' . $message_id . '">' . $msg['title'] . '</a>';
			$TextState = ( !$msg['status'] ) ? '<b>' . $fm->LANG['No'] . '</b>' : $fm->LANG['Yes'];
			$ImgState = ( $msg['status'] ) ? '<img src="./templates/' . DEF_SKIN . '/im/readed.gif" alt="readed" title="' . $fm->LANG['ReadedSts'] . '">' : '<img src="./templates/' . DEF_SKIN . '/im/not_readed.gif"  alt="new" title="' . $fm->LANG['NotReadedSts'] . '">';
			$MessageDate = date("d.m.Y - H:i", $message_id + $fm->user['timedif'] * 3600);
			include( './templates/' . DEF_SKIN . '/pm_inbox_data.tpl' );
		}
	}
	else {
		@unlink($outboxfile);
	}

	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/pm_outbox.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
elseif ($fm->input['action'] == 'read') {
	$inboxdata = $fm->_Read2Write($fp_inbox, EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-msg.php');

	if (( $message_id = $fm->_Intval('msg') ) === 0 || !isset( $inboxdata[$message_id] )) {
		$fm->_Fclose($fp_inbox);
		$fm->_Message($fm->LANG['InboxTitle'], $fm->LANG['MessNotExists']);
	}

	$sender_id = $inboxdata[$message_id]['frid'];
	if ($inboxdata[$message_id]['status'] === false) {
		$inboxdata[$message_id]['status'] = true;
		$fm->_Write($fp_inbox, $inboxdata);

		//check as readed into the sender outbox
		$senderoutbox = $fm->_Read2Write($fp_sender, EXBB_DATA_DIR_MESSAGES . '/' . $sender_id . '-out.php');

		if (isset( $senderoutbox[$message_id] )) {
			$senderoutbox[$message_id]['status'] = true;
			$fm->_Write($fp_sender, $senderoutbox);
		}
		else {
			$fm->_Fclose($fp_sender);
		}
		if (count($senderoutbox) == 0) {
			unlink(EXBB_DATA_DIR_MESSAGES . '/' . $sender_id . '-out.php');
		}
		unset( $senderoutbox );
	}
	else {
		$fm->_Fclose($fp_inbox);
	}
	$SenderName = $inboxdata[$message_id]['from'];
	$fm->LANG['MessageFrom'] = sprintf($fm->LANG['MessageFrom'], $SenderName, $fm->_DateFormat($message_id + $fm->user['timedif'] * 3600));

	$MessageTitle = $inboxdata[$message_id]['title'];
	$MessageText = $fm->formatpost($inboxdata[$message_id]['msg']);
	$email = $inboxdata[$message_id]['mail'];
	$yes_email = ( $email != false ) ? '| <a href="mailto:' . $email . '" title="' . $fm->LANG['SendEmail'] . ' (' . $email . ')">' . $email . '</a>' : '';
	$yes_email_im = ( $email != false ) ? '<a href="mailto:' . $email . '" title="' . $fm->LANG['SendEmail'] . ' (' . $email . ')"><img src="./templates/' . DEF_SKIN . '/im/pm_email.gif" alt="' . $email . '" border="0" /></a>' : '';

	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/pm_read.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
elseif ($fm->input['action'] == 'outread') {
	$outboxdata = $fm->_Read(EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-out.php');

	if (( $message_id = $fm->_Intval('msg') ) === 0 || !isset( $outboxdata[$message_id] )) {
		if (count($outboxdata) <= 0) {
			unlink(EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-out.php');
		}
		$fm->_Message($fm->LANG['OutboxTitle'], $fm->LANG['MessNotExists']);
	}

	$MessageTitle = $outboxdata[$message_id]['title'];
	$MessageText = $fm->formatpost($outboxdata[$message_id]['msg']);
	$date = $fm->_DateFormat($message_id + $fm->user['timedif'] * 3600);
	$OwnerName = $outboxdata[$message_id]['to'];

	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/pm_outread.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
elseif (( $fm->input['action'] == 'send' && $fm->_String('dosend') == '' ) || ( $fm->input['action'] == 'new' || $fm->input['action'] == 'reply' || $fm->input['action'] == 'replyquote' )) {
	$fm->_LoadLang('formcode');
	$ToUserName = "";
	$MessageTitle = "";
	$MessageText = "";
	$message_id = "";
	$PreviewData = "";
	$preview = $fm->_String('preview');

	if ($fm->input['action'] == 'reply' || $fm->input['action'] == 'replyquote') {
		$inboxdata = $fm->_Read2Write($fp_inbox, EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-msg.php');

		if (( $message_id = $fm->_Intval('msg') ) === 0 || !isset( $inboxdata[$message_id] )) {
			$fm->_Fclose($fp_inbox);
			if (count($inboxdata) <= 0) {
				unlink(EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-msg.php');
			}
			$fm->_Message($fm->LANG['NewPMCreating'], $fm->LANG['MessNotExists']);
		}

		$sender_id = $inboxdata[$message_id]['frid'];
		$ToUserName = $inboxdata[$message_id]['from'];
		if ($inboxdata[$message_id]['status'] === false) {
			$inboxdata[$message_id]['status'] = true;
			$fm->_Write($fp_inbox, $inboxdata);
			//Отмечаем как прочитанное у отправителя
			$senderoutbox = $fm->_Read2Write($fp_senderoutbox, EXBB_DATA_DIR_MESSAGES . '/' . $sender_id . '-out.php');

			if (isset( $senderoutbox[$message_id] )) {
				$senderoutbox[$message_id]['status'] = true;
				$fm->_Write($fp_senderoutbox, $senderoutbox);
			}
			else {
				$fm->_Fclose($fp_senderoutbox);
			}
			if (count($senderoutbox) <= 0) {
				unlink(EXBB_DATA_DIR_MESSAGES . '/' . $sender_id . '-out.php');
			}
		}
		else {
			$fm->_Fclose($fp_inbox);
		}

		if ($fm->input['action'] == 'replyquote') {
			$MessageText = $inboxdata[$message_id]['msg'];
			$MessageText = '[quote]' . $MessageText . '[/quote]';
		}
		unset( $senderoutbox );
		$MessageTitle = 'RE:' . $inboxdata[$message_id]['title'];
	}
	elseif (( $ToUserID = $fm->_Intval('touser') ) != 0) {
		$ToUserInfo = $fm->_Getmember($ToUserID);
		$ToUserName = ( is_array($ToUserInfo) ) ? $ToUserInfo['name'] : $ToUserName;
		unset( $ToUserInfo );
	}
	if ($preview != '') {
		$fm->LANG['PreviewTitle'] = $fm->input['msgtitle'];
		$PreviewText = $fm->formatpost($fm->input['message']);
		include( './templates/' . DEF_SKIN . '/preview.tpl' );
		$ToUserName = $fm->input['tousername'];
		$MessageTitle = $fm->input['msgtitle'];
		$MessageText = $fm->input['message'];
	}

	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/pm_new.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
elseif ($fm->input['action'] == 'send') {

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	if ($fm->exbb['flood_limit'] > 0 && !defined('IS_ADMIN') && isset( $_SESSION['lastpm'] ) && ( $_SESSION['lastpm'] + $fm->exbb['flood_limit'] ) > $fm->_Nowtime) {
		$fm->_Message($fm->LANG['NewPMCreating'], sprintf($fm->LANG['FloodLimit'], $fm->exbb['flood_limit']));

	}

	$TextHash = md5($fm->input['message']);
	if (isset( $_SESSION['double'][$TextHash] )) {
		$fm->_Message($fm->LANG['NewPMCreating'], $fm->LANG['DoubleAddedOk'], $_SESSION['double'][$TextHash]);
	}

	if ($fm->input['tousername'] == '') {
		$fm->_Message($fm->LANG['NewPMCreating'], $fm->LANG['OwnerNeeded']);
	}
	if ($fm->input['msgtitle'] == '' || strlen($fm->input['msgtitle']) > 80) {
		$fm->_Message($fm->LANG['NewPMCreating'], $fm->LANG['TitleNeeded']);
	}
	if ($fm->input['message'] == '') {
		$fm->_Message($fm->LANG['NewPMCreating'], $fm->LANG['MessageNeeded']);
	}
	if (strlen($fm->input['message']) > $fm->exbb['max_posts']) {
		$fm->_Message($fm->LANG['NewPMCreating'], sprintf($fm->LANG['BigPost'], $fm->exbb['max_posts'] / 1024));
	}


	$allusers = $fm->_Read(EXBB_DATA_USERS_LIST, false);
	$tousername = $fm->_LowerCase($fm->input['tousername']);

	ksort($allusers);
	$touser_id = 0;
	foreach ($allusers as $id => $info) {
		if ($info['n'] == $tousername) {
			$touser_id = $id;
			break;
		}
	}
	unset( $allusers );

	if ($touser_id === 0) {
		$fm->_Message($fm->LANG['NewPMCreating'], $fm->LANG['UserNotFound']);
	}
	if ($touser_id === $fm->user['id']) {
		$fm->_Message($fm->LANG['NewPMCreating'], $fm->LANG['DoNotSendSelf']);
	}

	$touserdata = $fm->_Read2Write($fp_touser, EXBB_DATA_DIR_MEMBERS . '/' . $touser_id . '.php');
	$touserdata['new_pm'] = true;
	$fm->_Write($fp_touser, $touserdata);

	$MessageTitle = ( $fm->exbb['wordcensor'] ) ? $fm->bads_filter($fm->input['msgtitle']) : $fm->input['msgtitle'];
	$MessageText = ( $fm->exbb['wordcensor'] ) ? $fm->bads_filter($fm->input['message']) : $fm->input['message'];

	$toinbox = $fm->_Read2Write($fp_toinbox, EXBB_DATA_DIR_MESSAGES . '/' . $touser_id . '-msg.php');

	$toinbox[$fm->_Nowtime]['from'] = $fm->user['name'];
	$toinbox[$fm->_Nowtime]['title'] = $MessageTitle;
	$toinbox[$fm->_Nowtime]['msg'] = $MessageText;
	$toinbox[$fm->_Nowtime]['frid'] = $fm->user['id'];
	$toinbox[$fm->_Nowtime]['mail'] = ( $fm->input['show'] === 'yes' ) ? $fm->user['mail'] : false;
	$toinbox[$fm->_Nowtime]['status'] = false;
	$fm->_Write($fp_toinbox, $toinbox);
	unset( $toinbox );

	$fromoutbox = $fm->_Read2Write($fp_fromoutbox, EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-out.php');
	$fromoutbox[$fm->_Nowtime]['to'] = $touserdata['name'];
	$fromoutbox[$fm->_Nowtime]['title'] = $MessageTitle;
	$fromoutbox[$fm->_Nowtime]['msg'] = $MessageText;
	$fromoutbox[$fm->_Nowtime]['status'] = false;
	$fm->_Write($fp_fromoutbox, $fromoutbox);
	unset( $fromoutbox );

	$_SESSION['double'][$TextHash] = 'messenger.php';
	$_SESSION['lastpm'] = $fm->_Nowtime;

	/* Уведомления по E-mail о новых ЛС */
	if ($fm->exbb['emailfunctions'] === true && $fm->exbb['pmnewmes'] === true && isset( $touserdata['sendnewpm'] ) && $touserdata['sendnewpm'] === true) {
		$email = sprintf($fm->LANG['NewPMNotify'], $touserdata['name'], $fm->exbb['boardname'], $fm->exbb['boardurl'], $fm->user['name'], $MessageTitle, $MessageText);
		$subject = $fm->LANG['EmailNewPMTitle'];
		$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $touserdata['mail'], $subject, $email);
	}
	/* Уведомления по E-mail о новых ЛС */

	$fm->_Message($fm->LANG['NewPMCreating'], sprintf($fm->LANG['NewPMSendedOk'], $touserdata['name']), 'messenger.php');
}
elseif ($fm->input['action'] == 'deletemsg') {
	$fm->_Array('msg');
	if (count($fm->input['msg']) == 0) {
		$fm->_Message($fm->LANG['DeleteTitle'], $fm->LANG['DeleteNotSelect']);
	}
	if ($fm->_String('where') == '') {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	switch ($fm->input['where']) {
		case  'inbox':
			$fromfile = EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-msg.php';
		break;
		case 'outbox':
			$fromfile = EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-out.php';
		break;
	}
	$deletedata = $fm->_Read2Write($fp_del, $fromfile);

	foreach ($fm->input['msg'] as $message_id) {
		if (isset( $deletedata[$message_id] )) {
			unset( $deletedata[$message_id] );
		}
	}
	$fm->_Write($fp_del, $deletedata);

	if (count($deletedata) <= 0) {
		unlink($fromfile);
	}
	unset( $deletedata );
	$fm->_Message($fm->LANG['DeleteTitle'], $fm->LANG['SelDeleteOk'], 'messenger.php?action=' . $fm->input['where']);
}
else {
	$allmessages = $fm->_Read(EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-msg.php');
	$totalmessages = count($allmessages);
	if ($totalmessages === 0) {
		@unlink(EXBB_DATA_DIR_MESSAGES . '/' . $fm->user['id'] . '-msg.php');
	}
	unset( $allmessages );
	$fm->LANG['NewPMMessage'] = sprintf($fm->LANG['NewPMMessage'], $totalmessages, $fm->user['unread']);
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/pm_show.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}
include( 'page_tail.php' );
?>
