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
$fm->_LoadLang('setforums', true);

if ($fm->input['action'] == "addcat" || $fm->input['action'] == "addforum") {
	$catid = $fm->_String('catid');

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, false);
	array_filter($allforums, 'GET_CATID');

	ksort($allforums);
	reset($allforums);
	end($allforums);
	$forum_id = key($allforums) + 1;

	$safe_mode = ( ini_get('safe_mode') ) ? sprintf($fm->LANG['SafeModeCat'], 'forum' . $forum_id) : '';

	if ($fm->input['action'] == "addcat") {
		$hidden = '<input type=hidden name="action" value="doaddcat">';
		$cathtml = '<input class="post" type=text size=40 name="catname" value="">';
		$do = $fm->LANG['NewForumCat'];
		$button = $fm->LANG['CatForumCreate'];
	}
	else {
		$cathtml = categories($categories, $catid);
		$hidden = '<input type=hidden name="action" value="doaddforum">';
		$do = $fm->LANG['NewForum'];
		$button = $fm->LANG['ForumCreate'];
	}

	$codes_on = $private_off = $access2view_all = $access2new_all = $access2reply_all = 'selected';
	$codes_off = $polls_off = $private_on = $access2view_reged = $access2view_no = $access2reply_reged = $access2reply_no = $access2new_reged = $access2new_no = '';
	$polls_on = 'checked="checked"';
	$upsize = $forumname = $forumdescription = $forummoderator = $forumgraphic = $sponsor = '';
	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/addforum.tpl' );
	include( './admin/footer.tpl' );
}
elseif ($fm->input['action'] == "doaddcat" || $fm->input['action'] == "doaddforum") {
	$MessageTitle = ( $fm->input['action'] == "doaddcat" ) ? $fm->LANG['NewForumCat'] : $fm->LANG['NewForum'];
	$MessageText = ( $fm->input['action'] == "doaddcat" ) ? $fm->LANG['NewCatForumAddedOk'] : $fm->LANG['NewForumAddedOk'];

	if ($fm->_String('forumname') == '') {
		$fm->_Message($MessageTitle, $fm->LANG['ForumNameNotEntered'], '', 1);
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	$categories = array();
	array_filter($allforums, 'GET_CATID');

	ksort($allforums);
	reset($allforums);
	end($allforums);
	$forum_id = key($allforums) + 1;

	if ($fm->input['action'] == "doaddforum") {
		$catid = $fm->_String('catid');
		$subforum = ( stristr($catid, 'f') ) ? substr($catid, 1, strlen($catid) - 1) : 0;

		if (( !$subforum ) && ( !isset( $categories[$catid] ) ) || ( ( $subforum ) && ( !isset( $allforums[$subforum] ) ) )) {
			$fm->_Fclose($fp_allforums);
			$fm->_Message($fm->LANG['NewForum'], $fm->LANG['CatNotExists'], '', 1);
		}
		$catname = ( !$subforum ) ? $categories[$catid] : "";
		$count = 0;
		foreach ($allforums as $id => $forum) {
			if ($forum['catid'] == $catid && $count < $forum['position']) {
				$count = $forum['position'];
			}
		}
		$count++;
		$catid2 = ( $subforum ) ? 0 : $catid;
		$position = $count;
	}
	else {
		if ($fm->_String('catname') == '') {
			$fm->_Fclose($fp_allforums);
			$fm->_Message($fm->LANG['NewForumCat'], $fm->LANG['CatNameNotEntered'], '', 1);
		}
		ksort($categories);
		reset($categories);
		end($categories);
		$catid = key($categories) + 1;
		$catname = $fm->input['catname'];
		$position = intval($catid . '01');
	}


	$dirtomake = EXBB_DATA_DIR_FORUMS.'/' . $forum_id;

	mkdir($dirtomake, $fm->exbb['ch_dirs']);
	@chmod($dirtomake, $fm->exbb['ch_dirs']);


	$htaccess = "AuthUserFile /dev/null\nAuthGroupFile /dev/null\nAuthName DenyViaWeb\nAuthType Basic\n\n\n\n<Limit GET>\norder allow,deny\ndeny from all\n</Limit>";
	$html = "<html><head><title>No access</title></head>\n<body>No access allowed</body></html>\n";
	New_File($dirtomake . '/.htaccess', $htaccess);
	New_File($dirtomake . '/index.html', $html);
	make_moderators();

	$allforums[$forum_id]['catname'] = $fm->html_replace($catname);
	$allforums[$forum_id]['catid'] = $catid;
	$allforums[$forum_id]['name'] = $fm->html_replace($fm->input['forumname']);
	$allforums[$forum_id]['id'] = $forum_id;
	$allforums[$forum_id]['desc'] = $fm->html_replace($fm->_String('forumdescription'));
	$allforums[$forum_id]['posts'] = 0;
	$allforums[$forum_id]['topics'] = 0;
	$allforums[$forum_id]['position'] = $position;
	$allforums[$forum_id]['stview'] = $fm->input['access2view'];
	$allforums[$forum_id]['stnew'] = $fm->input['access2new'];
	$allforums[$forum_id]['strep'] = $fm->input['access2reply'];
	$allforums[$forum_id]['moderator'] = $fm->input['forummoderator'];
	$allforums[$forum_id]['private'] = $fm->_Boolean($fm->input, 'privateforum');
	$allforums[$forum_id]['codes'] = $fm->_Boolean($fm->input, 'codestate');
	$allforums[$forum_id]['polls'] = $fm->_Boolean($fm->input, 'polls');
	$allforums[$forum_id]['icon'] = ( preg_match("#^[A-Za-z0-9-_]{1,16}\.[A-Za-z]{3,4}$#is", $fm->input['forumgraphic']) && file_exists('im/images/' . $fm->input['forumgraphic']) ) ? $fm->input['forumgraphic'] : '';
	$allforums[$forum_id]['upload'] = ( $fm->_Intval('upsize') !== 0 ) ? $fm->input['upsize'] * 1024 : 0;
	$allforums[$forum_id]['last_time'] = 0;

	// Спонсор раздела
	if ($fm->exbb['sponsor']) {
		$allforums[$forum_id]['sponsor'] = $fm->html_replace($fm->_String('sponsor'));
	}

	//uasort($allforums,'sort_by_catid');
	uasort($allforums, 'sort_by_position');
	$fm->_Write($fp_allforums, $allforums);

	$fm->_WriteLog($MessageTitle, 1);
	$fm->_Message($MessageTitle, $MessageText, 'setforums.php' . ( ( stristr($catid, 'f') ) ? '?subforum=' . substr($catid, 1, strlen($catid) - 1) : '' ), 1);
}
elseif ($fm->input['action'] == "edit") {
	$forum_id = $fm->_Intval('forum');
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, false);
	if (!isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['AdminEditForum'], $fm->LANG['ForumNotExists'], '', 1);
	}

	$categoryplace = $catid = $allforums[$forum_id]['catid'];
	$pcatid = ( stristr($catid, 'f') ) ? substr($catid, 1, strlen($catid) - 1) : 0;
	$cathtml = ( $pcatid ) ? $allforums[$pcatid]['catname'] . ' :: ' . $allforums[$pcatid]['name'] : $allforums[$forum_id]['catname'];

	$forummoderator = $allforums[$forum_id]['moderator'];
	$forummoderator = array_values($forummoderator);
	$forummoderator = implode(',', $allforums[$forum_id]['moderator']);
	$forumgraphic = $allforums[$forum_id]['icon'];

	$forumname = htmlspecialchars($allforums[$forum_id]['name'], ENT_COMPAT, 'windows-1251');
	$forumdescription = htmlspecialchars($allforums[$forum_id]['desc'], ENT_COMPAT, 'windows-1251');
	$do = $fm->LANG['EditForum'];

	$codes_on = ( $allforums[$forum_id]['codes'] ) ? 'selected' : '';
	$codes_off = ( !$allforums[$forum_id]['codes'] ) ? 'selected' : '';
	$polls_on = ( $allforums[$forum_id]['polls'] ) ? 'checked="checked"' : '';
	$polls_off = ( !$allforums[$forum_id]['polls'] ) ? 'checked="checked"' : '';
	$private_on = ( $allforums[$forum_id]['private'] ) ? 'selected' : '';
	$private_off = ( !$allforums[$forum_id]['private'] ) ? 'selected' : '';
	$access2view_all = ( $allforums[$forum_id]['stview'] == 'all' ) ? 'selected' : '';
	$access2view_reged = ( $allforums[$forum_id]['stview'] == 'reged' ) ? 'selected' : '';
	$access2view_no = ( $allforums[$forum_id]['stview'] == 'admo' ) ? 'selected' : '';
	$access2new_all = ( $allforums[$forum_id]['stnew'] == 'all' ) ? 'selected' : '';
	$access2new_reged = ( $allforums[$forum_id]['stnew'] == 'reged' ) ? 'selected' : '';
	$access2new_no = ( $allforums[$forum_id]['stnew'] == 'admo' ) ? 'selected' : '';
	$access2reply_all = ( $allforums[$forum_id]['strep'] == 'all' ) ? 'selected' : '';
	$access2reply_reged = ( $allforums[$forum_id]['strep'] == 'reged' ) ? 'selected' : '';
	$access2reply_no = ( $allforums[$forum_id]['strep'] == 'admo' ) ? 'selected' : '';
	$upsize = $allforums[$forum_id]['upload'] / 1024;
	$sponsor = ( $fm->exbb['sponsor'] && isset( $allforums[$forum_id]['sponsor'] ) ) ? htmlspecialchars($allforums[$forum_id]['sponsor'], ENT_COMPAT, 'windows-1251') : '';
	$button = $fm->LANG['Save'];
	$safe_mode = '';
	$hidden = '<input type="hidden" name="action" value="doedit">
					<input type="hidden" name="forum" value="' . $forum_id . '">';

	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/addforum.tpl' );
	include( './admin/footer.tpl' );
}
elseif ($fm->input['action'] == "doedit") {
	$forum_id = $fm->_Intval('forum');

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	if (!isset( $allforums[$forum_id] )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['AdminEditForum'], $fm->LANG['ForumNotExists'], '', 1);
	}

	if ($fm->_String('forumname') == '') {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['AdminEditForum'], $fm->LANG['ForumNameNotEntered'], '', 1);
	}
	copy(EXBB_DATA_FORUMS_LIST, EXBB_DATA_FORUMS_LIST_BACKUP);
	@chmod(EXBB_DATA_FORUMS_LIST_BACKUP, $fm->exbb['ch_files']);

	make_moderators();

	$allforums[$forum_id]['name'] = $fm->html_replace($fm->input['forumname']);
	$allforums[$forum_id]['id'] = $forum_id;
	$allforums[$forum_id]['desc'] = $fm->html_replace($fm->_String('forumdescription'));
	$allforums[$forum_id]['stview'] = $fm->input['access2view'];
	$allforums[$forum_id]['stnew'] = $fm->input['access2new'];
	$allforums[$forum_id]['strep'] = $fm->input['access2reply'];
	$allforums[$forum_id]['moderator'] = $fm->input['forummoderator'];
	$allforums[$forum_id]['private'] = $fm->_Boolean($fm->input, 'privateforum');
	$allforums[$forum_id]['codes'] = $fm->_Boolean($fm->input, 'codestate');
	$allforums[$forum_id]['polls'] = $fm->_Boolean($fm->input, 'polls');
	$allforums[$forum_id]['icon'] = ( preg_match("#^[A-Za-z0-9-_]{1,16}\.[A-Za-z]{3,4}$#is", $fm->input['forumgraphic']) && file_exists('im/images/' . $fm->input['forumgraphic']) ) ? $fm->input['forumgraphic'] : '';
	$allforums[$forum_id]['upload'] = ( $fm->_Intval('upsize') !== 0 ) ? $fm->input['upsize'] * 1024 : 0;

	// Спонсор раздела
	if ($fm->exbb['sponsor']) {
		$allforums[$forum_id]['sponsor'] = $fm->html_replace($fm->_String('sponsor'));
	}

	$fm->_Write($fp_allforums, $allforums);

	$fm->_WriteLog($fm->LANG['AdminEditForum'], 1);

	$catid = $allforums[$forum_id]['catid'];
	$redir = 'setforums.php';
	if (stristr($catid, 'f')) {
		$redir .= '?subforum=' . substr($catid, 1, strlen($catid) - 1);
	}

	$fm->_Message($fm->LANG['AdminEditForum'], $fm->LANG['ForumEditOk'], $redir, 1);
}
elseif ($fm->input['action'] == "editcatname") {
	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	array_filter($allforums, 'GET_CATID');
	if (( $catid = $fm->_String('catid') ) == '' || !isset( $categories[$catid] ) || stristr($catid, 'f')) {
		$fm->_Fclose($fp_allforums);
		header('Location: setforums.php' . ( ( stristr($catid, 'f') ) ? '?subforum=' . substr($catid, 1, strlen($catid) - 1) : '' ));
		//$fm->_Message($fm->LANG['AdminCatNameEdit'],$fm->LANG['CatNotExists'],'',1);
	}

	if ($fm->_String('doedit') != '') {
		if ($fm->_String('catname') == '') {
			$fm->_Fclose($fp_allforums);
			$fm->_Message($fm->LANG['AdminCatNameEdit'], $fm->LANG['CatNameNotEntered'], '', 1);
		}

		foreach ($allforums as $id => $forum) {
			if ($catid == $forum['catid']) {
				$allforums[$id]['catname'] = $fm->html_replace($fm->input['catname']);
			}
		}
		$fm->_Write($fp_allforums, $allforums);

		$fm->_WriteLog($fm->LANG['AdminCatNameEdit'], 1);
		$fm->_Message($fm->LANG['AdminCatNameEdit'], $fm->LANG['NewCatNameEditOk'], 'setforums.php', 1);
	}
	else {
		$fm->_Fclose($fp_allforums);
		$categoryname = htmlspecialchars($categories[$catid], ENT_COMPAT, 'windows-1251');
		include( './admin/all_header.tpl' );
		include( './admin/nav_bar.tpl' );
		include( './admin/edit_catname.tpl' );
	}
}
elseif ($fm->input['action'] == "delcat" || $fm->input['action'] == "delete" || $fm->input['action'] == "moveforum") {
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, false);
	$returncat = true;
	array_filter($allforums, 'GET_CATID');
	switch ($fm->input['action']) {
		case 'delcat':
			$forum_id = $fm->_String('catid');
			$TableTitle = $fm->LANG['AdminCatDelete'];
			if ($forum_id == '' || !isset( $categories[$forum_id] ) || stristr($forum_id, 'f')) {
				header('Location: setforums.php' . ( ( stristr($forum_id, 'f') ) ? '?subforum=' . substr($forum_id, 1, strlen($forum_id) - 1) : '' ));
				//$fm->_Message($TableTitle,$fm->LANG['CatNotExists'],'',1);
			}
			$action = 'dodelcat';
			$ButtonValue = $fm->LANG['CatDelete'];
			$RequestText = $fm->LANG['RequestYourAction'];
		break;
		case 'delete':
			$forum_id = $fm->_Intval('forum');
			$TableTitle = $fm->LANG['AdminForumDelete'];
			if ($forum_id == 0 || !isset( $allforums[$forum_id] )) {
				$fm->_Message($TableTitle, $fm->LANG['ForumNotExists'], '', 1);
			}
			$action = 'dodelforum';
			$ButtonValue = $fm->LANG['ForumDelete'];
			$RequestText = $fm->LANG['RequestYourAction'];
		break;
		case 'moveforum':
			$forum_id = $fm->_Intval('forum');
			$TableTitle = $fm->LANG['MoveForumRestore'];
			if ($forum_id == 0 || !isset( $allforums[$forum_id] )) {
				$fm->_Message($TableTitle, $fm->LANG['ForumNotExists'], '', 1);
			}
			$warning = ( count($catarray) == 1 ) ? $fm->LANG['MoveWarning'] : '';
			$catid = $fm->_String('catid');
			$action = 'domoveforum';
			$ButtonValue = $fm->LANG['MoveForum'];
			$RequestText = $fm->LANG['MoveContents'] . categories($categories, $catid, true, $forum_id) . $warning;
		break;
	}
	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/del_forum.tpl' );
	include( './admin/footer.tpl' );
}
elseif ($fm->input['action'] == "dodelcat") {
	$allforums = $foreach = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	array_filter($allforums, 'GET_CATID');
	if (( $catid = $fm->_Intval('forum') ) == 0 || !isset( $categories[$catid] )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['AdminCatDelete'], $fm->LANG['CatNotExists'], '', 1);
	}

	$todelete = $private = array();
	$delposts = $deltopics = 0;

	foreach ($foreach as $id => $forum) {
		if ($forum['catid'] == $catid || stristr($forum['catid'], 'f') && ( $pforum = substr($forum['catid'], 1, strlen($forum['catid']) - 1) ) && $allforums[$pforum]['catid'] == $catid) {
			if (DelForum($id) === false) {
				$todelete[] = "<b>forum$id</b>";
			}
			if ($forum['catid'] == $catid) {
				$delposts += $forum['posts'];
				$deltopics += $forum['topics'];
			}
			if ($allforums[$id]['private'] === true) {
				$private[$id] = 1;
			}
			unset( $allforums[$id] );
		}
	}
	unset( $foreach );
	$fm->_SAVE_STATS(array( 'totalposts' => array( $delposts, -1 ), 'totalthreads' => array( $deltopics, -1 ) ));
	$fm->_Write($fp_allforums, $allforums);
	$needremove = ( count($todelete) > 0 ) ? sprintf($fm->LANG['MustRemove'], implode(", ", $todelete)) : '';
	updateUsersPrivate($private);
	$fm->_WriteLog($fm->LANG['AdminCatDelete'], 1);
	$fm->_Message($fm->LANG['AdminCatDelete'], $fm->LANG['ForumDeleteOk'] . $needremove, 'setforums.php', 1);
}
elseif ($fm->input['action'] == "dodelforum") {
	$forum_id = $fm->_Intval('forum');
	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);

	$redir = 'setforums.php';
	$catid = $allforums[$forum_id]['catid'];
	if (stristr($catid, 'f')) {
		$redir .= '?subforum=' . substr($catid, 1, strlen($catid) - 1);
	}

	if (!isset( $allforums[$forum_id] )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['AdminForumDelete'], $fm->LANG['ForumNotExists'], '', 1);
	}
	else {
		$private = array();
		$needremove = '';
		$d_posts = $d_topics = 0;

		foreach ($allforums as $id => $forum) {
			if ($forum['catid'] == 'f' . $forum_id || $forum_id == $id) {
				if ($allforums[$id]['private'] === true) {
					$private[$id] = 1;
				}
				$needremove .= ( DelForum($id) === false ) ? sprintf($fm->LANG['MustRemove'], "<b>$forum_id</b><br>") : '';
				if ($forum_id == $id) {
					$d_posts += $forum['posts'];
					$d_topics += $forum['topics'];
				}
				unset( $allforums[$id] );
			}
		}
	}

	$fm->_SAVE_STATS(array( 'totalposts' => array( $d_posts, -1 ), 'totalthreads' => array( $d_topics, -1 ) ));

	$fm->_Write($fp_allforums, $allforums);

	updateUsersPrivate($private);
	$fm->_WriteLog($fm->LANG['AdminForumDelete'], 1);
	$fm->_Message($fm->LANG['AdminForumDelete'], $fm->LANG['ForumDeleteOk'] . $needremove, $redir, 1);
}
elseif ($fm->input['action'] == "domoveforum") {

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	if (( $forum_id = $fm->_Intval('forum') ) == 0 || !isset( $allforums[$forum_id] )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['MoveForumRestore'], $fm->LANG['ForumNotExists'], '', 1);
	}
	if (( !$catid = $fm->_String('catid') )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['MoveForumRestore'], $fm->LANG['CatNotExists'], '', 1);
	}

	$redir = 'setforums.php';
	if (stristr($catid, 'f')) {
		$redir .= '?subforum=' . substr($catid, 1, strlen($catid) - 1);
	}

	$returncat = $catid;
	array_filter($allforums, 'GET_CATID');
	@ksort($catarray);

	@$newposition = end($catarray) + 1;
	@$allforums[$forum_id]['catname'] = $categories[$catid];
	$allforums[$forum_id]['catid'] = $catid;
	$allforums[$forum_id]['position'] = $newposition;
	uasort($allforums, 'sort_by_position');
	$fm->_Write($fp_allforums, $allforums);
	$fm->_WriteLog($fm->LANG['MoveForumRestore'], 1);
	$fm->_Message($fm->LANG['MoveForumRestore'], $fm->LANG['MoveContentsOk'], $redir, 1);
}
elseif ($fm->input['action'] == "stat") {
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, false);

	$totalposts = 0;
	$totalthreads = 0;
	foreach ($allforums as $id => $forum) {
		if (stristr($forum['catid'], 'f')) {
			continue;
		}
		$totalposts += $forum['posts'];
		$totalthreads += $forum['topics'];
	}
	$fm->_BOARDSTATS();
	$fm->LANG['NowStat'] = sprintf($fm->LANG['NowStat'], $fm->_Stats['totalposts'], $fm->_Stats['totalthreads'], $totalposts, $totalthreads);
	$fm->_SAVE_STATS(array( 'totalposts' => array( $totalposts, 0 ), 'totalthreads' => array( $totalthreads, 0 ) ));
	$fm->_WriteLog($fm->LANG['AdminForumStats'], 1);
	$fm->_Message($fm->LANG['AdminForumStats'], $fm->LANG['NowStat'], 'setforums.php', 1);
}
elseif ($fm->input['action'] == "recount") {
	$forum_id = $fm->_Intval('forum');

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);

	if (!isset( $allforums[$forum_id] )) {
		$fm->_Fclose($fp_allforums);
		$fm->_Message($fm->LANG['ForumRecount'], $fm->LANG['ForumNotExists'], '', 1);
	}

	$redir = 'setforums.php';
	$catid = $allforums[$forum_id]['catid'];
	if (stristr($catid, 'f')) {
		$redir .= '?subforum=' . substr($catid, 1, strlen($catid) - 1);
	}

	$topics = array();
	$postscount = 0;
	$topiccount = 0;
	$last_time = $last_sub = 0;

	$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php', false);
	$topiccount = count($list);

	if (!stristr($allforums[$forum_id]['catid'], 'f')) {
		foreach ($allforums as $id => $forum) {
			if ($forum['catid'] == 'f' . $forum_id) {
				$topiccount += $forum['topics'];
				$postscount += $forum['posts'];
				if ($forum['last_time'] >= $last_time) {
					$last_poster = @$forum['last_poster'];
					$last_poster_id = @$forum['last_poster_id'];
					$last_post = @$forum['last_post'];
					$last_id = @$forum['last_post_id'];
					$last_key = @$forum['last_key'];
					$last_time = @$forum['last_time'];
					$last_sub = $id;
				}
			}
		}
	}

	if ($topiccount > 0) {
		foreach ($list as $id => $topic) {
			$postscount += $topic['posts'];
		}
		uasort($list, "sort_by_postdate");
		@$topic = reset($list);
		while (@$topic['state'] == 'moved') {
			next($list);
			@$topic = key($list);
			$topiccount--;
		}
		$last_post_id = key($list);

		$allforums[$forum_id]['topics'] = $topiccount;
		$allforums[$forum_id]['posts'] = $postscount;
		if ($last_time < @$list[$last_post_id]['postdate']) {
			@$allforums[$forum_id]['last_poster'] = $list[$last_post_id]['poster'];
			@$allforums[$forum_id]['last_poster_id'] = $list[$last_post_id]['p_id'];
			@$allforums[$forum_id]['last_post'] = $list[$last_post_id]['name'];
			@$allforums[$forum_id]['last_post_id'] = $last_post_id;
			@$allforums[$forum_id]['last_key'] = $list[$last_post_id]['postkey'];
			@$allforums[$forum_id]['last_time'] = $list[$last_post_id]['postdate'];
			unset( $allforums[$forum_id]['last_sub'] );
		}
		else {
			@$allforums[$forum_id]['last_poster'] = $last_poster;
			@$allforums[$forum_id]['last_poster_id'] = $last_poster_id;
			@$allforums[$forum_id]['last_post'] = $last_post;
			@$allforums[$forum_id]['last_post_id'] = $last_id;
			@$allforums[$forum_id]['last_key'] = $last_key;
			@$allforums[$forum_id]['last_time'] = $last_time;
			@$allforums[$forum_id]['last_sub'] = $last_sub;
		}
	}
	else {
		$allforums[$forum_id]['topics'] = $topiccount;
		$allforums[$forum_id]['posts'] = $postscount;
	}
	$fm->_Write($fp_allforums, $allforums);
	$fm->_Message($fm->LANG['ForumRecount'], sprintf($fm->LANG['ForumRecountOk'], $topiccount, $postscount), $redir, 1);
}
elseif ($fm->input['action'] == "restore") {
	$forum_id = $fm->_Intval('forum');

	$forum_dir = EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/';
	if (!is_dir($forum_dir)) {
		$fm->_Message($fm->LANG['ForumRestore'], sprintf($fm->LANG['WrongForumDir'], $forum_id), '', 1);
	}

	$redir = 'setforums.php';
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, 0);
	$catid = $allforums[$forum_id]['catid'];
	if (stristr($catid, 'f')) {
		$redir .= '?subforum=' . substr($catid, 1, strlen($catid) - 1);
	}

	$views_data = $fm->_Read2Write($fp_views, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/views.php');
	$fm->_Read2Write($fp_list, EXBB_DATA_DIR_FORUMS . '/' . $forum_id . '/list.php');

	$list = array();
	$dir = dir($forum_dir);
	while (false !== ( $file = $dir->read() )) {
		if (preg_match("#(\d{1,5})-thd\.php$#is", $file, $match)) {
			$topic_id = $match[1];
			$topic_data = $fm->_Read($forum_dir . $topic_id . '-thd.php', false);
			if (count($topic_data) === 0) {
				unlink($forum_dir . $topic_id . '-thd.php');
				continue;
			}
			ksort($topic_data, SORT_NUMERIC);
			$firstpost = reset($topic_data);
			$date = key($topic_data);
			$lastpost = end($topic_data);
			$postdate = key($topic_data);

			$topic_name = $topic_data[$date]['name'];
			$topic_a_id = ( isset( $firstpost['p_id'] ) ) ? $firstpost['p_id'] : 0;
			$topic_p_id = ( isset( $lastpost['p_id'] ) ) ? $lastpost['p_id'] : 0;

			$list[$topic_id]['name'] = $firstpost['name'];
			$list[$topic_id]['id'] = $topic_id;
			$list[$topic_id]['fid'] = $forum_id;
			$list[$topic_id]['desc'] = ( isset( $firstpost['desc'] ) ) ? $firstpost['desc'] : '';
			$list[$topic_id]['state'] = ( isset( $firstpost['state'] ) ) ? $firstpost['state'] : 'open';
			$list[$topic_id]['pinned'] = ( isset( $firstpost['pinned'] ) ) ? $firstpost['pinned'] : false;
			$list[$topic_id]['posts'] = count($topic_data) - 1;
			$list[$topic_id]['author'] = ( ( $autor = Member_Name($topic_a_id) ) !== false ) ? $autor : false;
			$list[$topic_id]['a_id'] = $topic_a_id;
			$list[$topic_id]['date'] = $date;
			$list[$topic_id]['poster'] = ( ( $poster = Member_Name($topic_p_id) ) !== false ) ? $poster : false;
			$list[$topic_id]['p_id'] = $topic_p_id;
			$list[$topic_id]['postdate'] = $postdate;
			$list[$topic_id]['postkey'] = $postdate;
			$views_data[$topic_id] = ( isset( $firstpost['views'] ) ) ? $firstpost['views'] : 0;
			if ($list[$topic_id]['state'] == 'moved') {
				$list[$topic_id]['movedid'] = $firstpost['movedid'];
			}
			$list[$topic_id]['poll'] = isset_poll($forum_id, $topic_id);
			unset( $topic_data, $firstpost, $lastpost, $date, $postdate );
		}
	}
	$dir->close();
	uasort($list, 'sort_by_postdate');
	$fm->_Write($fp_list, $list);
	$fm->_Write($fp_views, $views_data);

	$restored = count($list);
	unset( $list, $views_data );
	$fm->LANG['ForumRestoreOk'] = ( $restored > 0 ) ? sprintf($fm->LANG['ForumRestoreOk'], $restored) : $fm->LANG['ForumRestoreDone'];
	$fm->_WriteLog($fm->LANG['ForumRestore'], 1);
	$fm->_Message($fm->LANG['ForumRestore'], $fm->LANG['ForumRestoreOk'], $redir, 1);
}
elseif ($fm->input['action'] == "catorder") {
	$tomove = $fm->_Intval('move');
	$catid = $fm->_String('catid');
	$redir = ( !stristr($catid, 'f') ) ? 'setforums.php' : 'setforums.php?subforum=' . substr($catid, 1, strlen($catid) - 1);
	$catid = intval($catid);
	if ($catid == 1 && $tomove == -1) {
		Header('Location: ' . $redir);
		exit;
	}

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	array_filter($allforums, 'GET_CATID');
	if ($catid == 0 || !isset( $categories[$catid] ) || count($categories) == 1) {
		$fm->_Fclose($fp_allforums);
		Header('Location: ' . $redir);
		exit;
	}

	ksort($categories);
	$keys = array_keys($categories);
	$lastcatid = end($keys);
	$curcat_key = array_search($catid, $keys);

	if ($catid == $lastcatid && $tomove == 1) {
		$fm->_Fclose($fp_allforums);
		Header('Location: ' . $redir);
		exit;
	}
	$newcatid = ( $tomove == 1 ) ? $keys[$curcat_key + 1] : $keys[$curcat_key - 1];
	$allforums = array_map("CatReOrder", $allforums);
	uasort($allforums, 'sort_by_position');
	$fm->_Write($fp_allforums, $allforums);
	Header('Location: ' . $redir);
	exit;
}
elseif ($fm->input['action'] == "forumorder") {
	$tomove = $fm->_Intval('move');
	$catid = $fm->_String('catid');
	$forum_id = $fm->_Intval('forum');
	$returncat = $catid;

	$redir = ( !stristr($catid, 'f') ) ? 'setforums.php' : 'setforums.php?subforum=' . substr($catid, 1, strlen($catid - 1));

	$allforums = $fm->_Read2Write($fp_allforums, EXBB_DATA_FORUMS_LIST, false);
	array_filter($allforums, 'GET_CATID');
	if ($catid == '' /*|| !isset($categories[$catid])*/ || count($catarray) == 1) {
		$fm->_Fclose($fp_allforums);
		Header('Location: ' . $redir);
		exit;
	}
	$num = 0;
	foreach ($catarray as $key => $value) {
		$num++;
		$cur_pos = ( $num < 10 ) ? intval($catid) . '0' . $num : intval($catid) . $num;
		$allforums[$key]['position'] = intval($cur_pos);
	}
	asort($catarray, SORT_NUMERIC);

	$keys = array_keys($catarray);
	$first_id = reset($keys);
	$last_id = end($keys);
	$curid_key = array_search($forum_id, $keys);

	if ($forum_id == $first_id && $tomove == -1) {
		$fm->_Fclose($fp_allforums);
		Header('Location: ' . $redir);
		exit;
	}

	if ($forum_id == $last_id && $tomove == 1) {
		$fm->_Fclose($fp_allforums);
		Header('Location: ' . $redir);
		exit;
	}

	$newpos_id = ( $tomove == 1 ) ? $keys[$curid_key + 1] : $keys[$curid_key - 1];
	$old_position = $allforums[$forum_id]['position'];
	$new_position = $allforums[$newpos_id]['position'];

	$allforums[$forum_id]['position'] = $new_position;
	$allforums[$newpos_id]['position'] = $old_position;
	uasort($allforums, 'sort_by_position');
	$fm->_Write($fp_allforums, $allforums);
	Header('Location: ' . $redir);
	exit;
}
elseif ($fm->input['action'] == "searchindex") {
	$forum_id = $fm->_Intval('forum');

	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);

	if (!isset( $allforums[$forum_id] )) {
		$fm->_Message($fm->LANG['ForumIndexing'], $fm->LANG['ForumNotExists'], '', 1);
	}

	$forum_dir = 'forum' . $forum_id;

	include( 'search/search.php' );

	#DEFINE CONSTANTS
	$indexed_total = 0;
	$cwn = 0;
	$kbcount = 0;

	if (!ini_get('safe_mode') && !is_dir('search/db')) {
		mkdir('search/db', $fm->exbb['ch_dirs']);
		@chmod('search/db', $fm->exbb['ch_dirs']);
	}
	else {
		if (!is_dir('search/db')) {
			$fm->_Message($fm->LANG['ForumIndexing'], sprintf($fm->LANG['SafeModeCat'], 'search/db'), '', 1);
		}
	}

	$HASH = 'search/db/' . $fm->input['forum'] . '_hash';
	$HASHWORDS = 'search/db/' . $fm->input['forum'] . '_hashwords';
	$FINFO = 'search/db/' . $fm->input['forum'] . '_finfo';
	$SITEWORDS = 'search/db/' . $fm->input['forum'] . '_sitewords';
	$WORD_IND = 'search/db/' . $fm->input['forum'] . '_word_ind';

	$fp_FINFO = fopen($FINFO, 'w');
	fwrite($fp_FINFO, "\n");

	$fp_SITEWORDS = fopen($SITEWORDS, 'wb');
	$fp_WORD_IND = fopen($WORD_IND, 'wb');

	$words = array();

	scan_files($forum_dir, $fm->input['forum']);

	$pos_sitewords = ftell($fp_SITEWORDS);
	$pos_word_ind = ftell($fp_WORD_IND);
	$to_print_sitewords = "";
	$to_print_word_ind = "";

	foreach ($words as $word => $value) {
		$cwn++;
		$words_word_dum = pack("NN", $pos_sitewords + strlen($to_print_sitewords), $pos_word_ind + strlen($to_print_word_ind));
		$to_print_sitewords .= $word . "\x0A";
		$to_print_word_ind .= pack("N", strlen($value) / 4) . $value;
		$words[$word] = $words_word_dum;

		if (strlen($to_print_word_ind) > 32000) {
			fwrite($fp_SITEWORDS, $to_print_sitewords);
			fwrite($fp_WORD_IND, $to_print_word_ind);

			$to_print_sitewords = "";
			$to_print_word_ind = "";

			$pos_sitewords = ftell($fp_SITEWORDS);
			$pos_word_ind = ftell($fp_WORD_IND);
		}
	}

	fwrite($fp_SITEWORDS, $to_print_sitewords);
	fwrite($fp_WORD_IND, $to_print_word_ind);

	fclose($fp_SITEWORDS);
	fclose($fp_WORD_IND);
	fclose($fp_FINFO);

	@chmod($SITEWORDS, $fm->exbb['ch_files']);
	@chmod($WORD_IND, $fm->exbb['ch_files']);
	@chmod($FINFO, $fm->exbb['ch_files']);

	build_hash();
	//print "$indexed_total files are indexed. Totalsize of indexed files -> $kbcount kB<br><br>\n";

	$fm->_WriteLog($fm->LANG['ForumIndexing'], 1);

	$redir = 'setforums.php';
	$catid = $allforums[$forum_id]['catid'];
	if (stristr($catid, 'f')) {
		$redir .= '?subforum=' . substr($catid, 1, strlen($catid) - 1);
	}
	$fm->_Message($fm->LANG['ForumIndexing'], sprintf($fm->LANG['ForumIndexingOk'], $indexed_total), $redir, 1);
}
else {
	$subforum = $fm->_Intval('subforum');
	$subforums = array();
	$highest = 0;
	$forum_data = "";
	$safe_mode = ( ini_get('safe_mode') ) ? $fm->LANG['SafeModeOn'] : '';
	if ($subforum) {
		$fm->LANG['CatAddNew'] = $fm->LANG['ForumAddNew'] = $fm->LANG['AddNewSub'];
	}
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, false);
	//prints($allforums);
	$lastcategoryplace = -1;
	foreach ($allforums as $forumid => $forum) {
		if (( stristr($forum['catid'], 'f') ) && ( !$subforum )) {
			$pid = substr($forum['catid'], 1, strlen($forum['catid']) - 1);
			if (!isset( $subforums[$pid] )) {
				$subforums[$pid] = 1;
			}
			else {
				$subforums[$pid]++;
			}
			continue;
		}
		if (( $subforum ) && ( $forum['catid'] != 'f' . $subforum )) {
			continue;
		}
		if (( $subforum ) && ( $forum['catid'] == 'f' . $subforum )) {
			$forum['catname'] = '&lt;- ' . $allforums[$subforum]['name'];
		}
		$fm->_GetModerators($forumid, $allforums);
		$catrow = ( $forum['catid'] != $lastcategoryplace ) ? true : false;
		$private = ( $forum['private'] ) ? $fm->LANG['Private'] : '';
		$sf = sprintf($fm->LANG['Subforums'], ( isset( $subforums[$forumid] ) ) ? $subforums[$forumid] : 0);
		include( './admin/forumlist_data.tpl' );
		$lastcategoryplace = $forum['catid'];
		if ($forum['catid'] > $highest) {
			$highest = $forum['catid'];
		}
	}
	$highest++;
	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/forumlist.tpl' );
	include( './admin/footer.tpl' );
}
include( 'page_tail.php' );

