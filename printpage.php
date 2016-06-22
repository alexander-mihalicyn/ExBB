<?php
/****************************************************************************
* ExBB v.1.1                                                              	*
* Copyright (c) 2002-20õõ by Alexander Subhankulov aka Warlock            	*
*                                                                         	*
* http://www.exbb.net                                             			*
* email: admin@exbb.net                                           			*
*                                                                         	*
****************************************************************************/
/****************************************************************************
*                                                                         	*
*   This program is free software; you can redistribute it and/or modify  	*
*   it under the terms of the GNU General Public License as published by  	*
*   the Free Software Foundation; either version 2 of the License, or     	*
*   (at your option) any later version.                                   	*
*                                                                         	*
****************************************************************************/
define('IN_EXBB', TRUE);
include('./include/common.php');

$fm->_GetVars();
$fm->_String('action');
$fm->_LoadLang('forums');

$allforums	= $fm->_Read(FM_ALLFORUMS);
if (($topic_id = $fm->_Intval('topic')) === 0 || ($forum_id = $fm->_Intval('forum')) === 0 || !isset($allforums[$forum_id])) {
	$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost']);
}
$fm->_GetModerators($forum_id, $allforums);
CheckForumPerms($allforums[$forum_id]['stview'],'Views');

$privateID	= ChekPrivate($allforums[$forum_id]['private'],$forum_id);

$list = $fm->_Read('forum'.$forum_id.'/list.php');
if (!isset($list[$topic_id]) || !file_exists('forum'.$forum_id.'/'.$topic_id.'-thd.php')) {
	$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['TopicMiss']);
}

$catname	= $allforums[$forum_id]['catname'];
$forumname 	= $allforums[$forum_id]['name'];
$cat_id 	= $allforums[$forum_id]['catid'];
$forumcodes = ($fm->exbb['exbbcodes'] === TRUE && $allforums[$forum_id]['codes'] === TRUE) ? TRUE:FALSE;
$cur_topic	= $list[$topic_id];

unset($allforums,$list);

$topicname = $cur_topic['name'];

$topic = $fm->_Read('forum'.$forum_id.'/'.$topic_id.'-thd.php');

$fm->_Boolean($fm->input,'color');
$query_arr = FALSE;
if ($fm->input['action'] !== '') {
	@set_time_limit(600);

	$query_arr = get_query();
	if (sizeof($query_arr) == 0) {
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['SearchNoParams']);
	}

	$topic		= in_topic($forum_id,$topic_id,$topic);
	$topictotal = count($topic);
	if ($topictotal == 0) {
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['SearchNotFound']);
	}

	$founds		= $fm->LANG['SearchTotalFound'].$topictotal;
	$g_post		= ($fm->input['post'] !== '') ? '&amp;post='.urlencode($fm->input['post']):"";
	$g_stype	= '&amp;stype='.$fm->input['stype'];
	$g_user		= ($fm->_String('user') !== '') ? '&amp;user='.urlencode($fm->input['user']):"";
	$g_color	= ($fm->input['color'] === TRUE) ? '&amp;color=yes':"";
	$get_param	= 'printpage.php?action=1&amp;forum='.$forum_id.'&amp;topic='.$topic_id.$g_post.$g_stype.$g_user.$g_color.'&amp;p={_P_}';
} else {
		$founds = "&nbsp;";
		$topictotal = count($topic);
		$get_param = 'printpage.php?forum='.$forum_id.'&amp;topic='.$topic_id.'&amp;p={_P_}';
}

$pages = Print_Paginator($topictotal,$get_param,$fm->user['posts2page'],8,$first,TRUE);
ksort($topic,SORT_NUMERIC);

$keys   = array_slice(array_keys($topic),$first,$fm->user['posts2page']);

$names = array();
$names[0] = $fm->LANG['Guest'];

