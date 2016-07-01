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
$allforums = $fm->_Read(FM_ALLFORUMS);
if (( $forum_id = $fm->_Intval('forum') ) == 0 || !isset( $allforums[$forum_id] )) {
	$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['ForumNotExists']);
}

$privateID = ChekPrivate($allforums[$forum_id]['private'], $forum_id);

$fm->_GetModerators($forum_id, $allforums);
$_moderator = $fm->_Moderator;
$_moderators = $fm->_Moderators;
$_modoutput = $fm->_Modoutput;

CheckForumPerms($allforums[$forum_id]['stview'], 'Views');

if ($fm->user['id'] !== 0 && $fm->input['action'] == 'markall') {
	mark_forum($forum_id);
}

$perms = $fm->LANG['Views' . $allforums[$forum_id]['stview']];
$perms .= $fm->LANG['NewAdd' . $allforums[$forum_id]['stnew']];
$perms .= $fm->LANG['Reply' . $allforums[$forum_id]['strep']];

$category = $allforums[$forum_id]['catname'];
$forumname = $allforums[$forum_id]['name'];
$catid = $allforums[$forum_id]['catid'];


$topics = $fm->_Read('forum' . $forum_id . '/list.php');
$_views = $fm->_Read('forum' . $forum_id . '/views.php');

$to_page = $resetfiltr = $word = '';
if ($fm->_String('filterby') != "" && $fm->_String('word') != '') {
	$word = $fm->input['word'];
	$topics = filtered($word, $topics);
	$resetfiltr = '<a href="forums.php?forum=' . $forum_id . '">' . $fm->LANG['ResetFilter'] . '</a>';
	$to_page = '&filterby=' . $fm->input['filterby'] . '&word=' . $word;
}

// Topics sort
$fm->_Strings(array( 'sort' => 'postdate', 'order' => 'desc' ));
$_sort_columns = array( 'name', 'desc', 'author', 'posts', 'postdate', 'poster' );
if (!in_array($fm->input['sort'], $_sort_columns)) {
	$fm->input['sort'] = 'postdate';
}
switch ($fm->input['sort']) {
	case 'name':
	case 'desc':
	case 'author':
	case 'poster':
		$sort_type = 's';
	break;
	default:
		$sort_type = 'd';
}
$_sort = array( 'column' => $fm->input['sort'], 'type' => $sort_type );
uasort($topics, '_sort');
if ($fm->input['order'] == 'desc') {
	$topics = array_reverse($topics, true);
}
$sorting = '';
foreach ($_sort_columns as $column) {
	$sorting .= '<option value="' . $column . '"' . ( ( $column == $fm->input['sort'] ) ? ' selected="selected"' : '' ) . '> ' . $fm->LANG['SortBy_' . $column];
}
$ordering = '<option value="asc"> ' . $fm->LANG['SortOrderAsc'];
$ordering .= '<option value="desc"' . ( ( $fm->input['order'] == 'desc' ) ? ' selected="selected"' : '' ) . '> ' . $fm->LANG['SortOrderDesc'];
//uasort($topics,"sortByPinnedPostdate");

$keys = $pinned = array();
array_filter($topics, 'pinned');

/* Paginator */
$topictotal = count($keys);
$get_param = 'forums.php?forum=' . $forum_id . '&p={_P_}' . $to_page;
$topicpages = Print_Paginator($topictotal, $get_param, $fm->user['topics2page'] - count($pinned), 6, $first, true);

$keys = array_slice($keys, $first, $fm->user['topics2page'] - count($pinned));
$keys = array_merge($pinned, $keys);

/*Topics last visits array */
$t_visits = $fm->_GetCookieArray('t_visits');
/*Get forum last visits */
$f_readed = $fm->_GetCookie('f' . $forum_id, 0);

$onlinedata = $fm->_OnlineLog($fm->LANG['ViewForum'] . " <a href=\"forums.php?forum={$forum_id}\"><b>" . $allforums[$forum_id]['name'] . '</b></a>', $privateID);