/*
	Functions
*/
function categories($allcats, $catid, $move = false, $forum_id = 0) {
	global $fm;
	$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST, 0);
	$sub = array();
	$cathtml = '<select name="catid">';
	$lastcat = -1;
	foreach ($allforums as $id => $forum) {
		if ($ch = stristr($forum['catid'], 'f')) {
			$sub[] = substr($forum['catid'], 1, strlen($forum['catid']) - 1);
		}
		if (/*($move === TRUE && $forum['catid'] == $catid) ||*/
		( $ch )
		) {
			continue;
		}
		$selected = ( $forum['catid'] == $catid ) ? ' selected' : '';
		if ($lastcat != $forum['catid']) {
			$cathtml .= '<option value="' . $forum['catid'] . '"' . $selected . '>' . $forum['catname'] . "</option>\n";
		}
		$selected = ( $id == substr($catid, 1, strlen($catid) - 1) ) ? ' selected' : '';
		if (( ( $move === true ) && ( stristr($catid, 'f') ) && ( $id != substr($catid, 1, strlen($catid) - 1) ) ) || ( $move === true && !stristr($catid, 'f') && !in_array($forum_id, $sub) && $forum_id != $id ) || ( $move === false )) {
			$cathtml .= '<option value="f' . $id . '"' . $selected . '>-- ' . $forum['name'] . '</a>';
		}
		$lastcat = $forum['catid'];
	}
	$cathtml .= '</select>';

	return $cathtml;
}

