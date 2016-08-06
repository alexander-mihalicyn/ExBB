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
define('FM_ADMIN_ID', 1);//ID админа форума

define('IN_EXBB', true);

include( './include/common.php' );

/*
	Не изменяйте эту константу, если не знаете зачем она!Best perfomance (more files) - 204800 (200 kB)Optimal - 150-200 kB Maximum - 300 kB
*/
define('FM_MAX_THREAD_SIZE', $fm->exbb['max_threads']); # 200 kB

define('FM_SUBPOST_TIME', $fm->exbb['sub_post'] * 60);//Интервал склевания поста

$fm->_GetVars();
$fm->_String('action');
$fm->_LoadLang('forums');

/* Штрафы на форуме */
include( 'modules/punish/p_error.php' );

switch ($fm->input['action']) {
	case 'new'            :
		newthread();
	break;
	case 'addnew'        :
		switch ($fm->_String('preview')) {
			case ''    :
				addnewthread();
			break;
			default    :
				newthread();
			break;
		};
	break;
	case 'replyquote'    :
	case 'reply'        :
		reply();
	break;
	case 'addreply'        :
		switch ($fm->_String('preview')) {
			case ''    :
				addreply();
			break;
			default    :
				reply();
			break;
		};
	break;
	case 'poll'            :
		poll_vote();
	break;
	default                :
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	break;
}
include( 'page_tail.php' );