// Advanced Visit Stats for ExBB FM 1.0 RC1 by yura3d
$statviewing = $statforum = false;
if ($fm->exbb['statvisit']) {
	$statviewing = $statvisit['numbers'];
	$statforum = $statvisit['forum'];

	if ($statviewing || $statforum) {
		viewing($statviewing, $statforum);
	}
}

/*	Subforums	*/
$subf = ( stristr($catid, 'f') ) ? substr($catid, 1, strlen($catid) - 1) : 0;
if ($subf) {
	$pcatid = $allforums[$subf]['catid'];
	$pcatname = $allforums[$subf]['catname'];
	$pforumname = $allforums[$subf]['name'];
}
$subforums = '';
$newposts = 0;
$allforums_keys = array_keys(array_filter($allforums, 'filterSub'));
require( 'modules/watches/_forums.php' );
foreach ($allforums_keys as $id) {
	$forum = $allforums[$id];

	$UnreadFlag = false;
	if ($fm->exbb['watches'] && $fm->user['id'] && $_watchesForums[0][$id][0]) {
		$UnreadFlag = true;

		$newposts += $_watchesForums[0][$id][0];
	}
	else {
		if (!$fm->exbb['watches'] && $fm->user['id'] !== 0) {

			$f_readed = $fm->_GetCookie('f' . $id, 0);
			$f_readed = ( $f_readed > $fm->user['last_visit'] ) ? $f_readed : $fm->user['last_visit'];
			if ($forum['last_time'] > $fm->user['last_visit']) {
				$alltopic = $fm->_Read('forum' . $id . '/list.php');
				if (sizeof($alltopic) > 0) {
					$alltopic = array_filter($alltopic, "NEW_POSTS");
					$totalnew = sizeof($alltopic);
					$UnreadFlag = ( $totalnew > 0 ) ? true : false;
					$newposts += $totalnew;
				}
				unset( $alltopic );
			}
		}
	}

	$yes_forumicon = ( $forum['icon'] != '' ) ? './im/images/' . $forum['icon'] : './templates/' . DEF_SKIN . '/im/foldernew.gif';
	$no_forumicon = ( $forum['icon'] != '' ) ? './im/images/no_' . $forum['icon'] : './templates/' . DEF_SKIN . '/im/folder.gif';
	if ($fm->user['id'] !== 0 && isset( $forum['last_time'] )) {
		$folderpicture = ( $UnreadFlag === true ) ? '<img src="' . $yes_forumicon . '" border="0">' : '<img src="' . $no_forumicon . '" border="0">';
	}
	else {
		$folderpicture = '<img src="' . $no_forumicon . '" border="0">';
	}

	$viewing = ( isset( $statviewing[$id] ) ) ? ' ' . sprintf($fm->LANG['Viewing'], $statviewing[$id]) : '';

	$sforumname = '<a href="forums.php?forum=' . $id . '">' . $forum['name'] . '</a>';
	$sforumdescription = $forum['desc'];

	// Спонсор раздела
	$sponsor = ( $fm->exbb['sponsor'] && isset( $forum['sponsor'] ) ) ? $forum['sponsor'] : '';

	$fm->_GetModerators($id, $allforums);

	$threads = $forum['topics'];
	$posts = $forum['posts'];

	$LastTopicDate = ( $forum['last_time'] > 0 ) ? date("d.m.Y - H:i", $forum['last_time'] + $fm->user['timedif'] * 3600) : $fm->LANG['NA'];

	$lastpost = $LastPosterName = $LastTopicName = '';
	if (isset( $forum['last_post'] )) {
		$LastTopicName = ( strlen($forum['last_post']) > 36 ) ? substr($forum['last_post'], 0, 35) . '...' : $forum['last_post'];
		$LastTopicName = ( $fm->user['id'] && ( $fm->exbb['watches'] && $_watchesForums[0][$id][1] || !$fm->exbb['watches'] && ( $fm->user['last_visit'] < $forum['last_key'] && $fm->user['id'] != $forum['last_poster_id'] && ( ( !isset( $t_visits[$id . ':' . $forum['last_post_id']] ) || $t_visits[$id . ':' . $forum['last_post_id']] < $forum['last_key'] ) ) ) ) ? '<a href="topic.php?forum=' . $id . '&topic=' . $forum['last_post_id'] . '&v=u#unread" title="' . $fm->LANG['GoToFirstUnread'] . '"><img src="./templates/' . DEF_SKIN . '/im/unread.gif" border="0" /></a> ' : '<img src="./templates/' . DEF_SKIN . '/im/lastpost.gif"> ' ) . ( $fm->exbb['show_hints'] ? '<span class="hint">' : '' ) . '<a href="topic.php?forum=' . $id . '&topic=' . $forum['last_post_id'] . '&v=l#' . $forum['last_key'] . '" title="' . $forum['last_post'] . '">' . $LastTopicName . '</a>' . ( $fm->exbb['show_hints'] ? '</span>' : '' );
		$LastPosterName = ( $forum['last_poster_id'] !== 0 ) ? $fm->LANG['Author'] . ': <a href="profile.php?action=show&member=' . $forum['last_poster_id'] . '">' . $forum['last_poster'] . '</a>' : $fm->LANG['Author'] . ': ' . $fm->LANG['Guest'];
	}
	include( 'templates/' . DEF_SKIN . '/subforums.tpl' );
	//}
}
$sublist = '';
if ($subforums) {
	$sublist = sprintf($fm->LANG['Subforums'], $forumname);
}

