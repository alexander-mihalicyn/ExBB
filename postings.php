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
$fm->_LoadLang('forums');

if ($fm->user['id'] === 0) {
	$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['GuestNoEdit']);
}
/* Штрафы на форуме */
include( 'modules/punish/p_error.php' );

switch ($fm->input['action']) {
	case 'edit'            :
		editform();
	break;
	case 'processedit'    :
		if ($fm->_Boolean($fm->input, 'deletepost') === true) {
			deletepost();
		}
		else {
			switch ($fm->_String('preview')) {
				case ''    :
					processedit();
				break;
				default    :
					editform();
				break;
			};
		}
	break;
	case 'edittopic'    :
		edit_topic_title();
	break;

	case 'delete'        :
		deletethread();
	break;
	case 'movetopic'    :
		movetopic();
	break;
	case 'unlink'        :
		unlink_topic();
	break;
	case 'restore'        :
		restore();
	break;
	case 'top_recount'    :
		top_recount();
	break;
	case 'addpoll'        :
		addpoll();
	break;
	case 'poll'            :
		poll_edit();
	break;
	case 'lock'            :
	case 'unlock'        :
		un_lockthread($fm->input['action']);
	break;
	case 'pin'            :
	case 'unpin'        :
		un_pinthread($fm->input['action']);
	break;
	case 'pinmsg'        :
		pinmsg();
	break;
	case 'trackers'        :
		del_subscribed();
	break;
	case 'delselected'    :
		delselected();
	break;
	case 'innew'        :
		innew();
	break;
	case 'inexists'        :
		inexists();
	break;
	case 'delattach'    :
		delattach();
	break;
	default                :
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	break;
}
include( 'page_tail.php' );


function editform() {
	global $fm;

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (( $post_id = $fm->_Intval('postid') ) === 0 || ( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);
	$fm->_GetModerators($forum_id, $allforums);

	$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php', false);
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if ($fm->_Moderator === false && $list[$topic_id]['state'] == 'closed') {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['EditClosed']);
	}

	$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php', false);
	if (!isset( $topic[$post_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if ($fm->_Moderator === false && $fm->user['id'] !== $topic[$post_id]['p_id']) {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['YouNotAuthor']);
	}
	if ($fm->_Moderator === false && $fm->exbb['edit_time'] && $fm->_Nowtime - $post_id > $fm->exbb['edit_time'] * 60) {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['EditTime']);
	}

	$lockedit = isset( $topic[$post_id]['lockedit'] ) ? true : false;
	if ($lockedit == true && $fm->_Moderator === false) {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['EditingBlocked']);
	}
	$modertext = isset( $topic[$post_id]['ad_edited'] ) || isset( $topic[$post_id]['mo_edited'] );

	if (isset( $topic[$post_id]['ad_edited'] ) && $lockedit == true && !defined('IS_ADMIN')) {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['EditingAfterAd']);
	}

	$fm->_LoadLang('formcode');

	$forumcodes = ( $fm->exbb['exbbcodes'] === true && $allforums[$forum_id]['codes'] === true ) ? true : false;

	$smilesbutton = ( $fm->exbb['emoticons'] === true ) ? '<input type="checkbox" name="showsmiles" value="yes"' . ( isset( $fm->input['showsmiles'] ) || $fm->_String('preview') === '' ? ' checked> ' : '> ' ) . $fm->LANG['DoSmiles'] . '<br />' : '';

	$upload = ( $fm->exbb['file_upload'] === true && $allforums[$forum_id]['upload'] !== 0 && ( $fm->user['upload'] === true || $fm->exbb['autoup'] === true && $fm->user['id'] ) ) ? $allforums[$forum_id]['upload'] : 0;

	$enctype = ( $upload !== 0 ) ? ' enctype="multipart/form-data"' : '';

	$forumname = $allforums[$forum_id]['name'];

	$topicname = ( isset( $list[$topic_id]['tnun'] ) ) ? $list[$topic_id]['name'] . ' - ' . $list[$topic_id]['tnun'] : $list[$topic_id]['name'];
	unset( $list );

	$fm->_Strings(array( 'inpost' => '', 'mo_edit' => '' ));

	$mo_text = ( isset( $topic[$post_id]['mo_text'] ) ) ? $topic[$post_id]['mo_text'] : '';
	$inpost = $topic[$post_id]['post'];

	$PreviewData = '';
	if ($fm->_String('preview') !== '') {
		CheckPostSize('inpost');
		$lockedit = ( $fm->_Boolean($fm->input, 'lockedit') === true ) ? true : false;
		$modertext = $fm->_Boolean($fm->input, 'modertext');
		$html = ( defined('IS_ADMIN') && $fm->_Boolean($fm->input, 'html') === true ) ? true : false;
		$PreviewText = $fm->bads_filter($fm->formatpost($fm->input['inpost'], $html));
		$inpost = $fm->input['inpost'];
		$mo_text = $fm->input['mo_text'];
		include( './templates/' . DEF_SKIN . '/preview.tpl' );
	}

	$modertext_yes = ( $modertext === true ) ? ' checked="checked"' : '';
	$modertext_no = ( $modertext === false ) ? ' checked="checked"' : '';
	$lockedit_yes = ( $lockedit === true ) ? ' checked' : '';
	$lockedit_no = ( $lockedit === false ) ? ' checked' : '';
	$moderform = ( $fm->_Moderator === true && $topic[$post_id]['p_id'] !== $fm->user['id'] ) ? true : false;
	$attach_options = '';
	if ($upload !== 0 && isset( $topic[$post_id]['attach_id'] )) {
		$attach_options = $fm->LANG['KeepAttach'] . '( ' . $topic[$post_id]['attach_file'] . ' )<br />';
		$attach_options .= $fm->LANG['DelAttach'] . '<br />';
		$attach_options .= $fm->LANG['ReplaceAttach'] . '<br />';
	}

	$fm->_Title = ' :: ' . $fm->LANG['MessageEdit'];
	$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/board.js\"></script>
		<script type=\"text/javascript\" language=\"JavaScript\">
		var LANG = {
		Spoiler: '{$fm->LANG['Spoiler']}',
		SpoilerShow: '{$fm->LANG['SpoilerShow']}',
		SpoilerHide: '{$fm->LANG['SpoilerHide']}'
		};
		</script>";
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/post_edit.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function processedit() {
	global $fm;

	CheckPostSize('inpost');
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (( $post_id = $fm->_Intval('postid') ) === 0 || ( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);
	$fm->_GetModerators($forum_id, $allforums);

	$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php', false);
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if ($fm->_Moderator === false && $list[$topic_id]['state'] == 'closed') {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['EditClosed']);
	}

	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php', false);
	if (!isset( $topic[$post_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if ($fm->_Moderator === false && $fm->user['id'] !== $topic[$post_id]['p_id']) {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['YouNotAuthor']);
	}
	if ($fm->_Moderator === false && $fm->exbb['edit_time'] && $fm->_Nowtime - $post_id > $fm->exbb['edit_time'] * 60) {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['EditTime']);
	}
	$lockedit = isset( $topic[$post_id]['lockedit'] ) ? true : false;
	if ($lockedit == true && $fm->_Moderator === false) {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['EditingBlocked']);
	}

	if (isset( $topic[$post_id]['ad_edited'] ) && $lockedit == true && !defined('IS_ADMIN')) {
		$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['EditingAfterAd']);
	}

	$upload = ( $fm->exbb['file_upload'] === true && $allforums[$forum_id]['upload'] !== 0 && ( $fm->user['upload'] === true || $fm->exbb['autoup'] === true && $fm->user['id'] ) ) ? $allforums[$forum_id]['upload'] : 0;
	$attach = false;
	if ($upload !== 0 && !empty( $_FILES['FILE_UPLOAD'] ) && !preg_match("#^(del|keep)$#is", $fm->_String('editattach')) && ( $attach = $fm->Upload($upload, uniqid("att-" . $forum_id . "-"), 'uploads/', 'file') ) !== false) {
		if (defined("UP_ERROR")) {
			$fm->_WriteLog(UP_ERROR);
			$fm->_Message($fm->LANG['MessageEdit'], UP_ERROR);
		}
	}

	$fm->_String('editattach');
	if ($fm->input['editattach'] === 'del') {
		$attach_id = $topic[$post_id]['attach_id'];
		add_attach(array(), $forum_id, $topic_id, $attach_id, 'del');
		unset( $topic[$post_id]['attach_id'], $topic[$post_id]['attach_file'] );
	}
	elseif ($fm->input['editattach'] === 'rep' && $attach !== false) {
		$attach_id = $topic[$post_id]['attach_id'];
		add_attach($attach, $forum_id, $topic_id, $attach_id, 'rep');
		$topic[$post_id]['attach_file'] = $attach['NAME'];
	}
	elseif ($fm->input['editattach'] === '' && $attach !== false) {
		$attach_id = add_attach($attach, $forum_id, $topic_id);
		$topic[$post_id]['attach_id'] = $attach_id;
		$topic[$post_id]['attach_file'] = $attach['NAME'];
	}

	$fm->_Boolean($fm->input, 'modertext');
	if ($fm->_Moderator === true) {
		$topic[$post_id]['lockedit'] = ( $fm->_Boolean($fm->input, 'lockedit') === true ) ? true : false;
		if ($topic[$post_id]['lockedit'] === false) {
			unset( $topic[$post_id]['lockedit'] );
		}

		if ($topic[$post_id]['p_id'] !== $fm->user['id'] && $fm->input['modertext'] === true) {
			if ($fm->input['mo_text'] !== '') {
				$topic[$post_id]['mo_text'] = $fm->input['mo_text'];
			}

			if (defined('IS_ADMIN')) {
				$topic[$post_id]['ad_editor'] = $fm->user['name'];
				$topic[$post_id]['ad_edited'] = $fm->_Nowtime;

				unset( $topic[$post_id]['mo_editor'], $topic[$post_id]['mo_edited'] );
			}
			else {
				$topic[$post_id]['mo_editor'] = $fm->user['name'];
				$topic[$post_id]['mo_edited'] = $fm->_Nowtime;

				unset( $topic[$post_id]['ad_editor'], $topic[$post_id]['ad_edited'] );
			}
		}
		else {
			unset( $topic[$post_id]['mo_text'], $topic[$post_id]['ad_editor'], $topic[$post_id]['ad_edited'], $topic[$post_id]['mo_editor'], $topic[$post_id]['mo_edited'] );
		}
	}
	else {
		$topic[$post_id]['edited'] = $fm->_Nowtime;
	}

	$topic[$post_id]['post'] = $fm->bads_filter(preg_replace("#(\?|&amp;|;|&)PHPSESSID=([0-9a-zA-Z]){32}#i", "", $fm->input['inpost']));
	$topic[$post_id]['smiles'] = $fm->_Boolean($fm->input, 'showsmiles');
	$topic[$post_id]['html'] = ( defined('IS_ADMIN') && $fm->_Boolean($fm->input, 'html') === true ) ? true : false;
	$fm->_Write($fp_topic, $topic);
	unset( $topic );
	$fm->_Message($fm->LANG['MessageEdit'], $fm->LANG['PostEditedOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&postid=' . $post_id . '#' . $post_id);
}