function GET_CATID($n) {
	global $categories, $catforums, $returncat, $catarray, $catid;
	$categories[$n['catid']] = $n['catname'];

	if ($n['catid'] == $catid) {
		$catforums[$n['id']] = $n['name'];
	}
	if ($n['catid'] == $returncat) {
		$catarray[$n['id']] = $n['position'];
	}

	return 0;
}

function New_File($filename, $somecontent) {
	$handle = fopen($filename, 'w');
	fwrite($handle, $somecontent);
	fclose($handle);
	@chmod($filename, 0644);
}

function make_moderators() {
	global $fm;

	$moderators = str_replace(', ', ',', $fm->input['forummoderator']);
	$moderators = str_replace(' ,', ',', $moderators);
	$moderators = array_flip(explode(',', $fm->_LowerCase($moderators)));
	$allusers = $fm->_Read(EXBB_DATA_USERS_LIST, false);

	$fm->input['forummoderator'] = array();
	$count = count($moderators);
	if ($count > 0) {
		$i = 0;
		foreach ($allusers as $id => $info) {
			if (isset( $moderators[$info['n']] ) && ( $name = Member_Name($id) ) !== false) {
				$fm->input['forummoderator'][$id] = $name;
				unset( $moderators[$info['n']] );
				$i++;
			}
			if ($i == $count) {
				break;
			}
		}
	}
	unset( $moderators, $allusers, $count, $i );

	return;
}