$fm->_Moderator = $_moderator;
$fm->_Moderators = $_moderators;
$fm->_Modoutput = $_modoutput;

$forum_data = '';
foreach ($keys as $id => $topic_id) {
	if (!isset( $topics[$topic_id]['name'] ) || $topics[$topic_id]['name'] == '') {
		$topics[$topic_id]['name'] = $topics[$topic_id]['author'] . date(" d.m.Y H:i", $topics[$topic_id]['date']);
	}
	if (isset( $topics[$topic_id]['tnun'] )) {
		$topics[$topic_id]['name'] .= ' - ' . $topics[$topic_id]['tnun'];
	}

	if ($topics[$topic_id]['state'] == 'moved' && isset( $topics[$topic_id]['movedid'] )) {
		list( $forumid, $topicid ) = explode(":", $topics[$topic_id]['movedid']);
		$topictitle = '<a href="topic.php?forum=' . $forumid . '&topic=' . $topicid . '">' . $fm->chunk_split($topics[$topic_id]['name']) . '</a>';
		if ($fm->_Moderator === true) {
			$topictitle .= ' [<a href="postings.php?action=unlink&forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . ( !$fm->input['p'] ? 1 : $fm->input['p'] ) . '">X</a>]';
		}
		$last_msg = '';
	}
	else {
		$topictitle = '<a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '">' . $fm->chunk_split($topics[$topic_id]['name']) . '</a>';
		$last_msg = '<img src="./templates/' . DEF_SKIN . '/im/lastpost.gif"> ' . ( $fm->exbb['show_hints'] ? '<span class="hint">' : '' ) . '<a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&v=l#' . $topics[$topic_id]['postkey'] . '"> ' . $fm->LANG['LastMsg'] . '</a>' . ( $fm->exbb['show_hints'] ? '</span>' : '' );

	}

	$description = ( isset( $topics[$topic_id]['desc'] ) && !empty( $topics[$topic_id]['desc'] ) ) ? "<br>&nbsp;&nbsp;&raquo;" . $fm->chunk_split($topics[$topic_id]['desc']) : '';

	$posts = $topics[$topic_id]['posts'];
	$views = isset( $_views[$topic_id] ) ? $_views[$topic_id] : 0;

	$author = $topics[$topic_id]['author'] ? $topics[$topic_id]['author'] : $fm->LANG['Guest'];
	$author = ( $topics[$topic_id]['a_id'] != 0 ) ? '<a href="profile.php?action=show&member=' . $topics[$topic_id]['a_id'] . '">' . $author . '</a>' : $author;
	$poster = $topics[$topic_id]['poster'] ? $topics[$topic_id]['poster'] : $fm->LANG['Guest'];
	$poster = ( $topics[$topic_id]['p_id'] != 0 ) ? '<a href="profile.php?action=show&member=' . $topics[$topic_id]['p_id'] . '">' . $poster . '</a>' : $poster;

	$topicstarted = ( $topics[$topic_id]['date'] != 0 ) ? date("d.m.Y", ( $topics[$topic_id]['date'] + ( $fm->user['timedif'] * 3600 ) )) : $fm->LANG['NA'];
	$lastpostdate = ( $topics[$topic_id]['postdate'] != 0 ) ? $fm->_DateFormat($topics[$topic_id]['postdate'] + $fm->user['timedif'] * 3600) : $fm->LANG['NA'];

	$TopicVisitTime = ( isset( $t_visits[$forum_id . ':' . $topic_id] ) && $t_visits[$forum_id . ':' . $topic_id] > $fm->user['last_visit'] ) ? $t_visits[$forum_id . ':' . $topic_id] : $fm->user['last_visit'];
	$topicicon = topic_icon($topics[$topic_id], $TopicVisitTime, ( isset( $_watchesForums[1][$forum_id][$topic_id] ) ) ? $_watchesForums[1][$forum_id][$topic_id] : false);
	$topictitle = ( ( $fm->exbb['watches'] && ( !empty( $_watchesForums[1][$forum_id][$topic_id] ) ) || !$fm->exbb['watches'] && $fm->user['last_visit'] < $topics[$topic_id]['postdate'] && $fm->user['id'] != $topics[$topic_id]['p_id'] && $TopicVisitTime < $topics[$topic_id]['postdate'] ) ? '<a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&v=u#unread" title="' . $fm->LANG['GoToFirstUnread'] . '"><img src="./templates/' . DEF_SKIN . '/im/unread.gif" border="0" /></a> ' : '' ) . $topictitle;


	$uploadicon = ( file_exists('forum' . $forum_id . '/attaches-' . $topic_id . '.php') ) ? '<img src="./templates/' . DEF_SKIN . '/im/upload.gif" border=0 alt="@" title="' . $fm->LANG['FileAttached'] . '"> ' : '';
	$pollicon = ( $topics[$topic_id]['poll'] === true ) ? ' <img src="./templates/' . DEF_SKIN . '/im/poll.gif" width=20 height=20 border="0" alt="' . $fm->LANG['Poll'] . '">' : '';

	$totalposts = $topics[$topic_id]['posts'] + 1;
	$totalpages = ceil($totalposts / intval($fm->user['posts2page']));
	$threadpages = '';
	$pageshow = 5;
	$pagestoshow = '&nbsp;[<a href="printpage.php?forum=' . $forum_id . '&topic=' . $topic_id . '" title="' . $fm->LANG['PrintPage'] . '">#</a>';
	if ($totalpages > 1) {
		$limitupper = ( $totalpages < $pageshow ) ? $totalpages : $pageshow;
		for ($p = 2; $p <= $limitupper; $p++) {
			$threadpages .= '<a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $p . '">' . $p . '</a> ';
		}
		$middlepage = ( $totalpages > $pageshow * 2 ) ? ceil($totalpages / 2) : 0;
		$lastpage = ( $totalpages > $pageshow ) ? $totalpages : 0;
		$middlepage = ( $middlepage ) ? '<a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $middlepage . '">...</a> ' : '';
		$lastpage = ( $lastpage ) ? '<a href="topic.php?forum=' . $forum_id . '&topic=' . $topic_id . '&p=' . $lastpage . '" title="' . $fm->LANG['LastPage'] . '">' . $fm->LANG['LastPage'] . '</a>' : '';
		$pagestoshow .= '&nbsp;' . $fm->LANG['Page'] . '&nbsp;' . $threadpages . ' ' . $middlepage . ' ' . $lastpage;
	}
	$pagestoshow .= ']';

	include( './templates/' . DEF_SKIN . '/forum_data.tpl' );
} # end topic foreach

