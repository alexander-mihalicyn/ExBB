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

if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0) {
	$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
}

$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);

if (!isset( $allforums[$forum_id] )) {
	$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['ForumNotExists']);
}

$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);
$fm->_GetModerators($forum_id, $allforums);
CheckForumPerms($allforums[$forum_id]['stview'], 'Views');


$perms = $fm->LANG['Views' . $allforums[$forum_id]['stview']];
$perms .= $fm->LANG['NewAdd' . $allforums[$forum_id]['stnew']];
$perms .= $fm->LANG['Reply' . $allforums[$forum_id]['strep']];

switch ($allforums[$forum_id]['strep']) {
	case 'all':
		$access = true;
	break;
	case 'reged':
		$access = ( $fm->user['id'] !== 0 ) ? true : false;
	break;
	case 'admo':
		$access = ( $fm->_Moderator === true ) ? true : false;
	break;
}

if (!file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
	$fm->_Message($fm->LANG['TopicOpen'], $fm->LANG['TopicMiss']);
}

/*Set topic last visits */
if (!$fm->exbb['watches'] && $fm->user['id'] !== 0) {
	$top_id = $forum_id . ":" . $topic_id;
	$t_visits = $fm->_GetCookieArray('t_visits');
	$TopicVisitTime = ( isset( $t_visits[$top_id] ) && $t_visits[$top_id] > $fm->user['last_visit'] ) ? $t_visits[$top_id] : $fm->user['last_visit'];
	$t_visits[$top_id] = time();
	$fm->_setcookie('t_visits', serialize($t_visits), 86400);
}

$category = $allforums[$forum_id]['catname'];
$forumname = $allforums[$forum_id]['name'];
$catid = $allforums[$forum_id]['catid'];
$upload = ( $fm->exbb['file_upload'] === true && $allforums[$forum_id]['upload'] !== 0 && ( $fm->user['upload'] === true || $fm->exbb['autoup'] === true && $fm->user['id'] ) ) ? $allforums[$forum_id]['upload'] : 0;
$forumcodes = ( $fm->exbb['exbbcodes'] === true && $allforums[$forum_id]['codes'] === true ) ? true : false;