function Member_Name($id) {
	global $fm;
	$member_data = $fm->_Getmember($id);
	if ($member_data !== false) {
		$name = $member_data['name'];
		unset( $member_data );

		return $name;
	}
	else {
		return false;
	}
}

function DelForum($forum_id) {
	global $fm;
	$dirtoremove = 'forum' . $forum_id . '/';
	if (is_dir($dirtoremove)) {
		$d = dir($dirtoremove);
		while (false !== ( $file = $d->read() )) {
			if (is_dir($file)) {
				continue;
			}
			elseif (preg_match("#^attaches-\d+.php$#is", $file)) {
				$attachdata = $fm->_Read($dirtoremove . $file, false);
				foreach ($attachdata as $id => $att) {
					$attachfile = 'uploads/' . $att['id'];
					if (file_exists($attachfile)) {
						unlink($attachfile);
					}
				}
			}
			unlink($dirtoremove . $file);
		}
		$d->close();
	}
	if (!ini_get('safe_mode')) {
		rmdir($dirtoremove);

		return true;
	}
	else {
		return false;
	}
}

function CatReOrder($n) {
	global $forum_id, $newpos_id, $newcatid, $catid;
	if ($n['catid'] == $catid) {
		$n['position'] = preg_replace("#^" . $catid . "(\d{2,4})$#is", "$newcatid$1", $n['position']);
		$n['catid'] = $newcatid;
	}
	elseif ($n['catid'] == $newcatid) {
		$n['position'] = preg_replace("#^" . $newcatid . "(\d{2,4})$#is", "$catid$1", $n['position']);
		$n['catid'] = $catid;
	}

	return $n;
}