$options = $markforum = '';
if ($fm->user['id'] !== 0) {
	$emailers = $fm->_Read2Write($fp_f_track, 'forum' . $forum_id . '/_f_track.php');

	$markforum = '<a href="forums.php?action=markall&forum=' . $forum_id . '">' . $fm->LANG['ForumMark'] . '</a>';
	if ($fm->exbb['emailfunctions'] === true && $fm->user['mail']) {
		$options = ( isset( $emailers[$fm->user['id']] ) && $fm->input['action'] != 'untrack' ) ? '<a href="forums.php?action=untrack&forum=' . $forum_id . '">' . $fm->LANG['UnTrackForum'] . '</a>' : '<a href="forums.php?action=track&forum=' . $forum_id . '&p=' . $current_page . '" title="' . $fm->LANG['TrackForumMes'] . '">' . $fm->LANG['TrackForum'] . '</a>';
		if ($fm->input['action'] == 'untrack') {
			unset( $emailers[$fm->user['id']] );
			$fm->_Write($fp_f_track, $emailers);
		}
		elseif ($fm->input['action'] == 'track') {
			$options = '<a href="forums.php?action=untrack&forum=' . $forum_id . '">' . $fm->LANG['UnTrackForum'] . '</a>';
			$emailers[$fm->user['id']] = 1;
			$fm->_Write($fp_f_track, $emailers);
		}
		else {
			$fm->_Fclose($fp_f_track);
		}
		unset( $emailers );
	}
}