function newthread() {
	global $fm;

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);

	if (( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);
	$fm->_GetModerators($forum_id, $allforums);
	CheckForumPerms($allforums[$forum_id]['stnew'], 'NewAdd');

	$fm->_Strings(array( 'topictitle' => '', 'description' => '', 'pollname' => '', 'pollansw' => '', 'inpost' => '' ));


	$fm->_LoadLang('formcode');

	$forumname = $allforums[$forum_id]['name'];

	$forumcodes = ( $fm->exbb['exbbcodes'] === true && $allforums[$forum_id]['codes'] === true ) ? true : false;

	$SetPoll = ( $fm->user['id'] !== 0 && $allforums[$forum_id]['polls'] === true && $fm->_Boolean($fm->input, 'poll') === true ) ? true : false;

	$smilesbutton = ( $fm->exbb['emoticons'] === true ) ? '<input type="checkbox" name="showsmiles" value="yes"' . ( isset( $fm->input['showsmiles'] ) || $fm->_String('preview') === '' ? ' checked> ' : '> ' ) . $fm->LANG['DoSmiles'] . '<br />' : '';

	$upload = ( $fm->exbb['file_upload'] === true && $allforums[$forum_id]['upload'] !== 0 && ( $fm->user['upload'] === true || $fm->exbb['autoup'] === true && $fm->user['id'] ) ) ? $allforums[$forum_id]['upload'] : 0;

	$enctype = ( $upload !== 0 ) ? ' enctype="multipart/form-data"' : '';

	$reged = ( $fm->user['id'] === 0 ) ? ' <a href="register.php">' . $fm->LANG['YouReged'] . '</a> ' : '';

	$pin_checked = ( $fm->_Boolean($fm->input, 'pin') === true ) ? ' checked' : '';
	$pintopic = ( $fm->_Moderator === true ) ? '<input type=checkbox name="pin" value="yes"' . $pin_checked . '> ' . $fm->LANG['PinTopic'] . '<br />' : '';

	$notify_checked = ( $fm->_Boolean($fm->input, 'notify') === true ) ? ' checked' : '';
	$emailnotify = ( $fm->user['id'] !== 0 && $fm->exbb['emailfunctions'] ) ? '<input type="checkbox" name="notify" value="yes"' . $notify_checked . '> ' . $fm->LANG['DoEmail'] . '<br>' : '';

	$PreviewData = '';
	if ($fm->_String('preview') !== '') {
		CheckPostSize('inpost');
		$html = ( defined('IS_ADMIN') && $fm->_Boolean($fm->input, 'html') === true ) ? true : false;
		$PreviewText = $fm->bads_filter($fm->formatpost($fm->input['inpost'], $html));
		$fm->LANG['PreviewTitle'] = $fm->bads_filter($fm->input['topictitle']);
		include( './templates/' . DEF_SKIN . '/preview.tpl' );
	}

	$fm->_OnlineLog($fm->LANG['TopicCreateInForum'] . ' <a href="forums.php?forum=' . $forum_id . '"><b>' . $forumname . '</b></a>', $privateID);

	$fm->_Title = ' :: ' . $fm->LANG['TopicCreate'];
	$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/board.js\"></script>
		<script type=\"text/javascript\" language=\"JavaScript\">
		var LANG = {
		Spoiler: '{$fm->LANG['Spoiler']}',
		SpoilerShow: '{$fm->LANG['SpoilerShow']}',
		SpoilerHide: '{$fm->LANG['SpoilerHide']}'
		};
		</script>";
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/post_addnew.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function addnewthread() {
	global $fm;
	check_captcha();
	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	if (( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	CheckPostSize('inpost');
	$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);
	$fm->_GetModerators($forum_id, $allforums);
	CheckForumPerms($allforums[$forum_id]['stnew'], 'NewAdd');

	if ($fm->exbb['flood_limit'] != 0 && $fm->_Moderator === false && isset( $_SESSION['lastposttime'] )) {
		$lastpost = $_SESSION['lastposttime'] + $fm->exbb['flood_limit'];
		if (( $_SESSION['lastposttime'] + $fm->exbb['flood_limit'] ) > $fm->_Nowtime) {
			$fm->_Fclose($fp_allforums);
			$fm->_Message($fm->LANG['TopicCreate'], sprintf($fm->LANG['FloodLimitNew'], $fm->exbb['flood_limit']));
		}
	}

	if ($fm->_String('topictitle') === '') {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['TopicCreate'], $fm->LANG['EmptyTitle']);
	}

	if (preg_match("#^[^A-Za-zА-Яа-яёЁ0-9\"'\*]#is", $fm->html_replace($fm->input['topictitle']))) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['TopicCreate'], $fm->LANG['TopicTitleRule']);
	}

	$_Poll = ( $fm->user['id'] !== 0 && $allforums[$forum_id]['polls'] === true && $fm->_Boolean($fm->input, 'poll') === true ) ? poll_new() : false;

	$upload = ( $fm->exbb['file_upload'] === true && $allforums[$forum_id]['upload'] !== 0 && ( $fm->user['upload'] === true || $fm->exbb['autoup'] === true && $fm->user['id'] ) ) ? $allforums[$forum_id]['upload'] : 0;

	$attach = false;
	if ($upload !== 0 && !empty( $_FILES['FILE_UPLOAD'] ) && ( $attach = $fm->Upload($allforums[$forum_id]['upload'], uniqid("att-" . $forum_id . "-"), 'uploads/', 'file') ) !== false) {
		if (defined("UP_ERROR")) {
			$fm->_Fclose($fp_allforums);
			$fm->_WriteLog(UP_ERROR);
			$fm->_Message($fm->LANG['TopicCreate'], UP_ERROR);
		}

	}

	$forumname = $allforums[$forum_id]['name'];

	$fm->input['topictitle'] = $fm->bads_filter(mb_substr($fm->input['topictitle'], 0, 255));
	$fm->input['description'] = $fm->bads_filter(mb_substr($fm->input['description'], 0, 160));
	$fm->input['keywords'] = $fm->bads_filter(keywordsProcessor(mb_substr($fm->_String('keywords'), 0, 128)));
	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	$topic_id = ( count($list) !== 0 ) ? max(array_keys($list)) + 1 : 1;

	while (file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$topic_id++;
	}

	if ($_Poll !== false) {
		$fm->_Read2Write($fp_poll, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php');
		$fm->_Write($fp_poll, $_Poll);
	}

	/* Обновляем информацию в allforums.php */
	$allforums[$forum_id]['posts']++;
	$allforums[$forum_id]['topics']++;
	$allforums[$forum_id]['last_post'] = $fm->input['topictitle'];
	$allforums[$forum_id]['last_post_id'] = $topic_id;
	$allforums[$forum_id]['last_key'] = $fm->_Nowtime;
	$allforums[$forum_id]['last_poster'] = ( $fm->user['id'] !== 0 ) ? $fm->user['name'] : false;
	$allforums[$forum_id]['last_poster_id'] = $fm->user['id'];
	$allforums[$forum_id]['last_time'] = $fm->_Nowtime;

	/* Если новая тема создана в подфоруме, то обновим инфу о ластпосте в родительском форуме (на главной) ;) */
	$pcatid = $allforums[$forum_id]['catid'];
	$pforum = ( mb_stristr($pcatid, 'f') ) ? mb_substr($pcatid, 1, mb_strlen($pcatid) - 1) : 0;
	if ($pforum) {
		$allforums[$pforum]['posts']++;
		$allforums[$pforum]['topics']++;
		$allforums[$pforum]['last_post'] = $fm->input['topictitle'];
		$allforums[$pforum]['last_post_id'] = $topic_id;
		$allforums[$pforum]['last_key'] = $fm->_Nowtime;
		$allforums[$pforum]['last_poster'] = $fm->user['name'];
		$allforums[$pforum]['last_poster_id'] = $fm->user['id'];
		$allforums[$pforum]['last_time'] = $fm->_Nowtime;
		$allforums[$pforum]['last_sub'] = $forum_id;
	}
	else {
		unset( $allforums[$forum_id]['last_sub'] );
	}

	$fm->_Write($fp_allforums, $allforums);

	/* Обновляем информацию в соответствующем list.php */
	$list[$topic_id]['name'] = $fm->input['topictitle'];
	$list[$topic_id]['id'] = $topic_id;
	$list[$topic_id]['fid'] = $forum_id;
	$list[$topic_id]['desc'] = $fm->input['description'];
	$list[$topic_id]['state'] = 'open';
	$list[$topic_id]['pinned'] = $fm->_Boolean($fm->input, 'pin');
	$list[$topic_id]['posts'] = 1;
	$list[$topic_id]['author'] = ( $fm->user['id'] !== 0 ) ? $fm->user['name'] : false;
	$list[$topic_id]['a_id'] = $fm->user['id'];
	$list[$topic_id]['date'] = $fm->_Nowtime;
	$list[$topic_id]['poster'] = ( $fm->user['id'] !== 0 ) ? $fm->user['name'] : false;
	$list[$topic_id]['p_id'] = $fm->user['id'];
	$list[$topic_id]['postdate'] = $fm->_Nowtime;
	$list[$topic_id]['postkey'] = $fm->_Nowtime;
	$list[$topic_id]['poll'] = ( $_Poll !== false ) ? true : false;
	uasort($list, 'sort_by_postdate');
	$fm->_Write($fp_list, $list);

	// Создадим элемент в массиве просмотров
	$views = $fm->_Read2Write($fp_views, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/views.php');
	$views[$topic_id] = 0;
	$fm->_Write($fp_views, $views);

	/* Сохраняем новую тему */
	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');

	$topic[$fm->_Nowtime]['p_id'] = $fm->user['id'];
	$topic[$fm->_Nowtime]['post'] = $fm->bads_filter(preg_replace("#(\?|&amp;|;|&)PHPSESSID=([0-9a-zA-Z]){32}#i", "", $fm->input['inpost']));
	$topic[$fm->_Nowtime]['ip'] = $fm->_IP;
	$topic[$fm->_Nowtime]['smiles'] = $fm->_Boolean($fm->input, 'showsmiles');
	$topic[$fm->_Nowtime]['html'] = ( defined('IS_ADMIN') && $fm->_Boolean($fm->input, 'html') === true ) ? true : false;
	$topic[$fm->_Nowtime]['name'] = $fm->input['topictitle'];
	$topic[$fm->_Nowtime]['desc'] = $fm->input['description'];
	$topic[$fm->_Nowtime]['keywords'] = $fm->input['keywords'];
	$topic[$fm->_Nowtime]['state'] = 'open';
	$topic[$fm->_Nowtime]['pinned'] = $list[$topic_id]['pinned'];
	if ($attach !== false) {
		$attach_id = add_attach($attach, $forum_id, $topic_id);
		$topic[$fm->_Nowtime]['attach_id'] = $attach_id;
		$topic[$fm->_Nowtime]['attach_file'] = $attach['NAME'];
	}
	$fm->_Write($fp_topic, $topic);

	/* Обновляем информацию пользователя */
	if ($fm->user['id'] !== 0) {
		/* Топ-лист пользователей */
		include( 'modules/userstop/post.php' );

		$user = $fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $fm->user['id'] . '.php');
		$user['posts']++;
		if ($allforums[$forum_id]['private'] === false) {
			$user['lastpost']['date'] = $fm->_Nowtime;
			$user['lastpost']['link'] = 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id;
			$user['lastpost']['name'] = $fm->input['topictitle'];
		}
		$user['posted'][$forum_id] = ( isset( $user['posted'][$forum_id] ) ) ? $user['posted'][$forum_id] + 1 : 1;
		$fm->_Write($fp_user, $user);

		$allusers = $fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST, false);
		$allusers[$fm->user['id']]['p'] = $user['posts'];
		$fm->_Write($fp_allusers, $allusers);
		unset( $user, $allusers );
	}
	$fm->_SAVE_STATS(array( 'totalthreads' => array( 1, 1 ), 'totalposts' => array( 1, 1 ) ));

	if ($fm->exbb['emailfunctions'] === true && $fm->exbb['mail_posts'] === true) {
		$time = date("d-m-Y H:i:s", $fm->_Nowtime);
		if ($fm->_Boolean($fm->input, 'notify') === true && $fm->user['id'] !== 0) {
			$_t_track = $fm->_Read2Write($fp_t_track, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/_t_track.php');
			$_t_track[$topic_id][$fm->user['id']] = 1;
			$fm->_Write($fp_t_track, $_t_track);
		}
		$email = sprintf($fm->LANG['NewPostThanks'], $fm->user['name'], $fm->exbb['boardurl'] . '/topic.php?forum=' . $forum_id . '&topic=' . $topic_id, $fm->exbb['boardname'], $fm->exbb['boardurl']);
		$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], (!empty($fm->user['mail'])) ? $fm->user['mail'] : '', $fm->LANG['NewTopicInForum'] . '"' . strip_tags($forumname) . '"', $email);

		/* Отправка всем подписавшимся за слежением новых тем */
		$emailers = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/_f_track.php');
		if (isset( $emailers[$fm->user['id']] )) {
			unset( $emailers[$fm->user['id']] );
		}

		if (count($emailers)) {
			$email = sprintf($fm->LANG['NewTopicNotify'], $fm->user['name'], date("d-m-Y H:i:s", $fm->_Nowtime), $fm->input['topictitle'], $fm->input['description'], $fm->exbb['boardurl'] . '/topic.php?forum=' . $forum_id . '&topic=' . $topic_id, $forumname, $fm->exbb['boardname'], $fm->exbb['boardurl'], $forum_id);
			$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $emailers, $fm->LANG['NewTopicInForum'] . '"' . strip_tags($forumname) . '"', $email);
		}
	}
	$fm->_OnlineLog($fm->LANG['TopicCreateInForum'] . ' <a href="topic.php?forum=' . $forum_id . '"><b>' . $forumname . '</b></a>', $privateID);
	include( 'modules/belong/_newTopic.php' );

	$_SESSION['lastposttime'] = $fm->_Nowtime;
	$fm->_Message($fm->LANG['TopicCreate'], $fm->LANG['TopicCreatedOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);

} # end addnewthread

function reply() {
	global $fm;

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);

	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['ReplyCreate'], $fm->LANG['TopicMiss']);
	}

	$topic = $list[$topic_id];
	unset( $list );

	if ($topic['state'] == 'closed' || $topic['state'] == 'moved') {
		$fm->_Message($fm->LANG['ReplyCreate'], $fm->LANG['TopicBlocked']);
	}

	$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);
	$fm->_GetModerators($forum_id, $allforums);
	CheckForumPerms($allforums[$forum_id]['strep'], 'Reply');

	$fm->_String('inpost');

	$fm->_LoadLang('formcode');

	$forumname = $allforums[$forum_id]['name'];
	$topicname = ( isset( $topic['tnun'] ) ) ? $topic['name'] . ' - ' . $topic['tnun'] : $topic['name'];

	$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
	krsort($topic, SORT_NUMERIC);

	/* Вложенные цитаты */
	$inpost = '';
	if ($fm->input['action'] === 'replyquote' && ( $post_id = $fm->_Intval('postid') ) !== 0 && isset( $topic[$post_id] )) {
		$quter_name = GetName($topic[$post_id]['p_id']);
		$fm->input['inpost'] = '[quote=' . $quter_name . ']' . $topic[$post_id]['post'] . '[/quote]';
	}
	/* Вложенные цитаты */

	$forumcodes = ( $fm->exbb['exbbcodes'] === true && $allforums[$forum_id]['codes'] === true ) ? true : false;

	$smilesbutton = ( $fm->exbb['emoticons'] === true ) ? '<input type="checkbox" name="showsmiles" value="yes"' . ( isset( $fm->input['showsmiles'] ) || $fm->_String('preview') === '' ? ' checked> ' : '> ' ) . $fm->LANG['DoSmiles'] . '<br />' : '';

	$upload = ( $fm->exbb['file_upload'] === true && $allforums[$forum_id]['upload'] !== 0 && ( $fm->user['upload'] === true || $fm->exbb['autoup'] === true && $fm->user['id'] ) ) ? $allforums[$forum_id]['upload'] : 0;

	$enctype = ( $upload !== 0 ) ? ' enctype="multipart/form-data"' : '';

	$reged = ( $fm->user['id'] === 0 ) ? ' <a href="register.php">' . $fm->LANG['YouReged'] . '</a> ' : '';

	$notify_checked = ( $fm->_Boolean($fm->input, 'notify') === true ) ? ' checked' : '';
	$emailnotify = ( $fm->user['id'] !== 0 && $fm->exbb['emailfunctions'] ) ? '<input type="checkbox" name="notify" value="yes"' . $notify_checked . '> ' . $fm->LANG['DoEmail'] . '<br>' : '';

	$PreviewData = '';
	if ($fm->_String('preview') !== '') {
		CheckPostSize('inpost');
		$html = ( defined('IS_ADMIN') && $fm->_Boolean($fm->input, 'html') === true ) ? true : false;
		$PreviewText = $fm->bads_filter($fm->formatpost($fm->input['inpost'], $html));
		include( './templates/' . DEF_SKIN . '/preview.tpl' );
	}

	$unread_anchor = $teamcon = $useravatar = $usergraphic = $online = $posts = $joined = $location = '';
	$prf = $eml = $www = $aim = $icq = $pm = $delbox = $karma = $reputation = $pun = $addpun = '';
	$pinmsg = $edit = $del = $reply = $report = $info = $topic_data = $say_thank_b = $say_thank_d = '';

	$icon_divider = ( $fm->exbb['text_menu'] === true ) ? ' : ' : '';
	$icon_postid = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconPostid'] : '<img src="./templates/' . DEF_SKIN . '/im/postid.gif" border="0" title="' . $fm->LANG['ViewPostAddress'] . '" alt="Post Id">';
	$icon_quote = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconQuote'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/quote.gif" border="0" title="' . $fm->LANG['IconQuote'] . '" alt="' . $fm->LANG['IconQuote'] . '">';

	$preview = 10;
	$viewed = 0;
	$users = array();
	$users[0]['n'] = $fm->LANG['Guest'];
	$users[0]['t'] = $fm->LANG['NoReged'];

	foreach ($topic as $key => $postinfo) {
		$viewed++;
		$user_id = isset( $postinfo['p_id'] ) ? $postinfo['p_id'] : 0;
		if (!isset( $users[$user_id] )) {
			$user = $fm->_Getmember($user_id);
			$users[$user_id]['n'] = ( is_array($user) ) ? $user['name'] : $fm->LANG['Guest'];
			$users[$user_id]['t'] = ( is_array($user) ) ? $user['title'] : $fm->LANG['UserDeleted'];
		}

		$postId = '<a href="#" onClick="PostId(this,' . $key . '); return false;" title="' . $fm->LANG['IconPostid'] . '">' . $icon_postid . '</a>';
		$quote = '<a href="#" name="quote" onmouseover="copyQ();" onClick="bbcode(this,\'' . addslashes($users[$user_id]['n']) . '\'); return false;" title="' . $fm->LANG['IconQuote'] . '">' . $icon_quote . '</a>' . $icon_divider;
		$quote2 = '<b><a href="#" name="quote" onmouseover="copyQ();" onClick="bbcode(this,\'' . addslashes($users[$user_id]['n']) . '\'); return false;" title="Для вставки цитаты выделите текст и нажмите сюда">Ответить с цитированием</a></b>' . $icon_divider;

		$username = '<a href="#" name="bold" onClick="bbcode(this,\'' . addslashes($users[$user_id]['n']) . '\'); return false;"><b>' . $users[$user_id]['n'] . '</b></a>';
		$username2 = '<a href="#" name="bold" onClick="bbcode(this,\'' . addslashes($users[$user_id]['n']) . '\'); return false;" title="Нажмите сюда для вставки ника в сообщение">Обратиться по нику</a>';
		$postIP = ( defined('IS_ADMIN') ) ? sprintf($fm->LANG['ViewIpInfo'], $postinfo['ip']) : '&nbsp;';
		$usertitle = $users[$user_id]['t'];
		$postdate = $fm->_DateFormat($key + $fm->user['timedif'] * 3600);

		//$fm->exbb['emoticons'] = ($fm->exbb['emoticons'] === TRUE && $postinfo['smiles'] === TRUE) ? TRUE:FALSE;
		$html = ( defined('IS_ADMIN') && isset( $postinfo['html'] ) ) ? $postinfo['html'] : false;
		if ($forumcodes === true) {
			$post = $fm->formatpost($postinfo['post'], $html);
		}
		$postbackcolor = ( !( $viewed % 2 ) ) ? 'row2' : 'row1';
		include( './templates/' . DEF_SKIN . '/topic_data.tpl' );
		if ($viewed == $preview) {
			break;
		}
	}
	unset( $users, $info, $allmessages );

	$fm->_OnlineLog($fm->LANG['ReplyCreateInTopic'] . ' <a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '"><b>' . $topicname . '</b></a> - <a href="forums.php?forum=' . $forum_id . '"><b>' . $forumname . '</b></a>', $privateID);
	$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/board.js\"></script>
<script type=\"text/javascript\" language=\"JavaScript\">
<!--
var LANG = {
ThisPostWWW: '{$fm->LANG['ThisPostWWW']}',
SpoilerShow: '{$fm->LANG['SpoilerShow']}',
SpoilerHide: '{$fm->LANG['SpoilerHide']}'
};
//-->
</script>";
	$fm->_Title = ' :: ' . $fm->LANG['AddPost'];
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/post_reply.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
} # end add reply routine

