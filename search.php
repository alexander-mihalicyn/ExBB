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

switch ($fm->input['action']) {
	case 'intopic'    :
		intopic();
	break;
	case 'newposts'    :
		newpostst();
	break;
	default            :
		search();
	break;
}
include( 'page_tail.php' );

function intopic() {
	global $fm;

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);

	if (( $topic_id = $fm->_Intval('topic') ) === 0 || ( $forum_id = $fm->_Intval('forum') ) === 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}

	$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
	if (!isset( $list[$topic_id] ) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/' . $topic_id . '-thd.php')) {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['TopicMiss']);
	}
	$fm->_Title = ' :: ' . $fm->LANG['SearchInTopic'];
	include( './templates/' . DEF_SKIN . '/all_header.tpl' );
	include( './templates/' . DEF_SKIN . '/logos.tpl' );
	include( './templates/' . DEF_SKIN . '/search_intop.tpl' );
	include( './templates/' . DEF_SKIN . '/footer.tpl' );
}

function newpostst() {
	global $fm, $pages;

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
	$t_visits = $fm->_GetCookieArray('t_visits');

	$allforums_keys = array_keys(array_filter($allforums, 'filterForums'));
	require( 'modules/watches/_search.php' );

	if (!$fm->exbb['watches']) {
		$alltopics = array();
		foreach ($allforums as $forum_id => $forum) {
			if (!defined('IS_ADMIN') && $forum['private'] === true) {
				if (!isset( $fm->user['private'][$forum_id] ) || $fm->user['private'][$forum_id] === false) {
					continue;
				}
			}
			if ($forum['last_time'] > $fm->user['last_visit']) {
				$alltopics = array_merge($alltopics, array_filter($fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php'), "SearchNewPost"));
			}
		}
	}

	if (count($alltopics) !== 0) {
		usort($alltopics, "sort_by_postdate");
		$t_visits = $fm->_GetCookieArray('t_visits');
		global $f_readed;
		$data = '';
		$alltopics_keys = array_keys($alltopics);
		$pages = Print_Paginator(count($alltopics_keys), 'search.php?action=newposts&p={_P_}', $fm->exbb['topics_per_page'], 8, $first, true);
		$alltopics_keys = array_slice($alltopics_keys, $first, $fm->exbb['topics_per_page']);
		foreach ($alltopics_keys as $topic) {
			$topic = $alltopics[$topic];
			$forum_id = $topic['fid'];
			$topic_id = $topic['id'];
			$f_readed = $fm->_GetCookie('f' . $forum_id, 0);

			$TopicVisitTime = ( isset( $t_visits[$forum_id . ':' . $topic_id] ) && $t_visits[$forum_id . ':' . $topic_id] > $fm->user['last_visit'] ) ? $t_visits[$forum_id . ':' . $topic_id] : $fm->user['last_visit'];
			$topicicon = topic_icon($topic, $TopicVisitTime, isset( $topic['watched'] ) ? $topic['watched'] : true);

			$author = ( $topic['author'] !== false ) ? $topic['author'] : $fm->LANG['Guest'];
			$author = ( $topic['a_id'] !== 0 ) ? '<a href="profile.php?action=show&member=' . $topic['a_id'] . '">' . $author . '</a>' : $author;

			$poster = ( $topic['poster'] !== false ) ? $topic['poster'] : $fm->LANG['Guest'];
			$poster = ( $topic['p_id'] !== 0 ) ? '<a href="profile.php?action=show&member=' . $topic['p_id'] . '">' . $poster . '</a>' : $poster;

			$topicname = ( ( $fm->exbb['watches'] && ( !isset( $topic['watched'] ) || $topic['watched'] ) || !$fm->exbb['watches'] && $fm->user['last_visit'] < $topic['postdate'] && $fm->user['id'] != $topic['p_id'] && $TopicVisitTime < $topic['postdate'] ) ? '<a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&v=u#unread" title="' . $fm->LANG['GoToFirstUnread'] . '"><img src="./templates/' . DEF_SKIN . '/im/unread.gif" border="0" /></a> ' : '' ) . '<a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&v=l#' . $topic['postkey'] . '">' . $fm->chunk_split($topic['name']) . '</a>';
			$topicdesc = $fm->chunk_split($topic['desc']);
			$forumname = '<a href="forums.php?forum=' . $forum_id . '">' . $allforums[$forum_id]['name'] . '</a>';
			$posts = $topic['posts'];
			$postdate = $fm->_DateFormat($topic['postdate'] + $fm->user['timedif'] * 3600);
			include( './templates/' . DEF_SKIN . '/newposts_data.tpl' );
		}
		$found = count($alltopics);
		$fm->_Title = ' :: ' . $fm->LANG['NewPosts'];
		$searchinmessage = '';
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/logos.tpl' );
		include( './templates/' . DEF_SKIN . '/newposts.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
	else {
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['NoNewPosts']);
	}
}

function search() {
	global $fm, $_SEARCH;

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);

	if ($fm->input['action'] === '') {
		$forums = '<option value="-1"> ' . $fm->LANG['INALL'] . "\n";
		$last_cat = -1;
		foreach ($allforums as $forumid => $val) {
			if (stristr($val['catid'], 'f')) {
				continue;
			}
			if (!empty( $allforums[$forumid]['private'] ) && empty( $fm->user['private'][$forumid] ) && !defined('IS_ADMIN') || $allforums[$forumid]['stview'] == 'reged' && !$fm->user['id'] || $allforums[$forumid]['stview'] == 'admo' && !defined('IS_ADMIN') && $fm->user['status'] != 'sm' && !isset( $allforums[$forumid]['moderators'][$fm->user['id']] )) {
				continue;
			}
			if ($val['catid'] != $last_cat) {
				$forums .= '<option value="cat:' . $val['catid'] . '"> ' . $val['catname'] . "\n";
			}
			$forums .= '<option value="' . $forumid . '">-- &nbsp; ' . $val['name'] . "\n";

			foreach ($allforums as $s_id => $s_val) {
				if ($s_val['catid'] == 'f' . $forumid) {
					$forums .= '<option value="' . $s_id . '">---- &nbsp; ' . $s_val['name'] . "\n";
				}
			}
			$last_cat = $val['catid'];
		}
		$fm->_Title = ' :: ' . $fm->LANG['Search'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/logos.tpl' );
		include( './templates/' . DEF_SKIN . '/search.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
	elseif ($fm->input['action'] == 'start') {

		preg_match_all('/([^a-zA-Zа-яА-ЯёЁ]|^)([a-zA-Zа-яА-ЯёЁ]{4,})(?![a-zA-Zа-яА-ЯёЁ])/', $fm->input['search_keywords'], $key_words);
		if (!count($key_words[0])) {
			$fm->_Message($fm->LANG['Search'], $fm->LANG['SEARCHNOPARAM']);
		}
		$key_words = array_unique($key_words[0]);
		if (strstr($fm->input['src_in'], 'cat')) {
			list( $in_where, $in_range ) = explode(':', $fm->input['src_in']);
		}
		else {
			$in_where = 'forum';
			$in_range = $fm->input['src_in'];
		}
		$dir_arr = array();

		if ($in_range == -1) {
			foreach ($allforums as $forum_id => $forum) {
				$dir_arr[$forum_id] = 0;
			}
		}
		elseif ($in_where == 'cat') {
			foreach ($allforums as $forum_id => $forum) {
				if ($in_range == $forum['catid']) {
					$dir_arr[$forum_id] = 0;
				}
			}
		}
		else {
			$dir_arr[$in_range] = 0;
		}

		if (!count($dir_arr)) {
			$fm->_Message($fm->LANG['Search'], $fm->LANG['SEARCHERROR']);
		}

		include( './search/search.php' );

		$wholeword = $querymode = $query_arr = array();
		$_SEARCH['entered_word'] = $fm->input['search_keywords'];
		$_SEARCH['search_keywords'] = $fm->input['search_keywords'];
		$_SEARCH['stype'] = $fm->input['stype'];

		get_query($wholeword, $querymode, $query_arr);

		$_SEARCH['entered_word_arr'] = $query_arr;
		$_SEARCH['query_statistics'] = '';

		$total_found = 0;

		if (count($query_arr) > 0) {
			$_SEARCH['rescount'] = array();
			foreach ($dir_arr as $forum => $trash) {
				if (!empty( $allforums[$forum]['private'] ) && empty( $fm->user['private'][$forum] ) && !defined('IS_ADMIN') || $allforums[$forum]['stview'] == 'reged' && !$fm->user['id'] || $allforums[$forum]['stview'] == 'admo' && !defined('IS_ADMIN') && $fm->user['status'] != 'sm' && !isset( $allforums[$forum]['moderators'][$fm->user['id']] )) {
					continue;
				}

				$allres = array();
				get_results($forum, $wholeword, $querymode, $query_arr, $allres);
				boolean($forum, $query_arr, $querymode, $allres);
				$total_found += ( isset( $_SEARCH['rescount'][$forum] ) ) ? $_SEARCH['rescount'][$forum] : 0;
			}

			if ($total_found) {
				$search_id = $newpassword = substr(uniqid(str_shuffle(session_id()), false), mt_rand(0, 32), 16);
				if ($fp = @fopen(EXBB_DATA_DIR_SEARCH . '/temp/' . $search_id, 'wb')) {
					$fm->_FilePointers[$fp] = $fp;
					$fm->_Write($fp, $_SEARCH);
				}
				$fm->_Message($fm->LANG['SEARCHCOMPLT'], sprintf($fm->LANG['SEARCHRESULT'], $total_found), 'search.php?action=next&search_id=' . $search_id);
			}
			else {
				$fm->_Message($fm->LANG['SEARCHCOMPLT'], $fm->LANG['SEARCHNO']);
			}
		}
		else {
			$fm->_Message($fm->LANG['Search'], $fm->LANG['SEARCHNOPARAM']);
		}
	}
	elseif ($fm->input['action'] == 'next') {
		include( './search/search.php' );
		clear_dir_from_expired_files();
		$search_id = $fm->_String('search_id');

		if (!$search_id || !file_exists(EXBB_DATA_DIR_SEARCH . '/temp/' . $search_id)) {
			$fm->_Message($fm->LANG['Search'], $fm->LANG['SEARCHNOPARAM']);
		}
		$_SEARCH = $fm->_Read(EXBB_DATA_DIR_SEARCH . '/temp/' . $search_id);
		//unset($vars['res']);
		//prints($vars);exit();
		$data = '';

		$t_visits = $fm->_GetCookieArray('t_visits');
		$found = $_found = 0;
		$entered_word = preg_replace('/([^a-zA-Zа-яА-ЯёЁ]|^)([a-zA-Zа-яА-ЯёЁ]{1,3})(?![a-zA-Zа-яА-ЯёЁ])/', '', $_SEARCH['entered_word']);
		$entered_word = preg_replace('/([[:punct:]]+)/', '', $entered_word);
		$entered_word = urlencode($entered_word);
		$fm->input['p'] = abs($fm->_Intval('p', 1));
		foreach ($_SEARCH['res'] as $forum_id => $res) {
			$FINFO = EXBB_DATA_DIR_SEARCH . '/db/' . $forum_id . '_finfo';
			if (!file_exists(EXBB_DATA_DIR_SEARCH . '/db/' . $forum_id . '_finfo')) {
				continue;
			}
			$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');
			$FP_FINFO = fopen($FINFO, "rb");
			$found += $_SEARCH['rescount'][$forum_id];

			$temparray = array();
			for ($i = 0; $i < $_SEARCH['rescount'][$forum_id]; $i++) {
				if ($i >= strlen($_SEARCH['res'][$forum_id]) / 4) {
					break 1;
				}
				$_found++;
				if ($_found <= $fm->exbb['topics_per_page'] * ( $fm->input['p'] - 1 )) {
					continue;
				}
				if ($_found >= $fm->exbb['topics_per_page'] * $fm->input['p'] + 1) {
					break;
				}

				$strpos = unpack("Npos", substr($_SEARCH['res'][$forum_id], $i * 4, 4));

				fseek($FP_FINFO, $strpos['pos'], 0);
				$dum = fgets($FP_FINFO, 100);

				if (isset( $temparray[$dum] )) {
					continue;
				}
				$temparray[$dum] = true;
				list( $f, $t ) = explode('::', $dum);
				$f = trim($f);
				$t = trim($t);

				$f_readed = $fm->_GetCookie('f' . $forum_id, 0);
				$TopicVisitTime = ( isset( $t_visits[$f . ':' . $t] ) && $t_visits[$f . ':' . $t] > $fm->user['last_visit'] ) ? $t_visits[$f . ':' . $t] : $fm->user['last_visit'];
				$topicicon = topic_icon($topic[$t], $TopicVisitTime);

				$author = ( $topic[$t]['author'] !== false ) ? $topic[$t]['author'] : $fm->LANG['Guest'];
				$author = ( $topic[$t]['a_id'] !== 0 ) ? '<a href="profile.php?action=show&member=' . $topic[$t]['a_id'] . '">' . $author . '</a>' : $author;

				$poster = ( $topic[$t]['poster'] !== false ) ? $topic[$t]['poster'] : $fm->LANG['Guest'];
				$poster = ( $topic[$t]['p_id'] !== 0 ) ? '<a href="profile.php?action=show&member=' . $topic[$t]['p_id'] . '">' . $poster . '</a>' : $poster;

				$topicname = '<a href="printpage.php?action=1&forum=' . $f . '&topic=' . $t . '&post=' . $entered_word . '&stype=OR&color=yes">' . $fm->chunk_split($topic[$t]['name']) . '</a>';
				//$topicname	= '<a href="topic.php?forum='.$f.'&topic='.$t.'&v=l#'.$topic[$t]['postkey'].'">'.$fm->chunk_split($topic[$t]['name']).'</a>';
				$topicdesc = $fm->chunk_split($topic[$t]['desc']);
				$forumname = '<a href="forums.php?forum=' . $f . '">' . $allforums[$f]['name'] . '</a>';

				$posts = $topic[$t]['posts'];
				$postdate = $fm->_DateFormat($topic[$t]['postdate'] + $fm->user['timedif'] * 3600);

				include( './templates/' . DEF_SKIN . '/newposts_data.tpl' );
			}
			fclose($FP_FINFO);
			$list = array();
		}
		$pages = Print_Paginator($found, 'search.php?action=next&search_id=' . $fm->input['search_id'] . '&p={_P_}', $fm->exbb['topics_per_page'], 8, $first, true);
		$searchinmessage = sprintf($fm->LANG['YOUSEARCH'], preg_replace('/([^a-zA-Zа-яА-ЯёЁ]|^)([a-zA-Zа-яА-ЯёЁ]{1,3})(?![a-zA-Zа-яА-ЯёЁ])/', '$1<font color="red">$2</font>', $_SEARCH['entered_word']));

		$fm->LANG['NewPosts'] = $fm->LANG['PRINTRESULT'];
		$fm->_Title = ' :: ' . $fm->LANG['Search'] . ' :: ' . $fm->LANG['PRINTRESULT'];
		include( './templates/' . DEF_SKIN . '/all_header.tpl' );
		include( './templates/' . DEF_SKIN . '/logos.tpl' );
		include( './templates/' . DEF_SKIN . '/newposts.tpl' );
		include( './templates/' . DEF_SKIN . '/footer.tpl' );
	}
}

function filterForums($forum) {
	global $fm;

	if (!defined('IS_ADMIN') && $forum['private'] && empty( $fm->user['private'][$forum['id']] )) {
		return false;
	}

	return true;
}

function clear_dir_from_expired_files() {
	$cleardir = EXBB_DATA_DIR_SEARCH . '/temp/';
	$d = dir($cleardir);
	while (false !== ( $file = $d->read() )) {
		if (is_dir($cleardir . '/' . $file)) {
			continue;
		}
		else {
			$clearfile = $cleardir . '/' . $file;
			$lifetime = time() - filemtime($clearfile);
			if ($lifetime >= 3600) {
				unlink($clearfile);
			}
		}
	}
	$d->close();

	return true;
}

function SearchNewPost($var) {
	global $fm;
	if ($var === 'moved') {
		return 0;
	}
	else {
		return ( $var['postdate'] > $fm->user['last_visit'] && $var['p_id'] != $fm->user['id'] ) ? 1 : 0;
	}
}

?>