function deletepost() {
	global $fm, $allforums;

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	if (( $post_id = $fm->_Intval('postid') ) === 0 || ( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);
	$fm->_GetModerators($forum_id, $allforums);
	if ($fm->_Moderator === false) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['PostDeleting'], $fm->LANG['EditNo']);
	}

	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php', false);
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php', false);
	if (!isset( $topic[$post_id] )) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	ksort($topic, SORT_NUMERIC);
	reset($topic);
	$first_key = key($topic);

	/* Удаляем первое сообщение темы? */
	if ($first_key === $post_id) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['PostDeleting'], $fm->LANG['PostDeletingFirst']);
	}

	if (isset( $topic[$post_id]['attach_id'] )) {
		$attach = $fm->_Read2Write($fp_attach, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
		$attach_id = $topic[$post_id]['attach_id'];
		$attach_name = $attach[$attach_id]['id'];
		unset( $attach[$attach_id] );
		$fm->_Write($fp_attach, $attach);

		if (count($attach) === 0) {
			unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
		}

		if (file_exists('uploads/' . $attach_name)) {
			unlink('uploads/' . $attach_name);
		}
	}

	end($topic);
	$LastDeleted = ( $post_id == key($topic) ) ? true : false;

	$poster_id = $topic[$post_id]['p_id'];
	$post_key = array_search($post_id, array_keys($topic));
	$page = ceil($post_key / $fm->user['posts2page']);
	unset( $topic[$post_id] );
	$fm->_Write($fp_topic, $topic);

	if ($LastDeleted === true) {
		end($topic);
		$last_key = key($topic);
		$last_user_name = GetName($topic[$last_key]['p_id']);

		$list[$topic_id]['poster'] = $last_user_name;
		$list[$topic_id]['p_id'] = $topic[$last_key]['p_id'];
		$list[$topic_id]['postdate'] = $last_key;
		$list[$topic_id]['postkey'] = $last_key;
	}
	unset( $topic );

	$list[$topic_id]['posts']--;
	$fm->_Write($fp_list, $list);

	if ($allforums[$forum_id]['last_post_id'] == $topic_id && $LastDeleted === true) {
		uasort($list, "sort_by_postdate");
		reset($list);
		$last_topic = key($list);
		while ($list[$last_topic]['state'] == 'moved') {
			next($list);
			$last_topic = key($list);
		}
		$allforums[$forum_id]['last_poster'] = $list[$last_topic]['poster'];
		$allforums[$forum_id]['last_poster_id'] = $list[$last_topic]['p_id'];
		$allforums[$forum_id]['last_time'] = $list[$last_topic]['postdate'];
		$allforums[$forum_id]['last_key'] = $list[$last_topic]['postkey'];
		$allforums[$forum_id]['last_post'] = ( isset( $list[$last_topic]['tnun'] ) ) ? $list[$last_topic]['name'] . ' - ' . $list[$last_topic]['tnun'] : $list[$last_topic]['name'];
		$allforums[$forum_id]['last_post_id'] = $last_topic;
	}
	$allforums[$forum_id]['posts']--;

	// Обновим ластпост в родительском форуме, если удаляемое сообщение было в нём последним
	$pcatid = $allforums[$forum_id]['catid'];
	if (mb_stristr($pcatid, 'f')) {
		$pforum = mb_substr($pcatid, 1, mb_strlen($pcatid) - 1);
		$allforums[$pforum]['posts']--; // Уменьшаем число ответов в родительском форуме на единицу
		if (@$allforums[$pforum]['last_sub'] == $forum_id && @$allforums[$pforum]['last_post_id'] == $topic_id && @$allforums[$pforum]['last_key'] == $post_id) {
			relast_post($pforum);
		}
	}

	$fm->_Write($fp_allforums, $allforums);

	// Черканём запись в логе об удалении сообщения ;)
	$fm->_WriteLog(sprintf($fm->LANG['DeletePostLog'], $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])), 2);

	unset( $allforums, $list, $last_post );

	/* Обновим количество постов у юзера, пост которого удалили */
	if ($poster_id !== 0 && $fm->_Checkuser($poster_id)) {
		$user = $fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $poster_id . '.php');
		if ($user['posts'] > 0) {
			$user['posts']--;
		}
		if (isset( $user['posted'][$forum_id] )) {
			$user['posted'][$forum_id]--;
		}
		if ($user['posted'][$forum_id] <= 0) {
			unset( $user['posted'][$forum_id] );
		}
		$fm->_Write($fp_user, $user);
		$allusers = $fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST, false);
		$allusers[$poster_id]['p'] = $user['posts'];
		$fm->_Write($fp_allusers, $allusers);
		unset( $user, $allusers );
	}

	$fm->_SAVE_STATS(array( 'totalposts' => array( 1, -1 ) ));
	include( 'modules/belong/_deletePost.php' );
	$fm->_Message($fm->LANG['PostDeleting'], $fm->LANG['PostDeletedOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $page);
}

function edit_topic_title() {
	global $fm;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}


	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['EditTopic'], $fm->LANG['EditNo']);
	}

	if ($fm->_Boolean($fm->input, 'request') === true) {
		if ($fm->input['topictitle'] == '') {
			$fm->_Message($fm->LANG['EditTopic'], $fm->LANG['EmptyTitle']);
		}

		$old_name = $list[$topic_id]['name'];

		$fm->input['topictitle'] = $fm->bads_filter(mb_substr($fm->input['topictitle'], 0, 255));
		$fm->input['description'] = $fm->bads_filter(mb_substr($fm->input['description'], 0, 160));
		$fm->input['keywords'] = $fm->bads_filter(keywordsProcessor(mb_substr($fm->_String('keywords'), 0, 255)));
		// Обновление названия темы в родительском форуме, если тема находится в подфоруме
		$pcatid = $allforums[$forum_id]['catid'];
		if (mb_stristr($pcatid, 'f')) {
			$pforum = mb_substr($pcatid, 1, mb_strlen($pcatid) - 1);
			if ($allforums[$pforum]['last_post_id'] == $topic_id && @$allforums[$pforum]['last_sub'] == $forum_id) {
				$allforums[$pforum]['last_post'] = $fm->input['topictitle'];
			}
		}

		if ($allforums[$forum_id]['last_post_id'] == $topic_id) {
			$allforums[$forum_id]['last_post'] = $fm->input['topictitle'];
			$fm->_Write($fp_allforums, $allforums);
		}
		$list[$topic_id]['name'] = $fm->input['topictitle'];
		$list[$topic_id]['desc'] = $fm->input['description'];
		$fm->_Write($fp_list, $list);
		$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php', false);
		$topic[$list[$topic_id]['date']]['name'] = $fm->input['topictitle'];
		$topic[$list[$topic_id]['date']]['desc'] = $fm->input['description'];
		$topic[$list[$topic_id]['date']]['keywords'] = $fm->input['keywords'];
		$fm->_Write($fp_topic, $topic);

		// Черканём запись в логе об изменении заголовка темы
		if ($old_name != $list[$topic_id]['name']) {
			$fm->_WriteLog(sprintf($fm->LANG['EditTopicLog'], $old_name, $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])), 2);
		}

		$fm->_Message($fm->LANG['EditTopic'], $fm->LANG['EditTopicOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
	}
	else {
		$forumname = $allforums[$forum_id]['name'];
		$topicname = $list[$topic_id]['name'];
		$description = $list[$topic_id]['desc'];
		unset( $list );
		$first = reset($fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php'));
		$keywords = ( isset( $first['keywords'] ) ) ? $first['keywords'] : '';
		$fm->_Title = ' :: ' . $fm->LANG['EditTopic'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/edit_topic_title.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
}

function deletethread() {
	global $fm, $allforums;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['DeleteTopic'], $fm->LANG['EditNo']);
	}

	if ($fm->_Boolean($fm->input, 'request') === true) {
		$fm->_SAVE_STATS(array( 'totalthreads' => array( 1, -1 ), 'totalposts' => array( $list[$topic_id]['posts'], -1 ) ));

		$allforums[$forum_id]['posts'] -= ( $del_posts = $list[$topic_id]['posts'] );
		$allforums[$forum_id]['topics']--;

		/* Удалим топик из list.php */
		$last_time = $list[$topic_id]['postdate'];
		$last_topic = $list[$topic_id]['name'];
		unset( $list[$topic_id] );
		$fm->_Write($fp_list, $list);

		/* Удалим инфу о кол-ве просмотров темы из views.php */
		$views = $fm->_Read2Write($fp_views, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/views.php');
		unset( $views[$topic_id] );
		$fm->_Write($fp_views, $views);

		/* Обновим инфо о постах пользователей */
		$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
		unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');

		$users = array();
		foreach ($topic as $t_id => $post) {
			if ($post['p_id'] !== 0) {
				$users[$post['p_id']] = ( isset( $users[$post['p_id']] ) ) ? $users[$post['p_id']] + 1 : 1;
			}
		}
		unset( $topic );

		if (count($users) !== 0) {
			UpdateAutorsInfoDelete($users, $forum_id);
		}

		/* Проверим был ли удаляемый топик последним в который постили, если да, то переделаем ластпост :) */
		if ($allforums[$forum_id]['last_post_id'] == $topic_id) {
			reset($list);
			$last_id = key($list);
			while (@$list[$last_id]['state'] == 'moved') {
				next($list);
				$last_id = key($list);
			}

			@$allforums[$forum_id]['last_poster'] = $list[$last_id]['poster'];
			@$allforums[$forum_id]['last_poster_id'] = $list[$last_id]['p_id'];
			@$allforums[$forum_id]['last_time'] = $list[$last_id]['postdate'];
			@$allforums[$forum_id]['last_key'] = $list[$last_id]['postkey'];
			@$allforums[$forum_id]['last_post'] = $list[$last_id]['name'];
			@$allforums[$forum_id]['last_post_id'] = $last_id;
		}

		unset( $list );

		/* Обновление ластпоста в родительском форуме при удалении темы в подфоруме */
		$pcatid = $allforums[$forum_id]['catid'];
		$pforum = ( mb_stristr($pcatid, 'f') ) ? mb_substr($pcatid, 1, mb_strlen($pcatid) - 1) : 0;
		if ($pforum) {
			$allforums[$pforum]['topics']--;
			$allforums[$pforum]['posts'] -= $del_posts;
			if (@$allforums[$pforum]['last_time'] == $last_time) {
				relast_post($pforum);
			}
		}

		$fm->_Write($fp_allforums, $allforums);

		$attaches = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
		foreach ($attaches as $id => $attach) {
			if (file_exists('uploads/' . $attach['id'])) {
				unlink('uploads/' . $attach['id']);
			}
		}
		@unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');

		$_t_track = $fm->_Read2Write($fp_t_track, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/_t_track.php');
		if (isset( $_t_track[$topic_id] )) {
			unset( $_t_track[$topic_id] );
		}
		$fm->_Write($fp_t_track, $_t_track);

		if (file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php')) {
			unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php');
		}
		include( 'modules/belong/_deleteTopic.php' );
		// Черканём в логе запись об удалении темы =)
		$fm->_WriteLog(sprintf($fm->LANG['DeleteThreadLog'], $last_topic, strip_tags($allforums[$forum_id]['name'])), 2);

		$fm->_Message($fm->LANG['DeleteTopic'], $fm->LANG['DeleteTopicOk'], 'forums.php?forum=' . $forum_id);
	}
	else {
		$topicname = $list[$topic_id]['name'];
		$hiddinfield = '<input type="hidden" name="action" value="delete">
								<input type="hidden" name="forum" value="' . $forum_id . '">
								<input type="hidden" name="topic" value="' . $topic_id . '">
								<input type="hidden" name="request" value="yes">';
		$formtitle = $fm->LANG['DeleteTopic'];
		$request_text = sprintf($fm->LANG['SuretDelTopic'], $topicname);
		$fm->_Title = ' :: ' . $fm->LANG['DeleteTopic'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/request_form.tpl' );
		echo $RequestForm;
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
}

function movetopic() {
	global $fm, $forum_id, $toforum_id, $allforums;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, 0);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['TopicMoving'], $fm->LANG['EditNo']);
	}

	if ($fm->_Boolean($fm->input, 'request') === true) {
		if (( $toforum_id = $fm->_Intval('toforum') ) === 0 || !isset( $allforums[$toforum_id] )) {
			$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
		}

		if ($toforum_id === $forum_id) {
			$fm->_Message($fm->LANG['TopicMoving'], $fm->LANG['MovingError']);
		}

		/* Get a new thread number. */
		$tolist = $fm->_Read2Write($fp_tolist, EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/list.php');
		$newtopic_id = ( count($tolist) !== 0 ) ? max(array_keys($tolist)) + 1 : 1;
		while (file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $newtopic_id . '-thd.php')) {
			$newtopic_id++;
		}

		$oldtopicfile = EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php';
		$newtopicfile = EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/' . $newtopic_id . '-thd.php';
		copy($oldtopicfile, $newtopicfile);
		@chmod($newtopicfile, $fm->exbb['ch_files']);
		unlink($oldtopicfile);

		/* attaches */
		$oldattachesfile = EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php';
		if (file_exists($oldattachesfile)) {
			$newattachesfile = EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/attaches-' . $newtopic_id . '.php';
			copy($oldattachesfile, $newattachesfile);
			@chmod($newattachesfile, $fm->exbb['ch_files']);
			unlink($oldattachesfile);
		}

		//poll file
		$oldpollfile = EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php';
		if (file_exists($oldpollfile)) {
			$newpollfile = EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/' . $newtopic_id . '-poll.php';
			copy($oldpollfile, $newpollfile);
			@chmod($newpollfile, $fm->exbb['ch_files']);
			unlink($oldpollfile);
		}

		/* topic email trackfile */
		$_t_track = $fm->_Read2Write($fp_t_track, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/_t_track.php');
		if (isset( $_t_track[$topic_id] )) {

			$new_t_track = $fm->_Read2Write($fp_new_t_track, EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/_t_track.php');
			$new_t_track[$newtopic_id] = $_t_track[$topic_id];
			$fm->_Write($fp_new_t_track, $new_t_track);

			unset( $_t_track[$topic_id] );
			$fm->_Write($fp_t_track, $_t_track);
		}

		/* Перенос кол-ва просмотров темы из старого views.php в новый */
		$old_views = $fm->_Read2Write($fp_old_views, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/views.php');
		$views = $fm->_Read2Write($fp_views, EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/views.php');
		$views[$newtopic_id] = $old_views[$topic_id];
		unset( $old_views[$topic_id] );
		$fm->_Write($fp_old_views, $old_views);
		$fm->_Write($fp_views, $views);

		$tolist[$newtopic_id] = $list[$topic_id];
		$tolist[$newtopic_id]['id'] = $newtopic_id;
		$tolist[$newtopic_id]['fid'] = $toforum_id;

		if ($fm->_Boolean($fm->input, 'block') === true) {
			$list[$topic_id]['state'] = 'moved';
			$list[$topic_id]['pinned'] = false;
			$list[$topic_id]['movedid'] = $toforum_id . ':' . $newtopic_id;
		}
		else {
			unset( $list[$topic_id] );
		}

		uasort($list, 'sort_by_postdate');
		uasort($tolist, 'sort_by_postdate');

		$allforums[$forum_id]['posts'] -= $tolist[$newtopic_id]['posts'];
		$allforums[$forum_id]['topics']--;
		$allforums[$toforum_id]['posts'] += $tolist[$newtopic_id]['posts'];
		$allforums[$toforum_id]['topics']++;

		$pcatid = $allforums[$forum_id]['catid'];
		$pforum = ( mb_stristr($pcatid, 'f') ) ? mb_substr($pcatid, 1, mb_strlen($pcatid) - 1) : 0;
		$ptcatid = $allforums[$toforum_id]['catid'];
		$ptforum = ( mb_stristr($ptcatid, 'f') ) ? mb_substr($ptcatid, 1, mb_strlen($ptcatid) - 1) : 0;

		// Если тема при переносе уходит за пределы текущего родительского форума, то из него вычитаются она сама и её ответы :)
		if ($pforum && $forum_id != $ptforum) {
			$allforums[$pforum]['topics']--;
			$allforums[$pforum]['posts'] -= $tolist[$newtopic_id]['posts'];
		}

		// Если тема приходит из-за пределов родительского раздела, то в нём необходимо увеличить кол-во тем на одну и прибавить ответы темы ;)
		if ($ptforum && $toforum_id != $pforum) {
			$allforums[$ptforum]['topics']++;
			$allforums[$ptforum]['posts'] += $tolist[$newtopic_id]['posts'];
		}

		$fm->_Write($fp_list, $list);
		$fm->_Write($fp_tolist, $tolist);

		reset($tolist);
		$first_key = key($tolist);
		while ($tolist[$first_key]['state'] == 'moved') {
			next($tolist);
			$first_key = key($tolist);
		}
		$allforums[$toforum_id]['last_poster'] = $tolist[$first_key]['poster'];
		$allforums[$toforum_id]['last_poster_id'] = $tolist[$first_key]['p_id'];
		$allforums[$toforum_id]['last_time'] = $tolist[$first_key]['postdate'];
		$allforums[$toforum_id]['last_key'] = $tolist[$first_key]['postkey'];
		$allforums[$toforum_id]['last_post'] = $tolist[$first_key]['name'];
		$allforums[$toforum_id]['last_post_id'] = $first_key;

		reset($list);
		$first_key = key($list);
		while (@$list[$first_key]['state'] == 'moved') {
			@next($list);
			$first_key = @key($list);
		}
		@$allforums[$forum_id]['last_poster'] = $list[$first_key]['poster'];
		@$allforums[$forum_id]['last_poster_id'] = $list[$first_key]['p_id'];
		@$allforums[$forum_id]['last_time'] = $list[$first_key]['postdate'];
		@$allforums[$forum_id]['last_key'] = $list[$first_key]['postkey'];
		@$allforums[$forum_id]['last_post'] = $list[$first_key]['name'];
		@$allforums[$forum_id]['last_post_id'] = $first_key;

		// Обновление ластпоста в исходном и новом родительских форумах
		if ($pforum && $pforum != $ptforum && @$tolist[$newtopic_id]['postdate'] == @$allforums[$pforum]['last_time']) {
			relast_post($pforum);
		}
		if ($ptforum && $pforum != $ptforum && @$tolist[$newtopic_id]['postdate'] > @$allforums[$ptforum]['last_time']) {
			$allforums[$ptforum]['last_poster'] = @$tolist[$newtopic_id]['poster'];
			$allforums[$ptforum]['last_poster_id'] = @$tolist[$newtopic_id]['p_id'];
			$allforums[$ptforum]['last_time'] = @$tolist[$newtopic_id]['postdate'];
			$allforums[$ptforum]['last_key'] = @$tolist[$newtopic_id]['postkey'];
			$allforums[$ptforum]['last_post'] = @$tolist[$newtopic_id]['name'];
			$allforums[$ptforum]['last_post_id'] = $newtopic_id;
			$allforums[$ptforum]['last_sub'] = $toforum_id;
		}
		if ($pforum == $ptforum && @$tolist[$newtopic_id]['postdate'] == @$allforums[$pforum]['last_time']) {
			$allforums[$ptforum]['last_post_id'] = $newtopic_id;
			$allforums[$ptforum]['last_sub'] = $toforum_id;
		}

		// Оставим запись в логе о перемещении темы
		$fm->_WriteLog(sprintf($fm->LANG['MoveTopicLog'], $tolist[$newtopic_id]['name'], strip_tags($allforums[$forum_id]['name']), strip_tags($allforums[$toforum_id]['name'])), 2);

		$fm->_Write($fp_allforums, $allforums);
		unset( $tolist, $allforums, $list );

		// Обновим статистику постов у пользователей
		$thread = $fm->_Read($newtopicfile);
		foreach ($thread as $id => $post) {
			if (!isset( $autors[$post['p_id']] )) {
				$autors[$post['p_id']] = 1;
			}
			else {
				$autors[$post['p_id']]++;
			}
		}
		UpdateAutorsInfo($autors);
		include( 'modules/belong/_moveTopic.php' );
		$fm->_Message($fm->LANG['TopicMoving'], $fm->LANG['TopicMovedOk'], 'forums.php?forum=' . $toforum_id);
	}
	else {
		//unset($allforums[$forum_id]);
		$jumphtml = JumpHTML($allforums, 0, $forum_id);
		$fm->_Title = ' :: ' . $fm->LANG['TopicMoving'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/movetopic.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
}

/*	Удаление ссылок на перемещённые темы	*/
function unlink_topic() {
	global $fm;

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, 0);

	$forum_id = $fm->_Intval('forum');
	$topic_id = $fm->_Intval('topic');
	$fm->_Intval('p', 1);

	$fm->_GetModerators($forum_id, $allforums);

	$list = $fm->_Read2Write($fp, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php', 0);

	if ($fm->_Moderator === false || !isset( $list[$topic_id] ) || $list[$topic_id]['state'] != 'moved') {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	// Запишем в лог информацию об удалении ссылки на тему
	$fm->_WriteLog(sprintf($fm->LANG['UnlinkTopicLog'], $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])), 2);

	unset( $list[$topic_id] );
	$fm->_Write($fp, $list);

	$fm->_Message($fm->LANG['UnlinkTopic'], $fm->LANG['TopicUnlinked'], 'forums.php?forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $fm->input['p']);
}

function restore() {
	global $fm;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['TopicRestore'], $fm->LANG['EditNo']);
	}

	$forumname = $allforums[$forum_id]['name'];

	if (!file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
	ksort($topic, SORT_NUMERIC);
	reset($topic);

	$date = key($topic);
	$topicname = $topic[$date]['name'];
	$description = $topic[$date]['desc'];
	$author_id = $topic[$date]['p_id'];
	$author = GetName($author_id);
	$post = $topic[$date]['post'];
	$poll = isset_poll($forum_id, $topic_id);
	$state = $topic[$date]['state'];
	$pinned = $topic[$date]['pinned'];
	$movedid = ( $state == 'moved' ) ? $topic[$date]['movedid'] : false;
	$posts = count($topic) - 1;
	$time = date("d-m-Y H:i:s", $date - $fm->user['timedif'] * 3600);

	if ($fm->_Boolean($fm->input, 'request') === true) {
		if ($fm->_String('topictitle') === '') {
			$fm->_Message($fm->LANG['TopicRestore'], $fm->LANG['EmptyTitle']);
		}

		$topic[$date]['name'] = $fm->input['topictitle'];
		$topic[$date]['desc'] = $fm->input['description'];
		$fm->_Write($fp_topic, $topic);

		end($topic);
		$last_key = key($topic);
		$poster_id = $topic[$last_key]['p_id'];
		unset( $topic );

		$poster = GetName($poster_id);

		$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');

		$list[$topic_id]['name'] = $fm->input['topictitle'];
		$list[$topic_id]['id'] = $topic_id;
		$list[$topic_id]['fid'] = $forum_id;
		$list[$topic_id]['desc'] = $fm->input['description'];
		$list[$topic_id]['state'] = $state;
		$list[$topic_id]['pinned'] = $pinned;
		$list[$topic_id]['posts'] = $posts;
		$list[$topic_id]['author'] = $author;
		$list[$topic_id]['a_id'] = $author_id;
		$list[$topic_id]['date'] = $date;
		$list[$topic_id]['poster'] = $poster;
		$list[$topic_id]['p_id'] = $poster_id;
		$list[$topic_id]['postdate'] = $last_key;
		$list[$topic_id]['postkey'] = $last_key;
		$list[$topic_id]['poll'] = isset_poll($forum_id, $topic_id);
		if ($state == 'moved') {
			$list[$intopic]['movedid'] = $movedid;
		}
		uasort($list, 'sort_by_postdate');
		$fm->_Write($fp_list, $list);

		// Сделаем запись в логе о восстановлении темы
		$fm->_WriteLog(sprintf($fm->LANG['RestoreTopicLog'], $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])), 2);

		$fm->_Message($fm->LANG['TopicRestore'], $fm->LANG['TopicRestoreOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
	}
	else {
		unset( $topic );
		$fm->_Title = ' :: ' . $fm->LANG['TopicRestore'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/logos.tpl' );
		include( './templates/' . DEF_SKIN . '/topic_restore.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
	unset( $allforums, $list );
}

function top_recount() {
	global $fm;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);

	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['TopicRecount'], $fm->LANG['EditNo']);
	}

	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$old_count = $list[$topic_id]['posts'];
	$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php', false);
	$new_count = count($topic) - 1;
	$list[$topic_id]['posts'] = $new_count;
	$fm->_Write($fp_list, $list);

	// Запишем в лог информацию об обновлении статистики темы
	$fm->_WriteLog(sprintf($fm->LANG['RecountTopicLog'], strip_tags($allforums[$forum_id]['name']), $list[$topic_id]['name']), 2);

	unset( $allforums, $list, $topic );

	$fm->_Refresh = 6;
	$fm->_Message($fm->LANG['TopicRecount'], sprintf($fm->LANG['TopicRecountInfo'], $old_count, $new_count), 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
}

/*	Добавление опроса в существующую тему	*/
function addpoll() {
	global $fm;

	if (!$fm->user['id']) {
		$fm->_Message($fm->LANG['AdditionPoll'], $fm->LANG['PollGuest']);
	}

	$forum_id = $fm->_Intval('forum');
	$topic_id = $fm->_Intval('topic');

	$topic_file = EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php';
	$poll_file = EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php';

	if (!file_exists($topic_file)) {
		$fm->_Message($fm->LANG['AdditionPoll'], $fm->LANG['TopicMiss']);
	}

	if (file_exists($poll_file)) {
		$fm->_Message($fm->LANG['AdditionPoll'], $fm->LANG['PollAlreadyExists']);
	}

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, 0);
	$fm->_GetModerators($forum_id, $allforums);

	$threads = $fm->_Read($topic_file, 0);
	$firstkey = reset(array_keys($threads));

	if ($threads[$firstkey]['state'] == 'closed') {
		$fm->_Message($fm->LANG['AdditionPoll'], $fm->LANG['PollTopicClosed']);
	}

	if (( $threads[$firstkey]['p_id'] != $fm->user['id'] ) && ( $fm->_Moderator !== true )) {
		$fm->_Message($fm->LANG['AdditionPoll'], $fm->LANG['PermsAddPoll']);
	}

	$forumname = $allforums[$forum_id]['name'];
	$topicname = $threads[$firstkey]['name'];

	$pollname = $fm->_String('pollname');
	$pollansw = $fm->_String('pollansw');

	$error = array();

	if (!$fm->_POST) {
		$fm->_Title = ' :: ' . sprintf($fm->LANG['AdditionPollTopic'], $topicname);
		include( 'templates/' . DEF_SKIN . '/all_header.tpl' );
		include( 'templates/' . DEF_SKIN . '/logos.tpl' );
		include( 'templates/' . DEF_SKIN . '/addpoll.tpl' );
		include( 'templates/' . DEF_SKIN . '/footer.tpl' );
	}
	else {
		if (!$pollname) {
			$error[] = $fm->LANG['PollNameEmpty'];
		}

		$pollansw = explode("\n", $pollansw);
		if (( !$pollansw[0] ) || ( count($pollansw) > $fm->exbb['max_poll'] )) {
			$error[] = sprintf($fm->LANG['PollError'], $fm->exbb['max_poll']);
		}

		if ($error) {
			$fm->_Title = ' :: ' . sprintf($fm->LANG['AdditionPollTopic'], $topicname);
			include( 'templates/' . DEF_SKIN . '/all_header.tpl' );
			include( 'templates/' . DEF_SKIN . '/logos.tpl' );
			include( 'templates/' . DEF_SKIN . '/addpoll.tpl' );
			include( 'templates/' . DEF_SKIN . '/footer.tpl' );
		}
		else {
			foreach ($pollansw as $offset => $answ) {
				$choices[$offset] = array( $offset, $answ, 0 );
			}

			$fm->_Read2Write($fp_poll, $poll_file, true);
			$poll = array( 'pollname' => $pollname, 'started' => $fm->_Nowtime, 'start_id' => $fm->user['id'], 'choices' => $choices, 'votes' => 0, 'ids' => array() );
			$fm->_Write($fp_poll, $poll);

			$fm->_Message($fm->LANG['AdditionPoll'], $fm->LANG['PollAdded'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
		}
	}
}

function poll_edit() {
	global $fm;

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['Poll'], $fm->LANG['EditNo']);
	}

	$forumname = $allforums[$forum_id]['name'];

	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$topicname = ( isset( $list[$topic_id]['tnun'] ) ) ? $list[$topic_id]['name'] . ' - ' . $list[$topic_id]['tnun'] : $list[$topic_id]['name'];
	$poll_data = $fm->_Read2Write($fp_poll, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php', false);

	if ($fm->_Boolean($fm->input, 'savepoll') === true) {
		if ($fm->_Boolean($fm->input, 'delpoll') === true) {
			if ($fm->_Boolean($fm->input, 'request') === true && $fm->_POST === true) {
				$fm->_Fclose($fp_poll);
				unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php');
				$list[$topic_id]['poll'] = false;
				$fm->_Write($fp_list, $list);

				// Запишем в лог информацию об удалении опроса
				$fm->_WriteLog(sprintf($fm->LANG['DeletePollLog'], $list[$topic_id]['name'], strip_tags($forumname)), 2);

				$fm->_Message($fm->LANG['PollDel'], $fm->LANG['PollDeleteOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
			}
			else {
				$fm->_FcloseAll();
				$hiddinfield = '<input type="hidden" name="action" value="poll">
										<input type="hidden" name="savepoll" value="yes">
										<input type="hidden" name="delpoll" value="yes">
										<input type="hidden" name="forum" value="' . $forum_id . '">
										<input type="hidden" name="topic" value="' . $topic_id . '">
										<input type="hidden" name="request" value="yes">';
				$formtitle = $fm->LANG['PollDeleteRequest'];
				$request_text = sprintf($fm->LANG['SurePollDelete'], $topicname);
				$fm->_Title = ' :: ' . $fm->LANG['PollDeleteRequest'];
				include( './templates/' . DEF_SKIN . '/all_header.tpl' );
				include( './templates/' . DEF_SKIN . '/request_form.tpl' );
				echo $RequestForm;
				include( './templates/' . DEF_SKIN . '/footer.tpl' );
			}
		}
		else {
			if ($fm->_String('pollname') === '') {
				$fm->_FcloseAll();
				$fm->_Message($fm->LANG['PollEdit'], $fm->LANG['PollNameEmpty']);
			}

			if ($fm->_String('pollansw') === '') {
				$fm->_FcloseAll();
				$fm->_Message($fm->LANG['PollEdit'], $fm->LANG['PollAnswEmpty']);
			}

			$answer_array = array();
			$poll_id = 0;
			$allanswers = explode("\n", $fm->input['pollansw']);
			foreach ($allanswers as $answer) {
				if ($answer === '') {
					continue;
				}
				$answer_array[$poll_id] = array( $poll_id, $answer, 0 );
				$poll_id++;
			}

			if ($poll_id > $fm->exbb['max_poll'] || $poll_id < 2) {
				$fm->_FcloseAll();
				$fm->_Message($fm->LANG['TopicCreate'], sprintf($fm->LANG['PollError'], $fm->exbb['max_poll']));
			}

			if (( count($answer_array) !== count($poll_data['choices']) ) || $fm->_Boolean($fm->input, 'respoll') === true) {
				$poll_data['votes'] = 0;
				$poll_data['ids'] = array();
				$poll_data['choices'] = $answer_array;
			}
			else {
				foreach ($answer_array as $poll_id => $answerinfo) {
					$poll_data['choices'][$poll_id][1] = $answerinfo[1];
				}
			}

			$poll_data['pollname'] = $fm->input['pollname'];
			$fm->_Write($fp_poll, $poll_data);

			// Запишем в лог информацию об изменении опроса
			$fm->_WriteLog(sprintf($fm->LANG['EditPollLog'], $list[$topic_id]['name'], strip_tags($forumname)), 2);

			$fm->_FcloseAll();
			$fm->_Message($fm->LANG['PollEdit'], $fm->LANG['PollEditOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
		}
	}
	else {
		$fm->_FcloseAll();
		$pollname = $poll_data['pollname'];
		$pollansw = '';
		foreach ($poll_data['choices'] as $choice) {
			$pollansw .= $choice[1] . "\n";
		}
		unset( $list, $poll_data );
		$fm->_Title = ' :: ' . $fm->LANG['PollEdit'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/poll_edit.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}

	return true;
}

function un_lockthread($mode) {
	global $fm;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['TopicClosing'], $fm->LANG['EditNo']);
	}

	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$list[$topic_id]['state'] = ( $mode == 'lock' ) ? 'closed' : 'open';
	$fm->_Write($fp_list, $list);

	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
	ksort($topic, SORT_NUMERIC);
	reset($topic);
	$firstkey = key($topic);
	$topic[$firstkey]['state'] = $list[$topic_id]['state'];
	$fm->_Write($fp_topic, $topic);

	// Черканём запись в логе о закрытии / открытии темы
	$fm->_WriteLog(sprintf($mode == 'lock' ? $fm->LANG['CloseTopicLog'] : $fm->LANG['OpenTopicLog'], $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])), 2);

	unset( $allforums, $topic );

	$OkMessage = ( $mode == 'lock' ) ? $fm->LANG['TopicClosedOk'] : $fm->LANG['TopicOpenOk'];
	$fm->_Message($fm->LANG['TopicClosing'], $OkMessage, 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
}

function un_pinthread($mode) {
	global $fm;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['TopicPinning'], $fm->LANG['EditNo']);
	}

	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
	$topic[$list[$topic_id]['date']]['pinned'] = ( $mode == 'pin' ) ? true : false;
	$fm->_Write($fp_topic, $topic);

	$list[$topic_id]['pinned'] = ( $mode == 'pin' ) ? true : false;
	$fm->_Write($fp_list, $list);

	// Запишем в лог сообщение о прикреплении / откреплении темы
	$fm->_WriteLog(sprintf($mode == 'pin' ? $fm->LANG['PinTopicLog'] : $fm->LANG['UnpinTopicLog'], $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])), 2);

	unset( $allforums, $list );

	$OkMessage = ( $mode == 'pin' ) ? $fm->LANG['TopicPinnedOk'] : $fm->LANG['TopicUnPinnedOk'];
	$fm->_Message($fm->LANG['TopicPinning'], $OkMessage, 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
}

/*	Прикрепление сообщений	*/
/*	http://www.exbb.org/	*/
function pinmsg() {
	global $fm;

	$fm->_Intvals(array( 'forum', 'topic', 'post' ));

	// Проверка прав доступа к этой функции
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	$fm->_GetModerators($fm->input['forum'], $allforums);
	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['MsgPin'], $fm->LANG['EditNo']);
	}

	// Проверка на существование сообщения
	if (!file_exists(EXBB_DATA_DIR_FORUMS . '/' . $fm->input['forum'] . '/' . $fm->input['topic'] . '-thd.php')) {
		$fm->_Message($fm->LANG['TopicOpen'], $fm->LANG['TopicMiss']);
	}
	$threads = $fm->_Read2Write($fp_threads, EXBB_DATA_DIR_FORUMS . '/' . $fm->input['forum'] . '/' . $fm->input['topic'] . '-thd.php');
	if (!isset( $threads[$fm->input['post']] )) {
		$fm->_Message($fm->LANG['TopicOpen'], $fm->LANG['MissMsg']);
	}

	// Найдём ключ первого сообщения и получим массив ключей прикреплённых сообщений
	$firstkey = min(array_keys($threads));
	if (!isset( $threads[$firstkey]['pinmsg'] )) {
		$threads[$firstkey]['pinmsg'] = array();
	}

	// Добавление / удаление ключа сообщения в массиве прикреплённых сообщений
	if (!in_array($fm->input['post'], $threads[$firstkey]['pinmsg'])) {
		$threads[$firstkey]['pinmsg'][] = $fm->input['post'];
		$msg = $fm->LANG['MsgPinned'];
	}
	else {
		if (( $found = array_search($fm->input['post'], $threads[$firstkey]['pinmsg']) ) !== false) {
			unset( $threads[$firstkey]['pinmsg'][$found] );
		}
		$msg = $fm->LANG['MsgUnpinned'];
	}

	// Удаление ключей удалённых / перемещённых ранее сообщений
	foreach ($threads[$firstkey]['pinmsg'] as $offset => $key) {
		if (!isset( $threads[$key] )) {
			unset( $threads[$firstkey]['pinmsg'][$offset] );
		}
	}

	// Упорядочение массива ключей прикреплённых сообщений
	if (empty( $threads[$firstkey]['pinmsg'] )) {
		unset( $threads[$firstkey]['pinmsg'] );
	}
	else {
		sort($threads[$firstkey]['pinmsg']);
	}

	// Сохраняем файл темы
	$fm->_Write($fp_threads, $threads);

	// Запись в лог инфы о прикреплении / откреплении сообщения
	$fm->_WriteLog(sprintf(( $msg == $fm->LANG['MsgPinned'] ) ? $fm->LANG['PinMsgLog'] : $fm->LANG['UnpinMsgLog'], $threads[$firstkey]['name'], strip_tags($allforums[$fm->input['forum']]['name'])), 2);

	// Всё! ;)
	$fm->_Message($fm->LANG['MsgPin'], $msg, 'topic.php?forum=' . $fm->input['forum'] . '&topic=' . $fm->input['topic'] . '&postid=' . $fm->input['post'] . '#' . $fm->input['post']);
}

function del_subscribed() {
	global $fm;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['EditNo']);
	}

	$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$_t_track = $fm->_Read2Write($fp_t_track, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/_t_track.php');
	if (isset( $_t_track[$topic_id] )) {
		unset( $_t_track[$topic_id] );
	}
	$fm->_Write($fp_t_track, $_t_track);

	// Черканём запись в логе об отмене подписки пользователей на тему
	$fm->_WriteLog(sprintf($fm->LANG['UnsubscribeLog'], $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])), 2);

	unset( $allforums );

	$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['Unsubscribed'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
}

function delselected() {
	global $fm, $allforums;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if (( $postkey = $fm->_String('postkey') ) === '' || ( $postkey = @unserialize($postkey) ) === false || count($postkey) === 0) {
		$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['NotSelectedPosts']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id. '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['EditNo']);
	}

	$countremoved = 0;

	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
	ksort($topic, SORT_NUMERIC);
	reset($topic);
	$firstkey = key($topic);
	$autors = array();
	$attaches = array();
	$users = array();
	foreach ($postkey as $post_id) {
		if (isset( $topic[$post_id] ) && $firstkey !== $post_id) {
			if ($topic[$post_id]['p_id'] !== 0) {
				$autor_id = $topic[$post_id]['p_id'];
				$autors[$autor_id] = ( isset( $autors[$autor_id] ) ) ? $autors[$autor_id] + 1 : 1;
				$users[$autor_id][] = $post_id;
			}
			if (isset( $topic[$post_id]['attach_id'] )) {
				$attaches[] = $topic[$post_id]['attach_id'];
			}
			unset( $topic[$post_id] );
			$countremoved++;
		}
	}

	ksort($topic, SORT_NUMERIC);
	$fm->_Write($fp_topic, $topic);

	end($topic);
	$last_key = key($topic);

	if ($last_key != $list[$topic_id]['postkey']) {
		$poster = GetName($topic[$last_key]['p_id']);
		$list[$topic_id]['postkey'] = $last_key;
		$list[$topic_id]['postdate'] = $last_key;
		$list[$topic_id]['poster'] = $poster;
		$list[$topic_id]['p_id'] = $topic[$last_key]['p_id'];
		unset( $last_poster );
	}
	$list[$topic_id]['posts'] -= $countremoved;
	uasort($list, "sort_by_postdate");
	$fm->_Write($fp_list, $list);

	reset($list);
	$lasttopic = key($list);
	while ($list[$lasttopic]['state'] == 'moved') {
		next($list);
		$lasttopic = key($list);
	}
	$allforums[$forum_id]['last_time'] = $list[$lasttopic]['postdate'];
	$allforums[$forum_id]['last_key'] = $list[$lasttopic]['postkey'];
	$allforums[$forum_id]['last_poster'] = $list[$lasttopic]['poster'];
	$allforums[$forum_id]['last_poster_id'] = $list[$lasttopic]['p_id'];
	$allforums[$forum_id]['last_post'] = $list[$lasttopic]['name'];
	$allforums[$forum_id]['last_post_id'] = $lasttopic;
	$allforums[$forum_id]['posts'] -= $countremoved;

	// Если тема с удаляемыми сообщениями находится в подфоруме и в неё постили последней, то обновим ластпост родительского форума
	$pcatid = $allforums[$forum_id]['catid'];
	if (mb_stristr($pcatid, 'f')) {
		$pforum = mb_substr($pcatid, 1, mb_strlen($pcatid) - 1);
		if ($allforums[$pforum]['last_post_id'] == $topic_id && @$allforums[$pforum]['last_sub'] == $forum_id) {
			$allforums[$pforum]['posts'] -= $countremoved; // Вычтим удалённые сообщения из родительского форума
			relast_post($pforum);
		}
	}

	// Запишем в лог информацию об удалении сообщений
	$fm->_WriteLog($countremoved == 1 ? sprintf($fm->LANG['DeletePostLog'], $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])) : sprintf($fm->LANG['DeletePostsLog'], $countremoved, $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name'])), 2);

	$fm->_Write($fp_allforums, $allforums);
	unset( $allforums, $topic, $list );

	$fm->_SAVE_STATS(array( 'totalposts' => array( $countremoved, -1 ) ));

	//Check posts attaches
	if (count($attaches) > 0) {
		$allattaches = $fm->_Read2Write($fp_attach, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
		foreach ($attaches as $attach_id) {
			if (isset( $allattaches[$attach_id] )) {
				if (file_exists('uploads/' . $allattaches[$attach_id]['id'])) {
					unlink('uploads/' . $allattaches[$attach_id]['id']);
				}
				unset( $allattaches[$attach_id] );
			}
		}
		$fm->_Write($fp_attach, $allattaches);
		if (count($allattaches) === 0) {
			unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
		}
	}

	if (count($autors) !== 0) {
		UpdateAutorsInfoDelete($autors, $forum_id);
	}
	include( 'modules/belong/_deletePosts.php' );
	$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['SelectedDeleteOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
}

function innew() {
	global $fm, $allforums;
	global $forum_id, $topic_id, $toforum_id, $newtopic_id;
	global $list, $autors, $users, $attaches, $movingFlag, $countmoving;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if (( $postkey = $fm->_String('postkey') ) === '' || ( $postkey = @unserialize($postkey) ) === false || count($postkey) === 0) {
		$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['NotSelectedPosts']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	if (in_array($list[$topic_id]['date'], $postkey)) {
		$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['PostDeletingFirst']);
	}

	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['EditNo']);
	}


	if ($fm->_Boolean($fm->input, 'moving') === true) {
		if (( $toforum_id = $fm->_Intval('toforum') ) === 0 || !isset( $allforums[$toforum_id] )) {
			$fm->_Message($fm->LANG['MoveSelectedInNew'], $fm->LANG['ForumNotSelected']);
		}

		if ($fm->_String('topictitle') === '') {
			$fm->_Message($fm->LANG['MoveSelectedInNew'], $fm->LANG['PostEmpty']);
		}
		$movingFlag = ( $toforum_id !== $forum_id ) ? true : false;

		$newlist = array();
		if ($movingFlag === true) {
			$newlist = $fm->_Read2Write($fp_newlist, EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/list.php');
		}
		$newtopic_id = ( $movingFlag === true ) ? @max(array_keys($newlist)) + 1 : @max(array_keys($list)) + 1;
		while (file_exists(EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/' . $newtopic_id . '-thd.php')) {
			$newtopic_id++;
		}

		/* УДАЛЯЕМ ПОСТЫ ИЗ СТАРОЙ ТЕМЫ, СОЗДАЕМ МАССИВ НОВОЙ ТЕМЫ И СОХРАНЯЕМ ФАЙЛ СТАРОЙ ТЕМЫ */
		$newtopic = UpdateOldTopicFile($topic, $postkey, "innew");

		/* ОБНОВИМ ИНФОРМАЦИЮ В LIST.PHP СТАРОГО РАЗДЕЛА */
		UpdateOldList($topic, $list);

		/* С АТТАЧАМИ ЕСЛИ КОЛ-ВО АТТАЧЕЙ БОЛЬШЕ 0 */
		if (count($attaches) !== 0) {
			$_attaches = UpdateAttaches($newtopic, $attaches);
		}
		foreach ($_attaches as $post_id => $attach_id) {
			$newtopic[$post_id]['attach_id'] = $attach_id;
		}

		/* СОЗДАЕМ ВРЕМЕННЫЙ МАССИВ НОВОЙ ТЕМЫ И СОЗДАЕМ ФАЙЛ НОВОЙ ТЕМЫ */
		ksort($newtopic, SORT_NUMERIC);

		reset($newtopic);
		$newfirstkey = key($newtopic);
		$newautor = GetName($newtopic[$newfirstkey]['p_id']);

		end($newtopic);
		$newlastkey = key($newtopic);
		$newlastposter = GetName($newtopic[$newlastkey]['p_id']);

		$newtopic[$newfirstkey]['name'] = $fm->input['topictitle'];
		$newtopic[$newfirstkey]['state'] = 'open';
		$newtopic[$newfirstkey]['pinned'] = false;
		$newtopic[$newfirstkey]['desc'] = $fm->input['description'];

		$fm->_Read2Write($fp_newtopic, EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/' . $newtopic_id . '-thd.php');
		$fm->_Write($fp_newtopic, $newtopic); //Закрыли и сохранили файл новой темы

		$tpm_list['name'] = $fm->input['topictitle'];
		$tpm_list['id'] = $newtopic_id;
		$tpm_list['fid'] = $toforum_id;
		$tpm_list['desc'] = $fm->input['description'];
		$tpm_list['state'] = 'open';
		$tpm_list['pinned'] = false;
		$tpm_list['posts'] = $countmoving - 1;
		$tpm_list['author'] = $newautor;
		$tpm_list['a_id'] = $newtopic[$newfirstkey]['p_id'];
		$tpm_list['date'] = $newfirstkey;
		$tpm_list['poster'] = $newlastposter;
		$tpm_list['p_id'] = $newtopic[$newlastkey]['p_id'];
		$tpm_list['postdate'] = $newlastkey;
		$tpm_list['postkey'] = $newlastkey;
		$tpm_list['poll'] = false;

		/* ДОБАВЛЕМЯ ИНФОРМАЦИЮ В LIST.PHP И ALLFORUMS.PHP */
		if ($movingFlag === true) {
			/* ОБНОВИМ ИНФОРМАЦИЮ О ПОСТАХ АВТОРОВ СООБЩЕНИЙ */
			UpdateAutorsInfo($autors);

			$newlist[$newtopic_id] = $tpm_list;
			uasort($newlist, "sort_by_postdate");
			$fm->_Write($fp_newlist, $newlist);

			reset($newlist);
			$newlasttopic = key($newlist);
			while ($newlist[$newlasttopic]['state'] == 'moved') {
				next($newlist);
				$newlasttopic = key($newlist);
			}

			$allforums[$toforum_id]['last_poster'] = $newlist[$newlasttopic]['poster'];
			$allforums[$toforum_id]['last_poster_id'] = $newlist[$newlasttopic]['p_id'];
			$allforums[$toforum_id]['last_time'] = $newlist[$newlasttopic]['postdate'];
			$allforums[$toforum_id]['last_key'] = $newlist[$newlasttopic]['postkey'];
			$allforums[$toforum_id]['last_post'] = $newlist[$newlasttopic]['name'];
			$allforums[$toforum_id]['last_post_id'] = $newlasttopic;
			$allforums[$toforum_id]['posts'] += $countmoving - 1;
			$allforums[$toforum_id]['topics'] = count($newlist);
		}
		else {
			$list[$newtopic_id] = $tpm_list;
		}

		unset( $tpm_list, $newlist );
		uasort($list, "sort_by_postdate");
		$fm->_Write($fp_list, $list);

		reset($list);
		$oldlasttopic = key($list);
		while ($list[$oldlasttopic]['state'] == 'moved') {
			next($list);
			$oldlasttopic = key($list);
		}

		$allforums[$forum_id]['last_poster'] = $list[$oldlasttopic]['poster'];
		$allforums[$forum_id]['last_poster_id'] = $list[$oldlasttopic]['p_id'];
		$allforums[$forum_id]['last_time'] = $list[$oldlasttopic]['postdate'];
		$allforums[$forum_id]['last_key'] = $list[$oldlasttopic]['postkey'];
		$allforums[$forum_id]['last_post'] = $list[$oldlasttopic]['name'];
		$allforums[$forum_id]['last_post_id'] = $oldlasttopic;
		$allforums[$forum_id]['posts'] = ( $movingFlag === true ) ? $allforums[$forum_id]['posts'] - $countmoving : $allforums[$forum_id]['posts'] - 1;
		$allforums[$forum_id]['topics'] = count($list);

		// Если посты переносятся в тему, находящуюся в подфоруме, то обновляем ластпост родительского форума
		// Также в этом форуме увеличим число тем на 1 и число ответов на кол-во перемещаемых сообщений минус 1
		$pcatid = $allforums[$toforum_id]['catid'];
		if (mb_stristr($pcatid, 'f')) {
			$pforum = mb_substr($pcatid, 1, mb_strlen($pcatid) - 1);
			$allforums[$pforum]['topics']++;
			$allforums[$pforum]['posts'] += $countmoving - 1;
			relast_post($pforum);
		}

		// Если посты перенесены из темы, находящейся в подфоруме, то обновим ластпост родительского форума
		// Также вычтем из числа ответов этого форума кол-во перемещённых сообщений
		$pcatid = $allforums[$forum_id]['catid'];
		if (mb_stristr($pcatid, 'f')) {
			$pforum = mb_substr($pcatid, 1, mb_strlen($pcatid) - 1);
			$allforums[$pforum]['posts'] -= $countmoving;
			relast_post($pforum);
		}

		$fm->_Write($fp_allforums, $allforums);

		$fm->_SAVE_STATS(array( 'totalposts' => array( 1, -1 ), 'totalthreads' => array( 1, 1 ) ));
		include( 'modules/belong/_inNew.php' );
		// Оставим запись в логе о перемещении сообщений в новую тему
		$fm->_WriteLog(sprintf($fm->LANG['InNewLog'], $countmoving, $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name']), $fm->input['topictitle'], strip_tags($allforums[$toforum_id]['name'])), 2);

		$fm->_Message($fm->LANG['MoveSelectedInNew'], $fm->LANG['SelectInNewOk'], 'topic.php?forum=' . $toforum_id . '&topic=' . $newtopic_id);
	}
	else {

		$postkey = serialize($postkey);
		$jumphtml = JumpHTML($allforums);
		$selectdesc = $fm->LANG['SelectIn'];
		$bodytitle = $fm->LANG['MoveSelectedInNew'];
		$hidden = '';

		$fm->_Title = ' :: ' . $fm->LANG['MoveSelectedInNew'];
		include( './templates/' . DEF_SKIN . '/moveposts_data.tpl' );
		$row = $row_innew;
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/moveposts.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}

}

function inexists() {
	global $fm, $allforums;
	global $forum_id, $topic_id, $toforum_id, $newtopic_id;
	global $list, $autors, $users, $attaches, $movingFlag, $countmoving;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if (( $postkey = $fm->_String('postkey') ) === '' || ( $postkey = @unserialize($postkey) ) === false || count($postkey) === 0) {
		$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['NotSelectedPosts']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	if (in_array($list[$topic_id]['date'], $postkey)) {
		$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['PostDeletingFirst']);
	}

	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['DeletSelectedPosts'], $fm->LANG['EditNo']);
	}

	if ($fm->_Boolean($fm->input, 'topicselected') === true && $fm->_Boolean($fm->input, 'moving') === true) {
		if (( $toforum_id = $fm->_Intval('toforum') ) === 0 || !isset( $allforums[$toforum_id] )) {
			$fm->_Message($fm->LANG['MoveSelectedInExists'], $fm->LANG['ForumNotSelected']);
		}

		if (( $newtopic_id = $fm->_Intval('newtopic') ) === 0) {
			$fm->_Message($fm->LANG['MoveSelectedInExists'], $fm->LANG['TopicNotSelected']);
		}

		if ($toforum_id == $forum_id && $topic_id == $newtopic_id) {
			$fm->_Message($fm->LANG['MoveSelectedInExists'], $fm->LANG['TopicSelfSelected']);
		}

		$newlist = array();
		$movingFlag = ( $toforum_id !== $forum_id ) ? true : false;
		if ($movingFlag === true) {
			$newlist = $fm->_Read2Write($fp_newlist, EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/list.php');
			$newtopicname = $newlist[$newtopic_id]['name'];
		}

		$newtopic_idExists = ( $movingFlag === true ) ? isset( $newlist[$newtopic_id] ) : isset( $list[$newtopic_id] );
		if ($newtopic_idExists === false) {
			$fm->_Message($fm->LANG['MoveSelectedInExists'], $fm->LANG['TopicNotSelected']);
		}
		unset( $newtopic_idExists );

		$newtopic_idTitle = ( $movingFlag === true ) ? $newlist[$newtopic_id]['name'] : $list[$topic_id]['name'];

		/* УДАЛЯЕМ ПОСТЫ ИЗ СТАРОЙ ТЕМЫ, СОЗДАЕМ МАССИВ НОВОЙ ТЕМЫ И СОХРАНЯЕМ ФАЙЛ СТАРОЙ ТЕМЫ*/
		$tmp_newtopic = UpdateOldTopicFile($topic, $postkey, "inexists");
		ksort($tmp_newtopic, SORT_NUMERIC);

		/* ОБНОВИМ ИНФОРМАЦИЮ В LIST.PHP СТАРОГО РАЗДЕЛА */
		UpdateOldList($topic, $list);

		/* РАЗБИРАЕМСЯ С АТТАЧАМИ ЕСЛИ КОЛ-ВО АТТАЧЕЙ БОЛЬШЕ 0 */
		if (count($attaches) !== 0) {
			UpdateAttaches($tmp_newtopic, $attaches);
		}

		$newtopic = $fm->_Read2Write($fp_newtopic, EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/' . $newtopic_id . '-thd.php');
		$oldfirstkey = key($newtopic);

		$newUsers = array();
		foreach ($tmp_newtopic as $post_id => $postinfo) {
			while (isset( $newtopic[$post_id] )) {
				$post_id++;
			}

			$newtopic[$post_id] = $postinfo;
			$newUsers[$postinfo['p_id']][] = $post_id;
		}
		unset( $tmp_newtopic );
		ksort($newtopic);
		$newfirstkey = key($newtopic);
		if ($oldfirstkey != $newfirstkey) {
			$newtopic[$newfirstkey] += array( 'name' => $newtopic[$oldfirstkey]['name'], 'desc' => $newtopic[$oldfirstkey]['desc'], 'state' => $newtopic[$oldfirstkey]['state'], 'pinned' => $newtopic[$oldfirstkey]['pinned'] );

			unset( $newtopic[$oldfirstkey]['name'], $newtopic[$oldfirstkey]['desc'], $newtopic[$oldfirstkey]['state'], $newtopic[$oldfirstkey]['pinned'] );

			if (isset( $newtopic[$oldfirstkey]['views'] )) {
				$newtopic[$newfirstkey]['views'] = $newtopic[$oldfirstkey]['views'];

				unset( $newtopic[$oldfirstkey]['views'] );
			}
			if (isset( $newtopic[$oldfirstkey]['pinmsg'] )) {
				$newtopic[$newfirstkey]['pinmsg'] = $newtopic[$oldfirstkey]['pinmsg'];

				unset( $newtopic[$oldfirstkey]['pinmsg'] );
			}
		}
		end($newtopic);
		$newlastkey = key($newtopic);
		$fm->_Write($fp_newtopic, $newtopic);
		include( 'modules/belong/_inExists.php' );
		$newauthor = GetName($newtopic[$newfirstkey]['p_id']);
		$newlastposter = GetName($newtopic[$newlastkey]['p_id']);

		/* START ДОБАВЛЕМЯ ИНФОРМАЦИЮ В LIST.PHP И ALLFORUMS.PHP */
		if ($movingFlag === true) {
			/* START ОБНОВИМ ИНФОРМАЦИЮ О ПОСТАХ АВТОРОВ СООБЩЕНИЙ */
			UpdateAutorsInfo($autors);
			$newlist[$newtopic_id]['author'] = $newauthor;
			$newlist[$newtopic_id]['a_id'] = $newtopic[$newfirstkey]['p_id'];
			$newlist[$newtopic_id]['date'] = $newfirstkey;
			$newlist[$newtopic_id]['postkey'] = $newlastkey;
			$newlist[$newtopic_id]['postdate'] = $newlastkey;
			$newlist[$newtopic_id]['poster'] = $newlastposter;
			$newlist[$newtopic_id]['p_id'] = $newtopic[$newlastkey]['p_id'];
			$newlist[$newtopic_id]['posts'] += $countmoving;
			uasort($newlist, "sort_by_postdate");
			$fm->_Write($fp_newlist, $newlist);

			reset($newlist);
			$newlasttopic = key($newlist);
			while ($newlist[$newlasttopic]['state'] == 'moved') {
				next($newlist);
				$newlasttopic = key($newlist);
			}

			$allforums[$toforum_id]['last_poster'] = $newlist[$newlasttopic]['poster'];
			$allforums[$toforum_id]['last_poster_id'] = $newlist[$newlasttopic]['p_id'];
			$allforums[$toforum_id]['last_time'] = $newlist[$newlasttopic]['postdate'];
			$allforums[$toforum_id]['last_key'] = $newlist[$newlasttopic]['postkey'];
			$allforums[$toforum_id]['last_post'] = $newlist[$newlasttopic]['name'];
			$allforums[$toforum_id]['last_post_id'] = $newlasttopic;
			$allforums[$toforum_id]['posts'] += $countmoving;
			$allforums[$forum_id]['posts'] -= $countmoving;
			$topicname = ( isset( $newlist[$newtopic_id]['tnun'] ) ) ? $newlist[$newtopic_id]['name'] . ' - ' . $newlist[$newtopic_id]['tnun'] : $newlist[$newtopic_id]['name'];
		}
		else {
			$list[$newtopic_id]['author'] = $newauthor;
			$list[$newtopic_id]['a_id'] = $newtopic[$newfirstkey]['p_id'];
			$list[$newtopic_id]['date'] = $newfirstkey;
			$list[$newtopic_id]['postkey'] = $newlastkey;
			$list[$newtopic_id]['postdate'] = $newlastkey;
			$list[$newtopic_id]['poster'] = $newlastposter;
			$list[$newtopic_id]['p_id'] = $newtopic[$newlastkey]['p_id'];
			$list[$newtopic_id]['posts'] += $countmoving;
			$topicname = ( isset( $list[$newtopic_id]['tnun'] ) ) ? $list[$newtopic_id]['name'] . ' - ' . $list[$newtopic_id]['tnun'] : $list[$newtopic_id]['name'];
		}

		uasort($list, "sort_by_postdate");
		$fm->_Write($fp_list, $list);

		reset($list);
		$oldlasttopic = key($list);
		while ($list[$oldlasttopic]['state'] == 'moved') {
			next($list);
			$oldlasttopic = key($list);
		}

		$allforums[$forum_id]['last_poster'] = $list[$oldlasttopic]['poster'];
		$allforums[$forum_id]['last_poster_id'] = $list[$oldlasttopic]['p_id'];
		$allforums[$forum_id]['last_time'] = $list[$oldlasttopic]['postdate'];
		$allforums[$forum_id]['last_key'] = $list[$oldlasttopic]['postkey'];
		$allforums[$forum_id]['last_post'] = $list[$oldlasttopic]['name'];
		$allforums[$forum_id]['last_post_id'] = $oldlasttopic;

		// Если сообщения перемещаются в тему, находящуюся в подфоруме, то обновим ластпост в родительском форуме
		// Также прибавим к числу ответов этого форума кол-во перемещаемых сообщений
		$pcatid = $allforums[$toforum_id]['catid'];
		if (mb_stristr($pcatid, 'f')) {
			$pforum = mb_substr($pcatid, 1, mb_strlen($pcatid) - 1);
			$allforums[$pforum]['posts'] += $countmoving;
			relast_post($pforum);
		}

		// Если сообщения перемещаются из темы, находящейся в подфоруме, то обновим ластпост в родительском форуме
		// Также вычтем из числа ответов этого форума кол-во перемещаемых сообщений
		$pcatid = $allforums[$forum_id]['catid'];
		if (mb_stristr($pcatid, 'f')) {
			$pforum = mb_substr($pcatid, 1, mb_strlen($pcatid) - 1);
			$allforums[$pforum]['posts'] -= $countmoving;
			relast_post($pforum);
		}

		// Запись в лог о переносе сообщений
		$fm->_WriteLog(sprintf($fm->LANG['InExistsLog'], $countmoving, $list[$topic_id]['name'], strip_tags($allforums[$forum_id]['name']), @$newtopicname, strip_tags($allforums[$toforum_id]['name'])), 2);

		unset( $list, $newlastposter, $newlist );
		$fm->_Write($fp_allforums, $allforums);
		//$fm->_Refresh = 15;
		$fm->_Message($fm->LANG['MoveSelectedInExists'], sprintf($fm->LANG['MoveInExistsOk'], $topicname), 'topic.php?forum=' . $toforum_id . '&topic=' . $newtopic_id . 'postid=' . $post_id . '#' . $post_id);
	}
	else {
		$postkey = serialize($postkey);
		$jumphtml = JumpHTML($allforums, $fm->_Intval('toforum'));
		$selectdesc = $fm->LANG['SelectIn'];
		$bodytitle = $fm->LANG['MoveSelectedInExists'];
		include( './templates/' . DEF_SKIN . '/moveposts_data.tpl' );
		$hidden = $row = '';
		if ($fm->_Boolean($fm->input, 'moving') === true) {
			if (( $toforum_id = $fm->_Intval('toforum') ) === 0 || !isset( $allforums[$toforum_id] )) {
				$fm->_Message($fm->LANG['MoveSelectedInExists'], $fm->LANG['ForumNotSelected']);
			}
			$jumptopichtml = JumpTopicHTML($toforum_id, $forum_id, $topic_id);
			$hidden = '<input type=hidden name="topicselected" value="yes">';
			$row = str_replace("{jumptopichtml}", $jumptopichtml, $row_inexists);
		}
		else {

		}

		$fm->_Title = ' :: ' . $fm->LANG['MoveSelectedInExists'];
		include( './templates/' . DEF_SKIN . '/moveposts_data.tpl' );
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/moveposts.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
}

function delattach() {
	global $fm;

	if ($fm->_POST === false) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST);
	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if (( $postkey = $fm->_String('postkey') ) === '' || ( $postkey = @unserialize($postkey) ) === false || count($postkey) === 0) {
		$fm->_Message($fm->LANG['DeletSelectedAtt'], $fm->LANG['NotSelectedPosts']);
	}

	$fm->_GetModerators($forum_id, $allforums);
	$list = $fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	if ($fm->_Moderator === false) {
		$fm->_Message($fm->LANG['DeletSelectedAtt'], $fm->LANG['EditNo']);
	}
	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php', false);
	$allattaches = $fm->_Read2Write($fp_attaches, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');

	$unlinked = 0;
	foreach ($postkey as $post_id) {
		if (isset( $topic[$post_id] ) && ( isset( $topic[$post_id]['attach_id'] ) )) {
			$attach_id = $topic[$post_id]['attach_id'];
			if (isset( $allattaches[$attach_id] )) {
				if (file_exists('uploads/' . $allattaches[$attach_id]['id'])) {
					unlink('uploads/' . $allattaches[$attach_id]['id']);
				}
				unset( $allattaches[$attach_id] );
				$unlinked++;
			}
			unset( $topic[$post_id]['attach_id'], $topic[$post_id]['attach_file'] );
		}
	}
	ksort($topic, SORT_NUMERIC);
	$fm->_Write($fp_topic, $topic);
	$fm->_Write($fp_attaches, $allattaches);
	if (count($allattaches) === 0) {
		unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
	}

	// Запись в лог информации об удалении прикреплённых файлов в теме
	if ($unlinked) {
		$fm->_WriteLog(sprintf($fm->LANG['DelAttachLog'], $unlinked, count($postkey), $list[$topic_id]['name'], $allforums[$forum_id]['name']), 2);
	}

	$fm->_Message($fm->LANG['DeletSelectedAtt'], $fm->LANG['AttachDeleteOk'], 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id);
}

function UpdateAutorsInfo(&$autors) {
	global $fm, $forum_id, $toforum_id;

	foreach ($autors as $user_id => $countposts) {
		if (file_exists(EXBB_DATA_DIR_MEMBERS . '/' . $user_id . '.php')) {
			$user = $fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $user_id . '.php');
			$user['posted'][$toforum_id] = ( isset( $user['posted'][$toforum_id] ) && $user['posted'][$toforum_id] >= 0 ) ? $user['posted'][$toforum_id] + $countposts : $countposts;
			$user['posted'][$forum_id] = ( isset( $user['posted'][$forum_id] ) && $user['posted'][$forum_id] >= $countposts ) ? $user['posted'][$forum_id] - $countposts : 0;
			if ($user['posted'][$forum_id] <= 0) {
				unset( $user['posted'][$forum_id] );
			}
			$fm->_Write($fp_user, $user);
			unset( $user );
		}
	}
	unset( $autors );

	return true;
}

function UpdateAutorsInfoDelete($users, $forum_id) {
	global $fm;
	$allusers = $fm->_Read2Write($fp_allusers, EXBB_DATA_USERS_LIST, false);

	foreach ($users as $user_id => $total) {
		if (file_exists(EXBB_DATA_DIR_MEMBERS . '/' . $user_id . '.php')) {
			$user = $fm->_Read2Write($fp_user, EXBB_DATA_DIR_MEMBERS . '/' . $user_id . '.php');
			$user['posts'] -= $total;
			if (isset( $user['posted'][$forum_id] )) {
				$user['posted'][$forum_id] -= $total;
				if (!$user['posted'][$forum_id]) {
					unset( $user['posted'][$forum_id] );
					unset( $user['lastpost'] );
				}
			}
			$fm->_Write($fp_user, $user);
			$allusers[$user_id]['p'] = $user['posts'];
		}
	}
	$fm->_Write($fp_allusers, $allusers);
	unset( $user, $allusers );
}

function UpdateOldList(&$topic, &$list) {
	global $fm, $countmoving, $topic_id;

	end($topic);
	$lastkey = key($topic);
	if ($list[$topic_id]['postkey'] != $lastkey) {
		$poster = GetName($topic[$lastkey]['p_id']);
		$list[$topic_id]['postkey'] = $lastkey;
		$list[$topic_id]['postdate'] = $lastkey;
		$list[$topic_id]['poster'] = $poster;
		$list[$topic_id]['p_id'] = $topic[$lastkey]['p_id'];
	}
	$list[$topic_id]['posts'] -= $countmoving;
}

function UpdateOldTopicFile(&$topic, &$postkey, $mode) {
	global $fm;
	global $list, $forum_id, $topic_id, $autors, $users, $attaches, $movingFlag, $countmoving;
	$newtopic = array();
	$attaches = array();
	$users = array();
	$autors = array();
	$countmoving = 0;
	$topic = $fm->_Read2Write($fp_topic, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');

	foreach ($postkey as $post_id) {
		if (isset( $topic[$post_id] )) {
			$newtopic[$post_id] = $topic[$post_id];//переносим все данные переносимого сообщения в новый массив
			$newtopic[$post_id]['moved'] = $mode . "::" . $forum_id . "::" . $topic_id . "::" . $list[$topic_id]['name'];
			if ($topic[$post_id]['p_id'] !== 0) {
				$autor_id = $topic[$post_id]['p_id'];
				$users[$autor_id][] = $post_id;
				if ($movingFlag === true) {
					$autors[$autor_id] = ( isset( $autors[$autor_id] ) ) ? $autors[$autor_id] + 1 : 1;
				}
			}
			if (isset( $topic[$post_id]['attach_id'] )) {
				$attaches[$post_id] = $topic[$post_id]['attach_id'];
			}
			unset( $topic[$post_id] );
			$countmoving++;
		}
	}
	unset( $postkey );
	ksort($topic, SORT_NUMERIC);
	$fm->_Write($fp_topic, $topic);

	return $newtopic;
}

function UpdateAttaches(&$newtopic, &$attaches) {
	global $fm, $forum_id, $topic_id, $toforum_id, $newtopic_id;

	//echo '<b>['.$topic_id.'-'.$newtopic_id.']</b>';
	$old_attach = $fm->_Read2Write($fp_old, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
	$new_attach = $fm->_Read2Write($fp_new, EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/attaches-' . $newtopic_id . '.php');
	$newattach_id = ( count($new_attach) == 0 ) ? 0 : max(array_keys($new_attach));
	foreach ($attaches as $post_id => $attach_id) {
		if (!isset( $old_attach[$attach_id] ) || !file_exists('uploads/' . $old_attach[$attach_id]['id'])) {
			if (file_exists('uploads/' . $old_attach[$attach_id]['id'])) {
				unlink('uploads/' . $old_attach[$attach_id]['id']);
			}
			unset( $newtopic[$post_id]['attach_id'], $newtopic[$post_id]['attach_file'] );
		}
		else {
			$newattach_id++;
			$new_attach[$newattach_id] = $old_attach[$attach_id];
			$newtopic[$post_id]['attach_id'] = $newattach_id;
			unset( $old_attach[$attach_id] );
		}
	}
	unset( $attaches );
	$fm->_Write($fp_old, $old_attach);
	$fm->_Write($fp_new, $new_attach);

	if (count($old_attach) === 0) {
		unlink(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php');
	}
	if (count($new_attach) === 0) {
		unlink(EXBB_DATA_DIR_FORUMS . '/' . $toforum_id . '/attaches-' . $newtopic_id . '.php');
	}

	return true;
}

function JumpHTML($allforums, $toforum = 0, $unset = 0) {
	global $fm;

	if ($toforum !== 0) {
		$selected_id = $toforum;
		$jumphtml = '<input type="hidden" name="toforum" value="' . $toforum . '">';
		$jumphtml .= $allforums[$toforum]['name'];
	}
	else {
		$selected_id = $fm->input['forum'];
		$jumphtml = '<select name="toforum" style="width:250px;">';
		$jumphtml .= '<option value="">' . $fm->LANG['ChooseForum'] . "</option>\n";
		$lastcatid = 0;
		foreach ($allforums as $forumid => $forum) {
			if (!defined('IS_ADMIN') && $forum['private'] === true && !isset( $fm->user['private'][$forumid] )) {
				continue;
			}
			if (mb_stristr($forum['catid'], 'f')) {
				continue;
			}

			$selected = ( $selected_id === $forumid ) ? ' selected' : '';
			if ($forum['catid'] != $lastcatid) {
				$jumphtml .= '<option value="">' . "</option>\n";
				$jumphtml .= '<option class=forumline value=""> ' . $forum['catname'] . "</option>\n";
			}
			if ($forumid != $unset) {
				$jumphtml .= '<option value="' . $forumid . '"' . $selected . '>-- &nbsp; ' . $forum['name'] . "</option>\n";
			}
			foreach ($allforums as $id => $sforum) {
				if (mb_stristr($sforum['catid'], 'f') && $forumid == mb_substr($sforum['catid'], 1, mb_strlen($sforum['catid']) - 1)) {
					if (!( !defined('IS_ADMIN') && $sforum['private'] === true && !isset( $fm->user['private'][$id] ) )) {
						if ($id != $unset) {
							$selected = ( $selected_id === $id ) ? ' selected' : '';
							$jumphtml .= '<option value="' . $id . '"' . $selected . '>---- &nbsp; ' . $sforum['name'] . "</option>\n";
						}
					}
				}
			}
			$lastcatid = $forum['catid'];
		}
		$jumphtml .= '</select>';
	}

	return $jumphtml;
}

function JumpTopicHTML($toforum_id, $forum_id, $topic_id) {
	global $fm, $list;

	if ($toforum_id !== $forum_id) {
		$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	}

	if (count($list) === 0) {
		$fm->_Message($fm->LANG['MoveSelectedInExists'], $fm->LANG['ForumIsEmpty']);
	}
	$jumptopichtml = "<option value=\"\">" . $fm->LANG['SelectTopic'] . "</option>\n";
	foreach ($list as $top_id => $topicinfo) {
		if ($top_id == $topic_id && $toforum_id == $forum_id) {
			continue;
		}
		$topicinfo['name'] = ( isset( $topicinfo['tnun'] ) ) ? $topicinfo['name'] . ' - ' . $topicinfo['tnun'] : $topicinfo['name'];
		$jumptopichtml .= "<option value=\"" . $top_id . "\">" . $topicinfo['name'] . "</option>\n";
	}
	unset( $list );

	return $jumptopichtml;
}

function relast_post($forum_id) {
	global $fm, $allforums;

	$maxtime = 0;

	$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php', 0);

	reset($list);
	$last_id = key($list);
	while (@$list[$last_id]['state'] == 'moved') {
		next($list);
		$last_id = key($list);
	}

	@$allforums[$forum_id]['last_poster'] = $list[$last_id]['poster'];
	@$allforums[$forum_id]['last_poster_id'] = $list[$last_id]['p_id'];
	@$allforums[$forum_id]['last_time'] = $maxtime = $list[$last_id]['postdate'];
	@$allforums[$forum_id]['last_key'] = $list[$last_id]['postkey'];
	@$allforums[$forum_id]['last_post'] = $list[$last_id]['name'];
	@$allforums[$forum_id]['last_post_id'] = $last_id;
	unset( $allforums[$forum_id]['last_sub'] );

	foreach ($allforums as $id => $forum) {
		if (!mb_stristr($forum['catid'], 'f')) {
			continue;
		}
		if (mb_substr($forum['catid'], 1, mb_strlen($forum['catid']) - 1) == $forum_id && @$forum['last_time'] > $maxtime) {
			@$allforums[$forum_id]['last_poster'] = $forum['last_poster'];
			@$allforums[$forum_id]['last_poster_id'] = $forum['last_poster_id'];
			@$allforums[$forum_id]['last_time'] = $maxtime = $forum['last_time'];
			@$allforums[$forum_id]['last_key'] = $forum['last_key'];
			@$allforums[$forum_id]['last_post'] = $forum['last_post'];
			@$allforums[$forum_id]['last_post_id'] = $forum['last_post_id'];
			@$allforums[$forum_id]['last_sub'] = $id;
		}
	}

	return $allforums;
}

?>