function updateUsersPrivate($private) {
	global $fm;
	@set_time_limit(360);

	if (!is_array($private) || count($private) <= 0) {
		return false;
	}

	$users = array();
	$dirtoread = 'members/';
	$d = dir($dirtoread);
	while (false !== ( $file = $d->read() )) {
		$writeFILE = false;
		if (preg_match("#^([0-9]+)\.php$#is", $file)) {
			$userinfo = $fm->_Read2Write($fp_user, $dirtoread . $file);
			if (isset( $userinfo['private'] ) && is_array($userinfo['private']) && count($userinfo['private']) > 0) {
				foreach ($private as $forumID => $flag) {
					if (isset( $userinfo['private'][$forumID] )) {
						unset( $userinfo['private'][$forumID] );
						$writeFILE = true;
					}
				}
			}
			if ($writeFILE === true) {
				$fm->_Write($fp_user, $userinfo);
			}
			else {
				$fm->_Fclose($fp_user);
			}

		}
	}
	$d->close();
}

function sort_by_catid($a, $b) {
	if ($a['catid'] == $b['catid']) {
		return 0;
	}

	return ( $a['catid'] < $b['catid'] ) ? -1 : 1;
}

function sort_by_position($a, $b) {
	if ($a['position'] == $b['position']) {
		return 0;
	}

	return ( $a['position'] < $b['position'] ) ? -1 : 1;
}

?>