$ImgNewTopicButton = '<a href="post.php?action=new&forum=' . $forum_id . '"><img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/newthread.gif" border="0"></a>';
$ImgNewPollButton = ( $allforums[$forum_id]['polls'] === true ) ? '<a href="post.php?action=new&poll=yes&forum=' . $forum_id . '"><img src="./templates/' . DEF_SKIN . '/im/' . DEF_LANG . '/newpoll.gif" border="0"></a>' : '';

$jumphtml = forumjump($allforums);
$fm->_Title = ( $subf ) ? ' :: ' . strip_tags($pcatname) . ' :: ' . strip_tags($pforumname) . ' :: ' . strip_tags($forumname) : ' :: ' . strip_tags($category) . ' :: ' . strip_tags($forumname);
if ($fm->_Modoutput) {
	$fm->_Modoutput = '(' . $fm->_Modoutput . ')';
}

if ($fm->exbb['show_hints']) {
	$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/hints.js\"></script>
<script type=\"text/javascript\" language=\"JavaScript\">
<!--
var LANG = {
	firstText:		'{$fm->LANG['FirstText']}',
	lastText:		'{$fm->LANG['LastText']}',
	firstTitle:		'{$fm->LANG['FirstTitle']}',
	lastTitle: 		'{$fm->LANG['LastTitle']}',
	SpoilerShow: 	'{$fm->LANG['SpoilerShow']}',
	SpoilerHide: 	'{$fm->LANG['SpoilerHide']}'
};
//-->
</script>";
}
if ($allforums[$forum_id]['stview'] == 'all' && !$allforums[$forum_id]['private']) {
	$_SESSION['rd'] = $_SERVER['REQUEST_URI'];
}
include( './templates/' . DEF_SKIN . '/all_header.tpl' );
include( './templates/' . DEF_SKIN . '/logos.tpl' );
include( './templates/' . DEF_SKIN . '/forum_body.tpl' );
include( './templates/' . DEF_SKIN . '/footer.tpl' );
include( 'page_tail.php' );