$print_data ='';
foreach ($keys as $key) {
		$first++;
		$autor_id = isset($topic[$key]['p_id']) ? $topic[$key]['p_id'] : 0;

		if (!isset($names[$autor_id])) {
			$names[$autor_id] = GetName($autor_id);
		}
		$autorname		= $names[$autor_id];
		$date			= ' - '.$fm->_DateFormat($key + $fm->user['timedif']*3600);
		$showsmiles		= $topic[$key]['smiles'];
		$html			= $topic[$key]['html'];
		$post 			= ($forumcodes === TRUE) ? $fm->formatpost($topic[$key]['post'],$html,$showsmiles):$topic[$key]['post'];

		if (is_array($query_arr) && $query['mode'] == 'post' && $fm->input['color'] === TRUE){
			$post = preg_replace("/(".implode("|",$query_arr).")/i",'<font>$1</font>',$post);
		}
		include('./templates/'.DEF_SKIN.'/print_data.tpl');
}

$fm->_Title = ($fm->input['action'] !== '') ? ' :: '.$fm->LANG['SearchInTopic']:' :: '.$fm->LANG['PrintPage'];
$fm->_Title .= ' :: '.$topicname;
$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/board.js\"></script>
 <script type=\"text/javascript\" language=\"JavaScript\">
 var LANG = {
 Spoiler: '{$fm->LANG['Spoiler']}',
 SpoilerShow: '{$fm->LANG['SpoilerShow']}',
 SpoilerHide: '{$fm->LANG['SpoilerHide']}'
 };
 </script>";
if ($current_page > 1) {
	$fm->_Title .= " [$current_page]";
}

$fm->_OnlineLog($fm->LANG['TopicSee'].' <a href="topic.php?forum='.$forum_id.'&topic='.$topic_id.'"><b>'.$topicname.'</b></a> - <a href="forums.php?forum='.$forum_id.'"><b>'.$forumname.'</b></a>',$privateID);
include('./templates/'.DEF_SKIN.'/printpage.tpl');
include('page_tail.php');

function in_topic($forum_id,$topic_id,$topic){
		global $fm, $query_str,$user_id,$query,$query_arr;

		if ($query['mode'] == 'post'){
			$query_arr = preg_replace('/([[:punct:]]+)/', '', $query_arr);
			$query_str = ($query['type'] == 'AND') ? implode(".*",$query_arr):implode("|",$query_arr);
			$result = array_filter($topic, "post");
		} else {
				$result = ($user_id == 0) ? array():array_filter($topic, "user");
		}
		return $result;
}

function get_query() {
		global $fm,$query,$user_id,$user;

		$query = array();
		$query['mode'] = 'post';

		if ($fm->_String('post') === '' && $fm->_String('user') === '') {
			return array();
		}

		if ($fm->input['post'] !== '') {
			$query['data'] = $fm->input['post'];
			$query['type'] = $fm->input['stype'];
		} else {
				$query['data']	= $fm->input['user'];
				$query['mode']	= 'poster';
				$allusers		= $fm->_Read(FM_USERS);
				$user			= $fm->_LowerCase($query['data']);
				$allusers		= array_filter($allusers, "search_user_id");
				$user_id	= (sizeof($allusers) != 0)? key($allusers):0;
				unset($allusers);
		}

		$query_arr_dum = array();
		switch ($query['mode']) {
			case 'post':	$query_arr_dum		= preg_split("/[\s,\.]+/",$query['data']);
							break;
			case 'poster':	$query_arr_dum[]	= trim($query['data']);
							break;
		}

		$query_arr = array_filter($query_arr_dum, "strlen_word");
		unset($query_arr_dum,$word);
		return $query_arr;
}

/*
	Filters
*/
function post($var){
		global $query_str;
		
		return (preg_match("/(".$query_str.")/i",$var['post'])) ? 1:0;
}

function user($var){
		global $user_id;
		return ($var['p_id'] == $user_id) ? 1:0;
}

function search_user_id($var){
		global $user;
		return ($var['n'] == $user) ? 1:0;
}

function strlen_word($var){
		return (strlen($var) > 3) ? 1:0;
}
/*
	End filters
*/
?>