/*Set topic views */
$viewsdata = $fm->_Read2Write($fp_views, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/views.php');
$viewsdata[$topic_id] = ( isset( $viewsdata[$topic_id] ) ) ? $viewsdata[$topic_id] + 1 : 1;
$fm->_Write($fp_views, $viewsdata);

$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
$topic = $list[$topic_id];
unset( $list, $viewsdata );

$topic['name'] = $fm->chunk_split($topic['name']);
$topic['name'] = ( isset( $topic['tnun'] ) && $topic['tnun'] !== 0 ) ? $topic['name'] . ' - ' . $topic['tnun'] : $topic['name'];
$topic['desc'] = ( isset( $topic['desc'] ) && !empty( $topic['desc'] ) ) ? 'Описание: ' . $fm->chunk_split($topic['desc']) : 'Без описания';
$topic['state'] = ( isset( $topic['state'] ) ) ? $topic['state'] : 'closed';
$topic['poll'] = ( isset( $topic['poll'] ) ) ? true : false;

$NewTopicButton = '<a href="post.php?action=new&forum=' . $forum_id . '"><img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/newthread.gif" border="0"></a>';
$NewPollButton = ( $allforums[$forum_id]['polls'] === true ) ? '&nbsp;<a href="post.php?action=new&poll=yes&forum=' . $forum_id . '"><img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/newpoll.gif" border="0"></a>' : '';
$ReplyButton = ( $topic['state'] == 'open' ) ? '<a href="post.php?action=reply&forum=' . $forum_id . '&topic=' . $topic_id . '"><img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/replytothread.gif" border="0"></a>' : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/closed.gif" border="0">';

/* ПОДСВЕТКА ПОИСКА */
$findstring = $search_link = '';
if ($fm->_String('search_id') !== '') {
	$search_array = $fm->_Read(EXBB_DATA_DIR_SEARCH . '/temp/' . $fm->input['search_id']);
	if (sizeof($search_array)) {
		$findstring = implode("|", $search_array['entered_word_arr']);
		$search_link = "&amp;search_id=" . $fm->input['search_id'];
		unset( $search_array );
	}
	else {
		unlink(EXBB_DATA_DIR_SEARCH . '/temp/' . $fm->input['search_id']);
	}
}
/* ПОДСВЕТКА ПОИСКА */

$threads = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php', false);
$threads_keys = array_keys($threads);
$end_key = end($threads_keys);
sort($threads_keys, SORT_NUMERIC);
$firstkey = reset($threads_keys);
require( 'modules/watches/_topic.php' );
/*	Прикрепление сообщений	*/
$_pinmsg = ( isset( $threads[$firstkey]['pinmsg'] ) ) ? $threads[$firstkey]['pinmsg'] : array();
foreach ($_pinmsg as $offset => $key) {
	if (!isset( $threads[$key] )) {
		unset( $_pinmsg[$offset] );
	}
	else {
		unset( $threads_keys[array_search($key, $threads_keys)] );
	}
}

/* Paginator */
$TotalPosts = count($threads_keys);
$get_param = 'topic.php?forum=' . $forum_id . '&topic=' . $topic_id . $search_link . '&p={_P_}';
$post_key = $unread_key = false;
if ($fm->_String('v') == 'l') {
	$_pages = ceil($TotalPosts / $fm->user['posts2page']);
	$_posts = $TotalPosts + $_pages * count($_pinmsg);
	$fm->input['p'] = ceil($_posts / $fm->user['posts2page']);
}
else {
	if ($fm->input['v'] == 'u' && $fm->user['id'] !== 0) {
		foreach ($threads_keys as $post_key => $post_time) {
			if ($fm->user['id'] != $threads[$post_time]['p_id'] && $TopicVisitTime < $post_time) {
				break;
			}
		}

		if ($post_time <= $fm->user['last_visit']) {
			foreach ($_pinmsg as $post_time) {
				if ($fm->user['id'] != $threads[$post_time]['p_id'] && $TopicVisitTime < $post_time) {
					$unread_key = $post_time;

					break;
				}
			}
		}
	}
	else {
		if ($fm->_Intval('postid') !== 0) {
			$post_key = array_search($fm->input['postid'], $threads_keys);
		}
	}
}

if ($post_key !== false) {
	$fm->input['p'] = ceil(( $post_key + 1 ) / ( $fm->user['posts2page'] - count($_pinmsg) ));

	if ($unread_key === false) {
		$unread_key = $threads_keys[$post_key];
	}
}

$pages = Print_Paginator($TotalPosts, $get_param, $fm->user['posts2page'] - count($_pinmsg), 8, $first, false);
$threads_keys = array_slice($threads_keys, $first, $fm->user['posts2page'] - count($_pinmsg));
$threads_keys = array_merge($_pinmsg, $threads_keys);

$allranks = ( $fm->exbb['ratings'] === true && $fm->_IsSpider === false ) ? $fm->_Read(EXBB_DATA_MEMBERS_TITLES) : array();
$defranks = reset($allranks);

$t_attaches = ( file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php') ) ? $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/attaches-' . $topic_id . '.php', false) : array();

$users = array();
$topic_data = '';

$_icon['divider'] = ( $fm->exbb['text_menu'] === true ) ? ' : ' : '';
$_icon['prf'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconPrf'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/profile.gif" border="0">';
$_icon['pm'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconPM'] : '<img src="./templates/' . DEF_SKIN . '/im/message.gif" border="0">';
$_icon['www'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconWWW'] : '<img src="./templates/' . DEF_SKIN . '/im/homepage.gif" border="0">';
$_icon['eml'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconMail'] : '<img src="./templates/' . DEF_SKIN . '/im/email.gif" border="0">';
$_icon['aol'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconAOL'] : '<img src="./templates/' . DEF_SKIN . '/im/aol.gif" border="0">';
$_icon['icq'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconICQ'] : '<img src="http://people.icq.com/scripts/online.dll?icq=%d&img=5"  border="0" hspace="0" vspace="0" class="icq">';
$_icon['edit'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconEdit'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/edit.gif" title=' . $fm->LANG['IconEdit'] . ' alt=' . $fm->LANG['IconEdit'] . ' border="0">';
$_icon['del'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconDel'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/delete.gif" border="0">';
$_icon['addpun'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconPun'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/punish.gif" border="0">';
$_icon['report'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconReport'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/report.gif" border="0">';
$_icon['reply'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconReply'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/reply.gif" border="0" title="' . $fm->LANG['IconReply'] . '" alt="' . $fm->LANG['IconReply'] . '">';
$_icon['quote'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconQuote'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/quote.gif" border="0" title="' . $fm->LANG['IconQuote'] . '" alt="' . $fm->LANG['IconQuote'] . '">';
$_icon['postid'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconPostid'] : '<img src="./templates/' . DEF_SKIN . '/im/postid.gif" border="0" title="' . $fm->LANG['ViewPostAddress'] . '" alt="Post Id">';
// ********* мод сказать "спасибо" *********
$_icon['$thank_i'] = ( $fm->exbb['text_menu'] === true ) ? $fm->LANG['IconThank'] : '<img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/thanks.gif" border="0" title="' . $fm->LANG['DescThank'] . '" alt="' . $fm->LANG['IconThank'] . '">';
if ($fm->input['action'] == 'thanks' && $fm->user['id'] != 0) {
	$key = $fm->input['post'];
	$member_id = ( isset( $threads[$key]['p_id'] ) ) ? $threads[$key]['p_id'] : 0;
	if ($fm->user['id'] != $member_id) {
		$threads = $fm->_Read2Write($fp_threads, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php');
		if (!isset( $threads[$key]['thanks'] )) {
			$threads[$key]['thanks'] = $fm->user['id'];
		}
		if (mb_strpos($threads[$key]['thanks'], strval($fm->user['id'])) === false) {
			$threads[$key]['thanks'] .= ',' . $fm->user['id'];
		}
		echo $threads[$key]['thanks'];
		//unset ($threads[$key]['thanks']);
		$fm->_Write($fp_threads, $threads);
	}
	header('Location: topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&postid=' . $key . '#' . $key);
}
// *******************************************
if ($fm->exbb['karma'] === true) {
	$fm->_LoadModuleLang('karma');
}
if ($fm->exbb['reputation'] === true) {
	$fm->_LoadModuleLang('reputation');
}

/*Subforums*/
$subf = ( mb_stristr($catid, 'f') ) ? mb_substr($catid, 1, mb_strlen($catid) - 1) : 0;
if ($subf) {
	$pcatid = $allforums[$subf]['catid'];
	$pcatname = $allforums[$subf]['catname'];
	$pforumname = $allforums[$subf]['name'];
}

$onlinedata = $fm->_OnlineLog($fm->LANG['TopicSee'] . ' <a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '"><b>' . $topic['name'] . '</b></a> - <a href="forums.php?forum=' . $forum_id . '"><b>' . $forumname . '</b></a>', $privateID);

// Advanced Visit Stats for ExBB FM 1.0 RC1 by yura3d
$stattopic = false;
if ($fm->exbb['statvisit']) {
	if ($statvisit['topic']) {
		viewing($stattopic);
	}
}
require( 'modules/ads/_topic.php' );
foreach ($threads_keys as $id => $key) {
	$member_id = ( isset( $threads[$key]['p_id'] ) ) ? $threads[$key]['p_id'] : 0;
	$postIP = ( defined('IS_ADMIN') ) ? sprintf($fm->LANG['ViewIpInfo'], $threads[$key]['ip']) : '&nbsp;';
	$post = $threads[$key]['post'] . ' ';

	if ($fm->exbb['botlight'] && $fm->_IsSpider !== false) {
		$topic_data .= '<tr><td colspan="2">' . $post . '</td></tr>';
		continue;
	}

	/* Attach */
	if (isset( $threads[$key]['attach_id'] ) && isset( $t_attaches[$threads[$key]['attach_id']] )) {
		$attach_ID = $threads[$key]['attach_id'];
		$attach_name = $threads[$key]['attach_file'];
		$attach_file = $t_attaches[$attach_ID]['file'];
		if ($t_attaches[$attach_ID]['type'] === 'image' && $fm->exbb['show_img'] === true) {
			if ($fm->exbb['imgpreview'] === true) {
				$post .= ( $t_attaches[$attach_ID]['width'] > 250 ) ? $fm->LANG['ImgAttachTmb'] . '<a href="printfile.php?action=attach&img=yes&f=' . $forum_id . '&t=' . $topic_id . '&id=' . $attach_ID . '" title="' . $fm->LANG['ToIncrease'] . '" rel="clearbox"><img src="printfile.php?action=attach&icon=yes&img=yes&f=' . $forum_id . '&t=' . $topic_id . '&id=' . $attach_ID . '" alt="' . $attach_name . '" style="border: 1px outset #DCDCDC;"></a><br>' : $fm->LANG['ImgAttach'] . '<img src="printfile.php?action=attach&img=yes&f=' . $forum_id . '&t=' . $topic_id . '&id=' . $attach_ID . '" alt="' . $attach_name . '"><br>';
			}
			else {
				$post .= $fm->LANG['ImgAttach'] . '<div align=center><img src="printfile.php?action=attach&img=yes&f=' . $forum_id . '&t=' . $topic_id . '&id=' . $attach_ID . '" alt="' . $attach_name . '"></div><br>';
			}
		}
		else {
			$attachurl = ( $fm->user['id'] !== 0 ) ? '<a href="printfile.php?action=attach&f=' . $forum_id . '&t=' . $topic_id . '&id=' . $attach_ID . '" target="_blank">' . $attach_name . '</a>' : '' . $fm->LANG['ViewAttachLink'] . '';
			$post .= '<br /><br /><div align=left >' . $fm->LANG['DownloadAttach'] . ' ' . $attachurl . '<br><span class="moder">' . $fm->LANG['DownloadsAttach'] . $t_attaches[$attach_ID]['hits'] . '</span></div>';
		}
	}
	/* Attach */

	/* Edited text */
	if (isset( $threads[$key]['ad_edited'] )) {
		$post .= '<p><hr><span class="admin"><i>' . $fm->LANG['EditedAdmin'] . $threads[$key]['ad_editor'] . ', ' . $fm->_DateFormat($threads[$key]['ad_edited'] + $fm->user['timedif'] * 3600) . '</i></span>';
		if (isset( $threads[$key]['mo_text'] )) {
			$post .= '<br />' . $threads[$key]['mo_text'];
		}
	}
	elseif (isset( $threads[$key]['mo_edited'] )) {
		$post .= '<p><hr><span class="moder"><i>' . $fm->LANG['EditedModer'] . $threads[$key]['mo_editor'] . ', ' . $fm->_DateFormat($threads[$key]['mo_edited'] + $fm->user['timedif'] * 3600) . '</i></span>';
		if (isset( $threads[$key]['mo_text'] ) && !isset( $threads[$key]['ad_edited'] )) {
			$post .= '<br>' . $threads[$key]['mo_text'];
		}
	}
	elseif (isset( $threads[$key]['edited'] )) {
		$post .= '<p><i>(' . $fm->LANG['EditedAutor'] . $fm->_DateFormat($threads[$key]['edited'] + $fm->user['timedif'] * 3600) . ')</i>';
	}
	/* Edited text */

	if (!isset( $users[$member_id] )) {
		if (!$fm->_Checkuser($member_id)) {
			setup_guest($member_id);
		}
		else {
			setup_member($member_id);
		}

	}

	$username = ( $access === true && $topic['state'] != 'closed' ) ? '<a href="#" name="bold" onClick="bbcode(this,\'' . addslashes($users[$member_id]['user']) . '\'); return false;"><b>' . $users[$member_id]['user'] . '</b></a>' : '<b>' . $users[$member_id]['user'] . '</b>';
	$username2 = ( $access === true && $topic['state'] != 'closed' ) ? '<a href="#" name="bold" onClick="bbcode(this,\'' . addslashes($users[$member_id]['user']) . '\'); return false;" title="Нажмите сюда для вставки ника автора этого поста в ваше сообщение"><b>Обратиться по нику</b></a>' : '';
	$usertitle = $users[$member_id]['usertitle'];
	$teamcon = $users[$member_id]['team'];
	$useravatar = $users[$member_id]['useravatar'];
	$usergraphic = $users[$member_id]['usergraphic'];
	$online = $users[$member_id]['onl'];
	$posts = $users[$member_id]['posts'];
	$joined = $users[$member_id]['jnd'];
	$location = $users[$member_id]['location'];

	$prf = $users[$member_id]['prf'];
	$eml = $users[$member_id]['eml'];
	$www = $users[$member_id]['www'];
	$icq = $users[$member_id]['icq'];
	$uin = $users[$member_id]['uin'];
	$aim = $users[$member_id]['aim'];
	$pm = $users[$member_id]['pm'];
	$reputation = ( isset( $users[$member_id]['reputation'] ) ) ? sprintf($fm->LANG['ReputationIs'], sprintf($users[$member_id]['reputation'], $key, $key)) : '';
	$karma = ( $users[$member_id]['karmalink'] !== '' ) ? sprintf($users[$member_id]['karmalink'], $member_id, $key, $users[$member_id]['karma']) : '';
	$pun = $users[$member_id]['pun'];/*  ШТРАФЫ  */
	$addpun = sprintf($users[$member_id]['addpun'], $forum_id, $topic_id, $key);

	/*	Прикрепление сообщений	*/
	if ($fm->_Moderator === true) {
		$pinmsg = ( in_array($key, $_pinmsg) ) ? 'pinned.gif' : 'pin.gif';
		$pinmsg = '<a href="postings.php?action=pinmsg&forum=' . $forum_id . '&topic=' . $topic_id . '&post=' . $key . '"><img src="templates/' . DEF_SKIN . '/im/' . $pinmsg . '" border="0"></a>';
	}
	else {
		$pinmsg = ( in_array($key, $_pinmsg) ) ? '<img src="templates/' . DEF_SKIN . '/im/pinned.gif" border="0">' : '';
	}

	/*  Опции сообщения */
	$edit = ( $fm->_Moderator === true || ( $fm->user['id'] === $member_id && $fm->user['id'] && $topic['state'] != 'closed' ) ) ? '<a href="postings.php?action=edit&forum=' . $forum_id . '&topic=' . $topic_id . '&postid=' . $key . '">' . $_icon['edit'] . '</a>' . $_icon['divider'] : '';
	$del = ( $fm->_Moderator === true ) ? '<a href="postings.php?action=processedit&deletepost=yes&forum=' . $forum_id . '&topic=' . $topic_id . '&postid=' . $key . '" onClick="DelPost(this,' . $key . '); return false">' . $_icon['del'] . '</a>' . $_icon['divider'] : '';
	$quote = ( $access === true && $topic['state'] != 'closed' ) ? '<a href="#" name="quote" onmouseover="copyQ();" onClick="bbcode(this,\'' . addslashes($users[$member_id]['user']) . '\'); return false;" title="' . $fm->LANG['IconQuote'] . '">' . $_icon['quote'] . '</a>' . $_icon['divider'] : "";
	$quote2 = ( $access === true && $topic['state'] != 'closed' ) ? '<a href="#" name="quote" onmouseover="copyQ();" onClick="bbcode(this,\'' . addslashes($users[$member_id]['user']) . '\'); return false;" title="Для вставки цитаты из этого поста выделите текст и нажмите на эту ссылку"><b>Ответить с цитированием</b></a>' . $_icon['divider'] : "";
	$reply = ( $access === true && $topic['state'] != 'closed' ) ? '<a href="post.php?action=replyquote&forum=' . $forum_id . '&topic=' . $topic_id . '&postid=' . $key . '" title="' . $fm->LANG['IconReply'] . '">' . $_icon['reply'] . '</a>' . $_icon['divider'] : "";
	$report = ( $access === true && $topic['state'] != 'closed' && $fm->exbb['preport'] === true && $fm->user['id'] !== 0 ) ? '<a href="tools.php?action=preport&forum=' . $forum_id . '&topic=' . $topic_id . '&postid=' . $key . '" title="' . $fm->LANG['Report2Moder'] . '">' . $_icon['report'] . '</a>' . $_icon['divider'] : '';
	$postId = '<a href="#" onClick="PostId(this,' . $key . '); return false;" title="' . $fm->LANG['IconPostid'] . '">' . $_icon['postid'] . '</a>';

	/* Модераторскоя опция удаления сообщений скопом */
	$delbox = ( $fm->_Moderator === true && $key != $firstkey ) ? '<input name="postkey[]" type="checkbox" value="' . $key . '">' : "";

	$postdate = $fm->_DateFormat($key + $fm->user['timedif'] * 3600);
	$info = $fm->LANG['PostDate'] . ' <b>' . $postdate . '</b> ' . $postIP;

	// ********* мод сказать "спасибо" *********
	$say_thank_b = ( $access === true && $topic['state'] != 'closed' && $fm->user['id'] != 0 && $fm->user['id'] != $member_id ) ? '<a href="topic.php?action=thanks&forum=' . $forum_id . '&topic=' . $topic_id . '&post=' . $key . '" title=' . $fm->LANG['DescThank'] . '>' . $_icon['$thank_i'] . '</a>' . $_icon['divider'] : '';
	$say_thank_d = '';
	if (isset( $threads[$key]['thanks'] )) {
		$th_list = '';
		$th_id = explode(",", $threads[$key]['thanks']);
		$th_count = count($th_id);
		foreach ($th_id as $usid) {
			if ($member = $fm->_Getmember($usid)) {
				if (in_array($usid, $fm->_Moderators)) {
					$member['status'] = 'mo';
				}
				switch ($member['status']) {
					case 'ad':
						$class = ' class="admin"';
					break;
					case 'sm':
						$class = ' class="supmoder"';
					break;
					case 'mo':
						$class = ' class="moder"';
					break;
					default:
						$class = '';
				}
				$th_list .= '<a href="profile.php?action=show&member=' . $usid . '"' . $class . '>' . $member['name'] . '</a>&nbsp;&nbsp;';
			}
		}
		if ($th_count > 5) {
			$say_thank_d = '<br /><br /><span><b><i>' . sprintf($fm->LANG['ThankMsg'] . $fm->LANG['ThankAddon'], $th_count) . '</i></b></span>
 <span id="sp' . $key . '">(<a href="#" onClick="spoiler(\'' . $key . '\'); return false;">' . $fm->LANG['SpoilerShow'] . '</a>)</span>
 <div id="spoiler' . $key . '" style="display: none;">' . $th_list . '</div>';
		}
		else {
			$say_thank_d = '<br /><br /><span><b><i>' . $fm->LANG['ThankMsg'] . ': </i></b></span>' . $th_list;
		}
	}
	// *****************************************

	$post .= ( $topic['state'] == "closed" && $topic['postkey'] == $key ) ? '<br><img src="./templates/' . DEF_SKIN . '/im/close.gif" alt="' . $fm->LANG['TopicIsClosed'] . '" border="0" class="closeimg">' : '';
	$post .= $users[$member_id]['signature'];

	$html = ( isset( $threads[$key]['html'] ) ) ? $threads[$key]['html'] : false;

	if ($forumcodes === true) {
		$post = $fm->formatpost($post, $html, $threads[$key]['smiles'], $findstring);
	}

	$postbackcolor = ( !( $id % 2 ) ) ? 'row1' : 'row2';

	/*  Перенесенные посты */
	if (isset( $threads[$key]['moved'] )) {
		list( $type, $forimid, $topicid, $topicname ) = explode("::", $threads[$key]['moved']);
		$post = '<span class="movedpost">' . $fm->LANG['Moved' . $type] . '"<a href="topic.php?forum=' . $forimid . '&topic=' . $topicid . '" target="_blank">' . $topicname . '</a>"</span><br>' . $post;
	}
	require( 'modules/ads/_topicPost.php' );
	$unread_anchor = ( $key === $unread_key ) ? '<a name="unread"></a>' : '';
	/*  Перенесенные посты */
	include( './templates/' . DEF_SKIN . '/topic_data.tpl' );
}
require( 'modules/ads/_topicPostLast.php' );
if ($topic['poll']) {
	$poll_html = poll($forum_id, $topic_id);
}
unset( $users );

$options = array();
if (!file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php') && $fm->user['id'] && $threads[$firstkey]['state'] != 'closed' && ( $threads[$firstkey]['p_id'] == $fm->user['id'] || $fm->_Moderator === true )) {
	$options[] = '<a href="postings.php?action=addpoll&forum=' . $forum_id . '&topic=' . $topic_id . '">' . $fm->LANG['AddPoll'] . '</a>';
}
$options['srch_intop'] = '<a href="search.php?action=intopic&forum=' . $forum_id . '&topic=' . $topic_id . '">' . $fm->LANG['SearchInTopic'] . '</a>';
$options['print'] = '<a href="printpage.php?forum=' . $forum_id . '&topic=' . $topic_id . '">' . $fm->LANG['PrintPage'] . '</a>';

$post_form = '';
if ($access === true && $topic['state'] != 'closed') {
	$fm->_LoadLang('formcode');

	$smilesbutton = ( $fm->exbb['emoticons'] === true ) ? '<input type=checkbox name="showsmiles" value="yes" checked> ' . $fm->LANG['DoSmiles'] . '<br>' : '';

	$reged = ( $fm->user['id'] === 0 ) ? ' &nbsp; <a href="register.php">' . $fm->LANG['YouReged'] . '</a>' : '';

	$emailnotify = ( $fm->user['id'] !== 0 && $fm->exbb['emailfunctions'] === true ) ? '<input type=checkbox name="notify" value="yes">' . $fm->LANG['DoEmail'] . '<br>' : '';

	$enctype = ( $upload !== 0 ) ? ' enctype="multipart/form-data"' : '';

	if ($fm->user['id'] !== 0 && $fm->exbb['emailfunctions'] === true && $fm->user['mail']) {
		//  Опция подписки на тему //
		$trackdata = $fm->_Read2Write($fp_track, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/_t_track.php');
		switch ($fm->input['action']) {
			case 'untrack':
				if (isset( $trackdata[$topic_id][$fm->user['id']] )) {
					unset( $trackdata[$topic_id][$fm->user['id']] );
				}
				if (count($trackdata[$topic_id]) == 0) {
					unset( $trackdata[$topic_id] );
				}
				$fm->_Write($fp_track, $trackdata);
				$options['track'] = '<a href="topic.php?action=track&forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $current_page . '">' . $fm->LANG['TrackTopic'] . '</a>';
			break;
			case 'track':
				$trackdata[$topic_id][$fm->user['id']] = 1;
				$options['track'] = '<a href="topic.php?action=untrack&forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $current_page . '">' . $fm->LANG['UntrackTopic'] . '</a>';
				$fm->_Write($fp_track, $trackdata);
			break;
			default:
				$fm->_Fclose($fp_track);
				$options['track'] = ( isset( $trackdata[$topic_id][$fm->user['id']] ) ) ? '<a href="topic.php?action=untrack&forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $current_page . '">' . $fm->LANG['UntrackTopic'] . '</a>' : '<a href="topic.php?action=track&forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $current_page . '">' . $fm->LANG['TrackTopic'] . '</a>';
		}
		unset( $trackdata );
		//  Опция подписки на тему //
	}
	include( './templates/' . DEF_SKIN . '/post_form.tpl' );
}
$options = implode(' | ', $options);

$mod_options = '';
if ($fm->_Moderator === true) {
	$pin = ( $topic['pinned'] === true ) ? '<option value="unpin">' . $fm->LANG['UnPin'] . '</option>' : '<option value="pin">' . $fm->LANG['Pin'] . '</option>';
	if ($topic['state'] == 'open') {
		$do = 'lock';
		$fm->LANG['Unlock'] = $fm->LANG['Blocking'];
	}
	else {
		$do = 'unlock';
	}
	include( './templates/' . DEF_SKIN . '/topic_options.tpl' );
}

$jumphtml = forumjump($allforums);

$fm->_Title = ' :: ' . $topic['name'];
$fm->_Title .= ( $current_page > 1 ) ? ' [' . $current_page . ']' : '';
$fm->_Keywords = ( !empty( $threads[$firstkey]['keywords'] ) ) ? $threads[$firstkey]['keywords'] . ' ' : '';
if ($fm->exbb['imgpreview']) {
	$fm->_Link .= "\n<link href=\"clearbox/css/clearbox.css\" rel=\"stylesheet\" type=\"text/css\" />\n<script src=\"clearbox/js/clearbox.js\" type=\"text/javascript\"></script>";
}
$fm->_Link .= "\n<LINK rel=\"Start\" title=\"Первая страница темы - First page\" type=\"text/html\" href=\"{$fm->exbb['boardurl']}/topic.php?forum={$forum_id}&topic={$topic_id}\">
<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/board.js\"></script>
<script type=\"text/javascript\" language=\"JavaScript\">
<!--
var LANG = {
	Sure:				'{$fm->LANG['Sure']}',
	Canceled:			'{$fm->LANG['Canceled']}',
	SureSelectAll:		'{$fm->LANG['SureSelectAll']}',
	SureDelSelected:	'{$fm->LANG['SureDelSelected']}',
	ActNotSelected:		'{$fm->LANG['ActNotSelected']}',
	ThisPostWWW:		'{$fm->LANG['ThisPostWWW']}',
	EmptySelect: 		'{$fm->LANG['EmptySelect']}',
	SpoilerShow: 		'{$fm->LANG['SpoilerShow']}',
	SpoilerHide: 		'{$fm->LANG['SpoilerHide']}'
};
//-->
</script>";
if ($fm->_Moderator === true) {
	$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/topicmoder.js\"></script>";
}
if ($fm->_Modoutput) {
	$fm->_Modoutput = '(' . $fm->_Modoutput . ')';
}
if ($allforums[$forum_id]['stview'] == 'all' && !$allforums[$forum_id]['private']) {
	$_SESSION['rd'] = $_SERVER['REQUEST_URI'];
	if ($fm->_Intval('postid') !== 0) {
		$_SESSION['rd'] .= '#' . $fm->input['postid'];
	}
	else {
		if ($fm->_String('v') !== '') {
			$_SESSION['rd'] .= '#' . $end_key;
		}
	}
}
include( './templates/' . DEF_SKIN . '/all_header.tpl' );
include( './templates/' . DEF_SKIN . '/logos.tpl' );
include( './templates/' . DEF_SKIN . '/topic_body.tpl' );
include( './templates/' . DEF_SKIN . '/footer.tpl' );
include( 'page_tail.php' );

function setup_guest($user_id = 0) {
	global $fm, $users;

	$users[$user_id] = array( 'user' => $fm->LANG['Guest'], 'usertitle' => ( $user_id === 0 ) ? $fm->LANG['NoReged'] : $fm->LANG['UserDeleted'], 'team' => '', 'useravatar' => '', 'usergraphic' => '', 'onl' => '', 'posts' => '', 'jnd' => '', 'location' => '', 'prf' => '', 'eml' => '', 'www' => '', 'icq' => '', 'uin' => '', 'aim' => '', 'pm' => '', 'signature' => '', 'karma' => '', 'karmalink' => '', 'pun' => '', 'addpun' => '' );
}

function setup_member($user_id) {
	global $fm, $users, $topic, $allranks, $defranks, $_icon, $forum_id, $topic_id, $key;

	$user = $fm->_Getmember($user_id);
	if ($fm->exbb['redirect'] && $user['www'] !== '' && $user['www'] != 'http://' && !mb_stristr($user['www'], 'http://www.' . $fm->exbb_domain) && !mb_stristr($user['www'], 'http://' . $fm->exbb_domain)) {
		$user['www'] = $fm->out_redir . $user['www'];
	}
	$user['title'] = ( $user['status'] == "banned" ) ? '<font color="#cc0000"><b>Забанен</b></font>' : $user['title'];
	$users[$user_id] = array( 'user' => $user['name'], 'usertitle' => $user['title'], 'team' => '', 'useravatar' => ( $fm->exbb['avatars'] === true && $user['avatar'] != 'noavatar.gif' && $user['avatar'] != '' ) ? '<br><img src="./im/avatars/' . $user['avatar'] . '" border="0">' : '<br /><img src="im/avatars/noavatar.gif" border="0" />', 'usergraphic' => '', 'onl' => '', 'posts' => sprintf($fm->LANG['UsertotalPosts'], $user['posts']), 'jnd' => $fm->LANG['UserRegDate'] . ' <b>' . ( ( $user['joined'] ) ? $fm->_JoinDate($user['joined']) : $fm->LANG['NA'] ) . '</b> &nbsp;', 'location' => ( $fm->exbb['location'] === true && $user['location'] != '' ) ? '<br>' . $fm->LANG['From'] . ': ' . $user['location'] : '', 'prf' => '<a href="profile.php?action=show&member=' . $user_id . '" title="' . $fm->LANG['UserProfile'] . ' ' . $user['name'] . '" target="_blank">' . $_icon['prf'] . '</a>' . $_icon['divider'], 'eml' => '', 'www' => ( $user['www'] !== '' ) ? '<a href="' . $user['www'] . '" target="_blank">' . $_icon['www'] . '</a>' . $_icon['divider'] : '', 'icq' => ( $user['icq'] != '' ) ? '<a href="' . ( ( $fm->exbb['redirect'] ) ? $fm->out_redir : '' ) . 'http://people.icq.com/' . $user['icq'] . '">' . sprintf($_icon['icq'], $user['icq']) . '</a> ' . $_icon['divider'] : '', 'uin' => ( $user['icq'] != '' ) ? $user['icq'] : '', 'aim' => ( $user['aim'] != '' ) ? '<a href="aim:goim?screenname=' . $user['aim'] . '&amp;message=Hello+Are+you+there?">' . $_icon['aol'] . '</a>' . $_icon['divider'] : '', 'pm' => ( $fm->exbb['pm'] === true && $fm->user['id'] !== 0 ) ? '<a href="messenger.php?action=new&touser=' . $user_id . '" title="' . $fm->LANG['SendPm'] . ' ' . $user['name'] . '" target="_blank">' . $_icon['pm'] . '</a>' : '', 'signature' => ( $fm->exbb['sig'] === true && $user['sig_on'] === true && $user['sig'] != '' ) ? '<br><br>-----<br>' . $user['sig'] : '', 'karma' => '', 'karmalink' => '', 'pun' => '', 'addpun' => '' );


	switch ($user['status']) {
		case 'ad':
			$users[$user_id]['usertitle'] = ( $users[$user_id]['usertitle'] == '' ) ? $fm->LANG['Admin'] : $users[$user_id]['usertitle'];
			$users[$user_id]['team'] = ' <img src="./templates/' . DEF_SKIN . '/im/team.gif" border="0" alt="' . $fm->LANG['Admin'] . '" title="' . $fm->LANG['Admin'] . '">';
		break;
		case 'sm':
			$users[$user_id]['usertitle'] = ( $users[$user_id]['usertitle'] == '' ) ? $fm->LANG['SuperModer'] : $users[$user_id]['usertitle'];
			$users[$user_id]['team'] = ' <img src="./templates/' . DEF_SKIN . '/im/steam.gif" border="0" alt="' . $fm->LANG['SuperModer'] . '" title="' . $fm->LANG['SuperModer'] . '">';
		break;
		case 'banned':
			$users[$user_id]['usertitle'] = $fm->LANG['Banned'];
		break;
		default:
			if (in_array($user_id, $fm->_Moderators)) {
				$users[$user_id]['team'] = ' <img src="./templates/' . DEF_SKIN . '/im/mteam.gif" border="0" alt="' . $fm->LANG['Moderator'] . '" title="' . $fm->LANG['Moderator'] . '">';
				$users[$user_id]['usertitle'] = $fm->LANG['Moderator'];
			}
			else {
				$users[$user_id]['team'] = '<img src="./templates/' . DEF_SKIN . '/im/user2.gif" border="0" alt="' . $fm->LANG['User'] . '" title="' . $fm->LANG['User'] . '">';
			}
		break;
	}

	if ($users[$user_id]['usertitle'] == '') {

		// Хоть убейте, но не помню что здесь должно было быть ==))
	}

	if ($fm->exbb['ratings'] === true) {
		$users[$user_id]['usertitle'] = ( $user['title'] === '' ) ? $defranks['title'] : $user['title'];
		$users[$user_id]['usergraphic'] = '<img src="./im/images/' . $defranks['icon'] . '" border="0">';
		foreach ($allranks as $info) {
			if ($user['posts'] >= $info['posts']) {
				$users[$user_id]['usertitle'] = ( $user['title'] == '' ) ? $info['title'] : $user['title'];
				$users[$user_id]['usergraphic'] = '<img src="./im/images/' . $info['icon'] . '" border="0">';
			}
		}
	}

	if ($user['showemail'] === true) {
		$users[$user_id]['eml'] = '<a href="mailto:' . $user['mail'] . '">' . $_icon['eml'] . '</a>' . $_icon['divider'];

	}
	elseif ($fm->exbb['emailfunctions'] === true) {
		$users[$user_id]['eml'] = '<a href="tools.php?action=mail&member=' . $user_id . '" title="' . $fm->LANG['ForumEml'] . '">' . $_icon['eml'] . '</a>' . $_icon['divider'];
	}

	/* Репутация */
	if ($fm->exbb['reputation']) {
		$reputation = ( isset( $user['reputation'] ) ) ? $user['reputation'] : 0;
		$reputation = '<b>' . $reputation . '</b>';
		if (isset( $user['reputation'] )) {
			$reputation = '<a href="tools.php?action=reputation&do=show&member=' . $user_id . '">' . $reputation . '</a>';
		}
		if ($fm->user['id'] && $fm->user['id'] != $user_id) {
			$reputation = '<a href="tools.php?action=reputation&do=down&forum=' . $forum_id . '&topic=' . $topic_id . '&post=%d">' . $fm->LANG['RepToDown'] . '</a> ' . $reputation . ' <a href="tools.php?action=reputation&do=up&forum=' . $forum_id . '&topic=' . $topic_id . '&post=%d">' . $fm->LANG['RepToUp'] . '</a>';
		}
		$users[$user_id]['reputation'] = $reputation;
	}
	/* Репутация */

	/*  КАРМА  */
	if ($fm->exbb['karma'] === true) {
		$users[$user_id]['karma'] = ( isset( $user['karma'] ) ) ? $user['karma'] : 0;
		$users[$user_id]['karmalink'] = ( $fm->user['id'] !== 0 ) ? $fm->LANG['Karma'] . '<a href="#" onClick="Karma(\'plus\', ' . $user_id . '); return false">' . $fm->LANG['KarmaAdd'] . '</a>/<a href="#" onClick="Karma(\'minus\', ' . $user_id . '); return false">' . $fm->LANG['KarmaDel'] . '</a><br>' : $fm->LANG['Karma'];
	}
	/*  КАРМА  */

	/*  ШТРАФЫ  */
	if ($fm->exbb['punish'] === true) {
		$pun_arr = array( "", "[+]", "[+][+]", "[+][+][+]", "[+][+][+][+]", "[+][+][+][+][+]" );
		$users[$user_id]['pun'] = ( isset( $user['punned'] ) && is_array($user['punned']) ) ? $pun_arr[count($user['punned'])] : '';
		$users[$user_id]['addpun'] = ( $fm->_Moderator === true ) ? "<a href=\"tools.php?action=punish&do=doact&forum=%1\$d&topic=%2\$d&postid=%3\$d\" onClick=\"window.open('tools.php?action=punish&do=doact&forum=%1\$d&topic=%2\$d&postid=%3\$d','punish','width=700,height=350,scrollbars=yes'); return false;\" title=\"" . $fm->LANG['OpenPunWin'] . "\">" . $_icon['addpun'] . "</a>" . $_icon['divider'] : '';
	}
	/*  ШТРАФЫ  */

	/*  Статус юзера On-Off-line  */
	if ($fm->exbb['showuseronline'] === true) {
		$user['visible'] = ( $fm->exbb['visiblemode'] === true ) ? $user['visible'] : false;
		$users[$user_id]['onl'] = ( $user['visible'] === false && isset( $fm->_OnlineIds[$user_id] ) ) ? $fm->LANG['UserOnLine'] : $fm->LANG['UserOffLine'];
	}
	/*  Статус юзера On-Off-line  */
}

function poll($forum_id, $topic_id) {
	global $fm, $topic, $firstkey, $_icon;

	$pollfile = EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-poll.php';

	if (!file_exists($pollfile)) {
		return '';
	}

	$poll_data = $fm->_Read($pollfile, false);

	if (!$poll_data['pollname']) {
		$poll_data['pollname'] = $topic['name'];
	}

	$poll_title = $poll_data['pollname'];

	$moderlinks = $pollch = $do = '';
	if ($fm->user['id'] === 0) {
		$pollch = '<tr><td>' . $fm->LANG['PollNeedLogin'] . '</td></tr>';
	}
	elseif (isset( $poll_data['ids'][$fm->user['id']] ) || $topic['state'] == 'closed') {
		foreach ($poll_data['choices'] as $choice) {
			$pid = $choice[0];
			$ptext = $choice[1];
			$votes = $choice[2];
			if (!$ptext) {
				continue;
			}

			$percent = ( $votes == 0 ) ? 0 : $votes / $poll_data['votes'] * 100;
			$percent = sprintf('%.2f', $percent) . '%';
			$width = ( $percent > 0 ) ? (int)$percent * 2 : 0;
			include( './templates/' . DEF_SKIN . '/poll_data.tpl' );
		}
		$do = '<b>' . $fm->LANG['VoteCount'] . $poll_data['votes'] . '</b>';
	}
	else {
		foreach ($poll_data['choices'] as $choice) {
			$pid = $choice[0];
			$ptext = $choice[1];
			if (!$ptext) {
				continue;
			}

			include( './templates/' . DEF_SKIN . '/poll_view.tpl' );
		}
		$do = '<input type="submit" name="submit" value="' . $fm->LANG['Vote'] . '" class="button" />';
	}
	$edit = '<a href="postings.php?action=poll&forum=' . $forum_id . '&topic=' . $topic_id . '">' . $_icon['edit'] . '</a>' . $_icon['divider'];
	$del = '<a href="postings.php?action=poll&delpoll=yes&savepoll=yes&forum=' . $forum_id . '&topic=' . $topic_id . '">' . $_icon['del'] . '</a>';
	$moderlinks = ( $fm->_Moderator === true ) ? $edit . ' ' . $del : null;

	include( './templates/' . DEF_SKIN . '/poll.tpl' );
	unset( $poll_data, $pollch );

	return $poll_html;
}

function viewing(&$viewing) {
	global $onlinedata, $allforums, $forum_id, $topic_id, $fm;

	$guests = $members = $hiddens = 0;
	$showonline = array();

	foreach ($onlinedata as $sess => $online) {
		if (preg_match("#\"topic\.php\?forum=" . $forum_id . "\&topic=" . $topic_id . "\"#is", $online['in'])) {
			if (!$online['id']) {
				$guests++;
			}
			elseif ($fm->exbb['visiblemode'] && !empty( $online['v'] ) && !defined('IS_ADMIN')) {
				$hiddens++;
			}
			else {
				if (empty( $online['v'] )) {
					$members++;
				}
				else {
					$hiddens++;
					$online['n'] .= '*';
				}

				switch ($online['st']) {
					case 'mo':
						$class = ' class="moder"';
					break;
					case 'ad':
						$class = ' class="admin"';
					break;
					case 'sm':
						$class = ' class="supmoder"';
					break;
					default:
						$class = '';
				}

				$showonline[] = '<a href="profile.php?action=show&member=' . $online['id'] . '"' . $class . '>' . $online['n'] . '</a>';
			}
		}
	}
	$showonline = ( ( $showonline ) ? ' &raquo; ' : '' ) . implode(' &raquo; ', $showonline);
	$showonline = sprintf($fm->LANG['TopicOnline'], $guests + $members + $hiddens, $guests, $members, ( $fm->exbb['visiblemode'] ) ? sprintf($fm->LANG['HiddensOnline'], $hiddens) : '', $showonline);

	$viewing = $showonline;
}

?>