/*
	Functions
*/
function filterSub($forum) {
	global $fm, $forum_id, $catid;

	if (( !stristr($catid, 'f') ) && ( stristr($forum['catid'], 'f') ) && ( $forum_id == substr($forum['catid'], 1, strlen($forum['catid']) - 1) )) {
		if (@$forum['private'] && empty( $fm->user['private'][$forum['id']] ) && !defined('IS_ADMIN')) {
			return false;
		}

		return true;
	}

	return false;
}

function _sort($a, $b) {
	global $fm, $_sort;

	if ($_sort['type'] == 's') {
		if ($fm->_RuLocale) {
			return strcasecmp($a[$_sort['column']], $b[$_sort['column']]);
		}
		else {
			return strcmp($fm->_StrToLower($a[$_sort['column']]), $fm->_StrToLower($b[$_sort['column']]));
		}
	}
	else {
		if ($_sort['type'] == 'd') {
			return $a[$_sort['column']] - $b[$_sort['column']];
		}
	}
}

function pinned($list) {
	global $keys, $pinned;

	if (!empty( $list['pinned'] )) {
		$pinned[] = $list['id'];
	}
	else {
		$keys[] = $list['id'];
	}
}

function mark_forum($forumid) {
	global $fm;
	if (!$fm->exbb['watches']) {
		$fm->_setcookie('f' . $forumid, $fm->_Nowtime);
	}
	else {
		require( 'modules/watches/_forumsMark.php' );
	}

	$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['ForumMarked'], 'forums.php?forum=' . $forumid);
}

function filtered($word, $topics) {
	global $fm;
	$res = array();
	switch ($fm->input['filterby']) {
		case 'topdesc':
			$field = 'desc';
		break;
		case 'author':
			$field = 'author';
		break;
		default:
			$field = 'name';
		break;
	}

	$word = $fm->_LowerCase($word);

	foreach ($topics as $id => $info) {
		if (isset( $info[$field] ) && preg_match("/$word/i", $fm->_LowerCase($info[$field]))) {
			$res[$id] = $info;
		}
	}

	return $res;
}

function sortByPinnedPostdate($a, $b) {
	if ($a['postdate'] < $b['postdate']) {
		return 1;
	}
	else {
		if ($a['postdate'] > $b['postdate']) {
			return -1;
		}
	}

	return 0;
}

function NEW_POSTS($var) {
	global $fm, $id, $f_readed, $t_visits;
	$top_id = $id . ':' . $var['id'];
	$t_readed = ( isset( $t_visits[$top_id] ) && $t_visits[$top_id] > $f_readed ) ? $t_visits[$top_id] : $f_readed;

	return ( $var['postdate'] > $t_readed && $var['p_id'] != $fm->user['id'] ) ? 1 : 0;
}

function viewing(&$statviewing, &$statforum) {
	global $onlinedata, $allforums, $forum_id, $fm;

	$viewing = false;
	$showonline = array();

	$guests = $members = $hiddens = 0;

	foreach ($onlinedata as $sess => $online) {
		if ($statviewing) {
			preg_match("#\"(forums|topic)\.php\?forum=([[:alnum:]]+)#is", $online['in'], $where);
			if (@$allforums[$where[2]]['catid'] == 'f' . $forum_id) {
				if (!isset( $viewing[$where[2]] )) {
					$viewing[$where[2]] = 0;
				}
				$viewing[$where[2]]++;
			}
		}

		if ($statforum) {
			if (preg_match("#\"(forums|topic)\.php\?forum=" . $forum_id . "(\"|\&)#is", $online['in'])) {
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
	}

	if ($statforum) {
		$showonline = ( ( $showonline ) ? ' &raquo; ' : '' ) . implode(' &raquo; ', $showonline);
		$showonline = sprintf($fm->LANG['ForumOnline'], $guests + $members + $hiddens, $guests, $members, ( $fm->exbb['visiblemode'] ) ? sprintf($fm->LANG['HiddensOnline'], $hiddens) : '', $showonline);
	}

	$statviewing = $viewing;
	$statforum = $showonline;
}

?>