function addreply() {
	global $fm;
	check_captcha();
	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php', false);
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['TopicMiss']);
	}

	if ($list[$topic_id]['state'] == 'closed' || $list[$topic_id]['state'] == 'moved') {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['ReplyCreate'], $fm->LANG['TopicBlocked']);
	}

	$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);
	$fm->_GetModerators($forum_id, $allforums);
	CheckForumPerms($allforums[$forum_id]['strep'], 'Reply');

	CheckPostSize('inpost');
	if ($fm->exbb['flood_limit'] != 0 && $fm->_Moderator === false && isset( $_SESSION['lastposttime'] )) {
		$lastpost = $_SESSION['lastposttime'] + $fm->exbb['flood_limit'];
		if (( $_SESSION['lastposttime'] + $fm->exbb['flood_limit'] ) > $fm->_Nowtime) {
			$fm->_FcloseAll();
			$fm->_Message($fm->LANG['ReplyCreate'], sprintf($fm->LANG['FloodLimitNew'], $fm->exbb['flood_limit']));
		}
	}

	$upload = ( $fm->exbb['file_upload'] === true && $allforums[$forum_id]['upload'] !== 0 && ( $fm->user['upload'] === true || $fm->exbb['autoup'] === true && $fm->user['id'] ) ) ? $allforums[$forum_id]['upload'] : 0;

	$attach = false;
	if ($upload !== 0 && !empty( $_FILES['FILE_UPLOAD'] ) && ( $attach = $fm->Upload($allforums[$forum_id]['upload'], uniqid("att-" . $forum_id . "-"), 'uploads/', 'file') ) !== false) {
		if (defined("UP_ERROR")) {
			$fm->_FcloseAll();
			$fm->_WriteLog(UP_ERROR);
			$fm->_Message($fm->LANG['ReplyCreate'], UP_ERROR);
		}

	}

	$forumname = $allforums[$forum_id]['name'];
	$topicname = $list[$topic_id]['name'];

	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
	ksort($topic, SORT_NUMERIC);
	end($topic);
	@$last_key = key($topic);

	#Check double clicking :)
	if ($topic[$last_key]['post'] == $fm->input['inpost']) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['ReplyCreate'], $fm->LANG['ReplySavedAlredy']);
	}

	$fm->input['inpost'] = ( $fm->exbb['wordcensor'] === true ) ? $fm->bads_filter($fm->input['inpost']) : $fm->input['inpost'];
	if ($fm->user['id'] !== 0 && $topic[$last_key]['p_id'] == $fm->user['id'] && ( $fm->_Nowtime - $last_key ) < FM_SUBPOST_TIME && $attach === false) {
		$topic[$last_key]['post'] .= "\n[i]{$fm->LANG['SubAddingPost']}[/i]\n" . preg_replace("#(\?|&amp;|;|&)PHPSESSID=([0-9a-zA-Z]){32}#i", "", $fm->input['inpost']);
		$PostAdded = false;
	}
	else {
		$PostAdded = true;
		$fm->_SAVE_STATS(array( 'totalposts' => array( 1, 1 ) ));

		clearstatcache();
		if ($continueTopic = filesize(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php') >= FM_MAX_THREAD_SIZE) {
			reset($topic);
			$first_key = key($topic);
			end($topic);
			$last_key = key($topic);
			$topic[$first_key]['state'] = 'closed';

			$list[$topic_id]['state'] = 'closed';
			$TopicNumber = ( isset( $list[$topic_id]['tnun'] ) ) ? $list[$topic_id]['tnun'] + 1 : 2;
			$NewTopicPost = sprintf($fm->LANG['TopicContinue'], $fm->exbb['boardurl'], $forum_id, $topic_id, $topicname . ( ( isset( $list[$topic_id]['tnun'] ) ) ? ' - ' . $list[$topic_id]['tnun'] : '' ));

			$newtopic_id = ( count($list) !== 0 ) ? max(array_keys($list)) + 1 : 1;
			$check_file = EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/';
			while (file_exists($check_file . $newtopic_id . '-thd.php')) {
				$newtopic_id++;
			}

			$topic[$last_key]['post'] .= sprintf($fm->LANG['TopicContinueEnd'], $fm->exbb['boardurl'], $forum_id, $newtopic_id, $topicname, $TopicNumber);
			$fm->_Write($fp_topic, $topic);

			$topic_id = $newtopic_id;
			$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id. '/' . $topic_id . '-thd.php');

			$list[$topic_id]['name'] = $topicname;
			$list[$topic_id]['id'] = $topic_id;
			$list[$topic_id]['fid'] = $forum_id;
			$list[$topic_id]['desc'] = $fm->LANG['ContinueDesc'];
			$list[$topic_id]['state'] = 'open';
			$list[$topic_id]['pinned'] = false;
			$list[$topic_id]['posts'] = 0;
			$list[$topic_id]['author'] = GetName(FM_ADMIN_ID);
			$list[$topic_id]['a_id'] = FM_ADMIN_ID;
			$list[$topic_id]['date'] = $fm->_Nowtime;
			$list[$topic_id]['poster'] = '';
			$list[$topic_id]['p_id'] = '';
			$list[$topic_id]['postdate'] = '';
			$list[$topic_id]['postkey'] = '';
			$list[$topic_id]['poll'] = false;
			$list[$topic_id]['tnun'] = $TopicNumber;

			$topic[$fm->_Nowtime]['p_id'] = FM_ADMIN_ID;
			$topic[$fm->_Nowtime]['post'] = $NewTopicPost;
			$topic[$fm->_Nowtime]['ip'] = 'is forum bot';
			$topic[$fm->_Nowtime]['smiles'] = false;
			$topic[$fm->_Nowtime]['html'] = false;
			$topic[$fm->_Nowtime]['name'] = $topicname;
			$topic[$fm->_Nowtime]['desc'] = $fm->LANG['ContinueDesc'];
			$topic[$fm->_Nowtime]['state'] = 'open';
			$topic[$fm->_Nowtime]['pinned'] = false;
			$topic[$fm->_Nowtime]['tnun'] = $TopicNumber;
			$fm->_SAVE_STATS(array( 'totalthreads' => array( 1, 1 ) ));

			include( 'modules/belong/_newTopic.php' );


			$fm->_Nowtime = $fm->_Nowtime + 15;
		}
		$topic[$fm->_Nowtime]['p_id'] = $fm->user['id'];
		$topic[$fm->_Nowtime]['post'] = preg_replace("#(\?|&amp;|;|&)PHPSESSID=([0-9a-zA-Z]){32}#i", "", $fm->input['inpost']);
		$topic[$fm->_Nowtime]['ip'] = $fm->_IP;
		$topic[$fm->_Nowtime]['smiles'] = $fm->_Boolean($fm->input, 'showsmiles');
		$topic[$fm->_Nowtime]['html'] = ( defined('IS_ADMIN') && $fm->_Boolean($fm->input, 'html') === true ) ? true : false;
		if ($attach !== false) {
			$attach_id = add_attach($attach, $forum_id, $topic_id);
			$topic[$fm->_Nowtime]['attach_id'] = $attach_id;
			$topic[$fm->_Nowtime]['attach_file'] = $attach['NAME'];
		}
		unset( $attach );
		$last_key = $fm->_Nowtime;

		// Сохраним информацию о кол-ве просмотров на случай обнуления views.php
		$views = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/views.php');
		if (isset( $views[$topic_id] )) {
			$viewsArrayKeys = array_keys($topic);
			$topic[reset($viewsArrayKeys)]['views'] = $views[$topic_id];
		}
	}

	/* Сохраняем флокнутые файлы*/
	$fm->_Write($fp_topic, $topic);

	$TotalPosts = count($topic) - 1;
	unset( $allmessages );

	if ($PostAdded) {
		$allforums[$forum_id]['posts']++;

		if ($continueTopic) {
			$allforums[$forum_id]['topics']++;
		}
	}

	$allforums[$forum_id]['last_poster'] = $fm->user['name'];
	$allforums[$forum_id]['last_poster_id'] = $fm->user['id'];
	$allforums[$forum_id]['last_post'] = $list[$topic_id]['name'];
	$allforums[$forum_id]['last_post_id'] = $topic_id;
	$allforums[$forum_id]['last_key'] = $last_key;
	$allforums[$forum_id]['last_time'] = $fm->_Nowtime;

	/* Если ответ был в подфорум, то выведем инфу о ластпосте также в родительском форуме на главной */
	$pcatid = $allforums[$forum_id]['catid'];
	$pforum = ( mb_stristr($pcatid, 'f') ) ? mb_substr($pcatid, 1, mb_strlen($pcatid) - 1) : 0;
	if ($pforum) {
		if ($PostAdded) {
			$allforums[$pforum]['posts']++;
		}
		$allforums[$pforum]['last_poster'] = $fm->user['name'];
		$allforums[$pforum]['last_poster_id'] = $fm->user['id'];
		$allforums[$pforum]['last_post'] = $list[$topic_id]['name'];
		$allforums[$pforum]['last_post_id'] = $topic_id;
		$allforums[$pforum]['last_key'] = $last_key;
		$allforums[$pforum]['last_time'] = $fm->_Nowtime;
		$allforums[$pforum]['last_sub'] = $forum_id;
	}
	else {
		unset( $allforums[$forum_id]['last_sub'] );
	}

	$fm->_Write($fp_allforums, $allforums);

	$list[$topic_id]['posts'] = $TotalPosts;
	$list[$topic_id]['poster'] = ( $fm->user['id'] === 0 ) ? false : $fm->user['name'];
	$list[$topic_id]['p_id'] = $fm->user['id'];
	$list[$topic_id]['postkey'] = $last_key;
	$list[$topic_id]['postdate'] = $fm->_Nowtime;

	// Удаление старого ключа views из list.php - пример работы конвертации на лету
	if (isset( $list[$topic_id]['views'] )) {
		unset( $list[$topic_id]['views'] );
	}

	uasort($list, 'sort_by_postdate');
	$fm->_Write($fp_list, $list);

	if ($fm->user['id'] !== 0 && $PostAdded === true) {
		/* Топ-лист пользователей */
		include( 'modules/userstop/post.php' );
		$user = $fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $fm->user['id'] . '.php');
		$user['posts'] += 1 + $continueTopic;
		if ($allforums[$forum_id]['private'] === false) {
			$user['lastpost']['date'] = $fm->_Nowtime;
			$user['lastpost']['link'] = 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id;
			$user['lastpost']['name'] = $topicname;
		}
		$user['posted'][$forum_id] = ( isset( $user['posted'][$forum_id] ) ) ? $user['posted'][$forum_id] + 1 + $continueTopic : 1;
		$fm->_Write($fp_user, $user);
		$allusers = $fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST, false);
		$allusers[$fm->user['id']]['p'] = $user['posts'];
		$fm->_Write($fp_allusers, $allusers);
		unset( $user );
	}

	if ($fm->exbb['emailfunctions'] === true && $fm->exbb['mail_posts'] === true) {
		$_t_track = $fm->_Read2Write($fp_t_track, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/_t_track.php');

		if ($fm->_Boolean($fm->input, 'notify') === true && $fm->user['id'] !== 0) {
			$_t_track[$topic_id][$fm->user['id']] = 1;
			$fm->_Write($fp_t_track, $_t_track);
		}
		else {
			$fm->_Fclose($fp_t_track);
		}

		$emailers = ( isset( $_t_track[$topic_id] ) ) ? $_t_track[$topic_id] : array();
		if (isset( $emailers[$fm->user['id']] )) {
			unset( $emailers[$fm->user['id']] );
		}

		/* Отправка всем подписавшимся на тему */
		if (count($emailers) !== 0 && $PostAdded === true) {
			$email = sprintf($fm->LANG['NewPostNotify'], $forumname, $fm->exbb['boardname'], $fm->user['name'], date("d-m-Y H:i:s", $fm->_Nowtime), $fm->input['inpost'], $fm->exbb['boardurl'] . '/topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&postid=' . $last_key, $fm->exbb['boardurl'], $forum_id, $topic_id);
			$fm->_Mail($fm->exbb['boardname'], $fm->exbb['adminemail'], $emailers, $fm->LANG['NotifyNewPost'] . '"' . strip_tags($forumname) . '"', $email);
			unset( $emailers );
		}
	} # end email send.
	include( 'modules/belong/_addReply.php' );

	$_SESSION['lastposttime'] = $fm->_Nowtime - 10;

	$fm->_Message($fm->LANG['ReplyCreate'], $fm->LANG['ReplyAddedOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&postid=' . $last_key . '#' . $last_key);
}

function poll_new() {
	global $fm;

	if ($fm->_String('pollname') === '') {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['TopicCreate'], $fm->LANG['PollNameEmpty']);
	}

	if ($fm->_String('pollansw') === '') {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['TopicCreate'], $fm->LANG['PollAnswEmpty']);
	}

	$answer_array = array();
	$poll_id = 0;
	$allanswers = explode("\n", $fm->input['pollansw']);

	foreach ($allanswers as $answer) {
		if ($answer === '') {
			continue;
		}
		$answer_array[] = array( $poll_id, $answer, 0 );
		$poll_id++;
	}

	if ($poll_id > $fm->exbb['max_poll'] || $poll_id < 2) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['TopicCreate'], sprintf($fm->LANG['PollError'], $fm->exbb['max_poll']));
	}

	$poll = array();
	$poll['pollname'] = $fm->input['pollname'];
	$poll['started'] = $fm->_Nowtime;
	$poll['start_id'] = $fm->user['id'];
	$poll['choices'] = $answer_array;
	$poll['votes'] = 0;
	$poll['ids'] = array();

	return $poll;
}

function poll_vote() {
	global $fm;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['CorrectPost']);
	}

	if ($fm->user['id'] === 0) {
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['PollNoGuest']);
	}

	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0) {
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['CorrectPost']);
	}

	$threads = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
	if ($threads[reset(array_keys($threads))]['state'] == 'closed') {
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['TopicClosed']);
	}

	if (!file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php')) {
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['PollNotFound']);
	}
	if (!isset( $fm->input['pid'] )) {
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['PollNoPid']);
	}
	$poll_data = $fm->_Read2Write($fp_poll, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php', false);

	if (!isset( $poll_data['choices'][$fm->_Intval('pid')] )) {
		$fm->_Fclose($fp_poll);
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['CorrectPost']);
	}

	if (isset( $poll_data['ids'][$fm->user['id']] )) {
		$fm->_Fclose($fp_poll);
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['PollAlredy']);
	}

	$poll_data['ids'][$fm->user['id']] = true;
	$poll_data['votes']++;
	$poll_data['choices'][$fm->input['pid']][2]++;
	$fm->_Write($fp_poll, $poll_data);
	unset( $poll_data );
	$fm->_Message($fm->LANG['Poll'], $fm->LANG['PollVoteOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&v=l#poll');
}

function check_captcha() {
	global $fm;

	if ($fm->exbb['anti_bot'] && !$fm->user['id'] && ( !isset( $_SESSION['captcha'] ) || $fm->_String('captcha') !== $_SESSION['captcha'] )) {
		$fm->_Message($fm->LANG['Captcha'], $fm->LANG['CaptchaMes']);
	}
}