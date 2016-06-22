<?php
/****************************************************************************
* ExBB v.1.1                                                              	*
* Copyright (c) 2002-20хх by Alexander Subhankulov aka Warlock            	*
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
$fm->_Intval('c');

if ($fm->_String('action') == 'resetall' && $fm->user['id'] !== 0) {
    mark_board($fm->user['id']);
    exit;
}

$news_data = GetNews(5);

$allforums = array_filter($fm->_Read(FM_ALLFORUMS),"Filter_Cat");

foreach ($allforums as $forum)
	$fm->_GetModerators($forum['id'], $allforums);

$onlinedata = $fm->_OnlineLog($fm->LANG['BoardMain'],FALSE,TRUE);

// Advanced Visit Stats for ExBB FM 1.0 RC1 by yura3d
$todayvisit = FALSE;
if ($fm->exbb['statvisit']) {
	if ($statvisit['day']) today($todayvisit);
	if ($statvisit['numbers']) viewing($statvisit);
}

$t_visits	= $fm->_GetCookieArray('t_visits');
$board_data	= '';
$lastcat	= -1;
$newposts	= 0;
$allforums_keys = array_keys($allforums);
$subforums = array();
require('modules/watches/_index.php');
foreach ($allforums_keys as $key => $id) {
        $forum 		= $allforums[$id];
		$threads 	= $forum['topics'];
		$posts 		= $forum['posts'];
		$category 	= $forum['catname'];
		$in_cat		= $forum['catid'];
		
		
		if (stristr($in_cat, 'f')) {
			$subforums[substr($in_cat, 1, strlen($in_cat) - 1)][$id] = $forum['name'];
			continue;
		}
		elseif ($fm->input['c'] != 0 && $fm->input['c'] != $in_cat) {
			continue;
		}
		
		$sub = array();
		if (isset($subforums[$id]))
			foreach ($subforums[$id] as $subid => $subname) {
				$UnreadFlag = FALSE;
				if ($fm->exbb['watches'] && $fm->user['id'] && $_watchesIndex[$subid][0]) {
					$UnreadFlag = true;
					
					$newposts += $_watchesIndex[$subid][0];
				}
				else if (!$fm->exbb['watches'] && $fm->user['id'] !== 0) {

					$f_readed = $fm->_GetCookie('f'.$subid,0);
					$f_readed = ($f_readed > $fm->user['last_visit']) ? $f_readed : $fm->user['last_visit'];
					if ($allforums[$subid]['last_time'] > $fm->user['last_visit']) {
						$alltopic = $fm->_Read('forum'.$subid.'/list.php');
						if (sizeof($alltopic) > 0) {
							$did = $id;
							$id = $subid;
							$alltopic	= array_filter($alltopic, "NEW_POSTS");
							$id = $did;
							$totalnew	= sizeof($alltopic);
							$UnreadFlag	= ($totalnew >0) ? TRUE:FALSE;
							$newposts += $totalnew;
						}
						unset($alltopic);
					}
				}
				$yes_forumicon	= 'sub_foldernew';
				$no_forumicon	= 'sub_folder';
				
				if ($fm->user['id'] !== 0 && isset($allforums[$subid]['last_time']))
					$folderpicture = ($UnreadFlag === TRUE) ? $yes_forumicon : $no_forumicon;
				else
					$folderpicture = $no_forumicon;
					
				$subinfo = '';
				if ($fm->exbb['sub_main_info']) {
					if (@!$allforums[$subid]['last_post']) $sub_lastpost = $fm->LANG['No'];
					else {
						$sub_lastpost = (strlen($allforums[$subid]['last_post']) > 16) ? substr($allforums[$subid]['last_post'], 0, 15).'...' : $allforums[$subid]['last_post'];
						$sub_lastpost = ($fm->user['id'] && ($fm->exbb['watches'] && $_watchesIndex[$subid][1] || !$fm->exbb['watches'] && ($fm->user['last_visit'] < $allforums[$subid]['last_key'] && $fm->user['id'] != $allforums[$subid]['last_poster_id'] && ((!isset($t_visits[$subid.':'.$allforums[$subid]['last_post_id']]) || $t_visits[$subid.':'.$allforums[$subid]['last_post_id']] < $allforums[$subid]['last_key'])))) ?
							'<a href="topic.php?forum='.$subid.'&topic='.$allforums[$subid]['last_post_id'].'&v=u#unread" title="'.$fm->LANG['GoToFirstUnread'].'"><img src="./templates/'.DEF_SKIN.'/im/unread.gif" border="0" /></a> ' : '') .
							'<a href="topic.php?forum='.$subid.'&topic='.$allforums[$subid]['last_post_id'].'&v=l#'.$allforums[$subid]['last_key'].'" title="'.$allforums[$subid]['last_post'].'">'.$sub_lastpost.'</a>';
						if ($fm->exbb['show_hints']) $sub_lastpost = '<span class="hint">'.$sub_lastpost.'</span>';
					}
					$subinfo = ' '.sprintf($fm->LANG['SubInfo'], $allforums[$subid]['topics'], $allforums[$subid]['posts'], $sub_lastpost);
				}
				
				$sub[] = '<a href="forums.php?forum='.$subid.'" class="'.$folderpicture.'">'.$subname.'</a>'.$subinfo;	
			}
		$sub = ($sub) ? sprintf($fm->LANG['Subforums'], '<br>'.implode('<br>', $sub).'<br>') : '';
		
		// Сколько человек просматривают этот форум?
		$viewing = (isset($statvisit[$id])) ? ' '.sprintf($fm->LANG['Viewing'], $statvisit[$id]) : '';
		
		$fm->_GetModerators($id, $allforums);

		$catrow				= ($forum['catid'] != $lastcat) ? TRUE:FALSE;
		$forumname			= '<a href="forums.php?forum='.$id.'">'.$forum['name'].'</a>';
		$forumdescription	= $forum['desc'];
		if ($fm->_Modoutput) $fm->_Modoutput .= '<br>';
		
		$UnreadFlag = FALSE;
		if ($fm->exbb['watches'] && $fm->user['id'] && $_watchesIndex[$id][0]) {
			$UnreadFlag = true;
			
			$newposts += $_watchesIndex[$id][0];
		}
		else if (!$fm->exbb['watches'] && $fm->user['id'] !== 0) {

			$f_readed = $fm->_GetCookie('f'.$id,0);
			$f_readed = ($f_readed > $fm->user['last_visit']) ? $f_readed : $fm->user['last_visit'];
			if ($forum['last_time'] > $fm->user['last_visit']) {
				$alltopic = $fm->_Read('forum'.$id.'/list.php');
				if (sizeof($alltopic) > 0) {
					$alltopic	= array_filter($alltopic, "NEW_POSTS");
					$totalnew	= sizeof($alltopic);
					$UnreadFlag	= ($totalnew >0) ? TRUE:FALSE;
					$newposts += $totalnew;
				}
				unset($alltopic);
			}
		}

		$yes_forumicon		= ($forum['icon'] != '') ? './im/images/'.$forum['icon']:'./templates/'.DEF_SKIN.'/im/foldernew.gif';
		$no_forumicon		= ($forum['icon'] != '') ? './im/images/no_'.$forum['icon']:'./templates/'.DEF_SKIN.'/im/folder.gif';
		if ($fm->user['id'] !== 0 && isset($forum['last_time'])) {
			$folderpicture = ($UnreadFlag === TRUE) ? '<img src="'.$yes_forumicon.'" border="0">' : '<img src="'.$no_forumicon.'" border="0">';
		} else {
				$folderpicture = '<img src="'.$no_forumicon.'" border="0">';
		}

		$LastTopicDate =  ($forum['last_time'] > 0) ? date("d.m.Y - H:i", $forum['last_time'] + $fm->user['timedif']*3600) : $fm->LANG['NA'];
		
		// Спонсор раздела
		$sponsor = ($fm->exbb['sponsor'] && isset($forum['sponsor'])) ? $forum['sponsor'] : '';

		$lastpost = $LastPosterName = $LastTopicName = '';
		if (isset($forum['last_post'])) {
			if (isset($forum['last_sub'])) $id = $forum['last_sub'];
			$LastTopicName	= (strlen($forum['last_post'])>36) ? substr($forum['last_post'],0,35).'...':$forum['last_post'];
			$LastTopicName  = ($fm->user['id'] && ($fm->exbb['watches'] && $_watchesIndex[$id][1] || !$fm->exbb['watches'] && ($fm->user['last_visit'] < $forum['last_key'] && $fm->user['id'] != $forum['last_poster_id'] && ((!isset($t_visits[$id.':'.$forum['last_post_id']]) || $t_visits[$id.':'.$forum['last_post_id']] < $forum['last_key'])))) ?'<a href="topic.php?forum='.$id.'&topic='.$forum['last_post_id'].'&v=u#unread" title="'.$fm->LANG['GoToFirstUnread'].'"><img src="./templates/'.DEF_SKIN.'/im/unread.gif" border="0" /></a> ' : '<img src="./templates/'.DEF_SKIN.'/im/lastpost.gif"> ') .
			($fm->exbb['show_hints'] ? '<span class="hint">' : '').'<a href="topic.php?forum='.$id.'&topic='.$forum['last_post_id'].'&v=l#'.$forum['last_key'].'" title="'.$forum['last_post'].'">'.$LastTopicName.'</a>'.($fm->exbb['show_hints'] ? '</span>' : '');
			$LastPosterName	= ($forum['last_poster_id'] !== 0) ? $fm->LANG['Author'].': <a href="profile.php?action=show&member='.$forum['last_poster_id'].'">'.$forum['last_poster'].'</a>' : $fm->LANG['Author'].': '.$fm->LANG['Guest'];
		}
		$lastcat = $forum['catid'];
		$last = (!isset($allforums_keys[$key+1]) || $allforums[$allforums_keys[$key+1]]['catid'] != $forum['catid'] ) ? true : false;
		include ('./templates/'.DEF_SKIN.'/board_data.tpl');
}

// СКРЫТЫЙ РЕЖИМ ПРЕБЫВАНИЯ НА ФОРУМЕ //
$countonline  = ($fm->exbb['visiblemode'] === TRUE) ?
						sprintf($fm->LANG['OnlineDataVSHide'],$fm->exbb['membergone'],$fm->_OnlineTotal,$fm->_OnlineGuest,$fm->_Members,$fm->_Invisible):
						sprintf($fm->LANG['OnlineData'],$fm->exbb['membergone'],$fm->_OnlineTotal,$fm->_Members,$fm->_OnlineGuest);
//END Invisible Mode Module
$online_last = $countonline . '';
$maximum = sprintf($fm->LANG['MaxUsers'],$fm->_Stats['max_online']).date("d.m.Y H:i",$fm->_Stats['max_time']+$fm->user['timedif']*3600);

$lastvisit = $fm->_DateFormat($fm->user['last_visit'] + $fm->user['timedif'] * 3600);

$rowspan = 1;
if ($todayvisit) $rowspan++;
$fm->LANG['NewPosts'] = sprintf($fm->LANG['NewPostsTopics'], $newposts);

/* Топ-Лист Пользователей */
include ('modules/userstop/userstop.php');

/* ДЕНЬ РОЖДЕНИЯ */
include ('modules/birstday/birst.php');

// Chat Informer for ExBB FM 1.0 RC2 by yura3d (http://www.exbb.org/)
if ($fm->exbb['chat'])
$fm->_Link .= "\n".'<script type="text/javascript" language="JavaScript">
<!--'."
var ChatLang = {
ChatEmpty: '{$fm->LANG['ChatEmpty']}',
ChatNow: '{$fm->LANG['ChatNow']}',
ChatOnline: '{$fm->LANG['ChatOnline']}',
ChatUpdate: '{$fm->LANG['ChatUpdate']}',
ChatWait: '{$fm->LANG['ChatWait']}'
};
//-->
</script>";

if ($fm->exbb['show_hints'])
$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/hints.js\"></script>
<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/board.js\"></script>
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
include('./templates/'.DEF_SKIN.'/all_header.tpl');
include('./templates/'.DEF_SKIN.'/logos.tpl');
include('./templates/'.DEF_SKIN.'/board_body.tpl');
include('./templates/'.DEF_SKIN.'/footer.tpl');
include('page_tail.php');

/*
	Functions
*/
function mark_board($user_id) {
		global $fm;

if (!$fm->exbb['watches']) {
			$user = $fm->_Read2Write($fp_user,'members/'.$user_id.'.php',FALSE);
			$user['last_visit'] = $fm->_Nowtime;
			$fm->_Write($fp_user,$user);
			unset($user);
			$fm->_setcookie('lastvisit', $fm->_Nowtime);
		}
		else {
			require('modules/watches/_indexMark.php');
		}
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['BoardMarked'],'index.php');
}

function NEW_POSTS($var){
		global $fm, $id, $f_readed, $t_visits;
        $top_id = $id.':'.$var['id'];
        $t_readed = (isset($t_visits[$top_id]) && $t_visits[$top_id] > $f_readed) ? $t_visits[$top_id]:$f_readed;
		return ($var['postdate'] > $t_readed && $var['p_id'] != $fm->user['id']) ?1:0;
}

function Filter_Cat($var) {
		global $fm;

		$_access = TRUE;
		/*if ($fm->input['c'] != 0 && $var['catid'] != $fm->input['c']) {
			$_access = FALSE;		Выборку по категориям сделаем по-другому (см. выше), т.к. фильтр в случае подфорумов не подходит
		}
		else*/if (!defined('IS_ADMIN') && $var['private'] === TRUE) {
			$_access = (isset($fm->user['private'][$var['id']]) && $fm->user['private'][$var['id']] === TRUE) ? TRUE:FALSE;
		}
        return ($_access === TRUE) ?1:0;
}

function GetNews($kolvo) {
global $fm;
$news_data = "";
if ($fm->exbb['announcements'] === TRUE) {
$news = $fm->_Read(FM_NEWS);
if (is_array($news) && count($news)) {
krsort($news);
reset($news);
foreach ($news as $time => $info) {
if (!$kolvo--) break;
$date = date("d.m.Y",($time + $fm->user['timedif'] * 3600));
$title = $info['t'];
$news_data .= '<b>&bull; <a href="announcements.php#'.$time.'">'.$title.'</a></b> ['.$date.']<br />';
}
include('./templates/'.DEF_SKIN.'/news.tpl');
unset($news,$titlenews,$dateposted);
}
} #end announs
return $news_data;
}

function viewing(&$viewing) {
	global $fm, $allforums, $onlinedata;
	
	$viewing = array();
	
	foreach ($onlinedata as $sess => $online) {
		preg_match("#\"(forums|topic)\.php\?forum=([[:alnum:]]+)#is", $online['in'], $where);
		if (isset($where[2]) && isset($allforums[$where[2]])) {
			if (stristr($allforums[$where[2]]['catid'], 'f'))
				$where[2] = substr($allforums[$where[2]]['catid'], 1, strlen($allforums[$where[2]]['catid']) - 1);
			if ($fm->input['c'] != 0 && $fm->input['c'] != $allforums[$where[2]]['catid'])
				continue;
			if (!isset($viewing[$where[2]])) $viewing[$where[2]] = 0;
			$viewing[$where[2]]++;
		}
	}
}

function today(&$todayvisit) {
	global $today, $fm;
	
	$members = $hiddens = 0;
	$was = array();
	if (empty($today['members'])) $today['members'] = array();
	foreach ($today['members'] as $id => $member) {
		switch ($member['s']) {
			case 'ad':	$class = ' class="admin"';
							break;
			case 'sm':	$class = ' class="supmoder"';
							break;
			default:	$class = '';
		}
		
		if ($member['v']) {
			$hiddens++;
			if (defined('IS_ADMIN') || $id == $fm->user['id']) $member['n'] .= '*';
			else continue;
		}
		else $members++;
		
		$was[] = '<a href="profile.php?action=show&member='.$id.'"'.$class.'>'.$member['n'].'</a>';
	}
	
	$todayvisit = sprintf($fm->LANG['TodayVisit'], $today['guests'] + $members + $hiddens, $today['guests'], $members,
		($hiddens) ? sprintf($fm->LANG['HiddensOnline'], $hiddens) : '', ($was) ? ' <span id="sp_todayvisit">(<a href="#" onClick="spoiler(\'_todayvisit\'); return false;">'.$fm->LANG['SpoilerShow'].'</a>)</span><div id="spoiler_todayvisit" style="display: none;">
<span class="admin">'.$fm->LANG['Admin'].'</span>, 
<span class="supmoder">'.$fm->LANG['SuperModer'].'</span>,
<span class="moder">'.$fm->LANG['Moderator'].'</span> ,
'.$fm->LANG['User'].'
<br />
'.implode(', ', $was).'</div>' : '');
}
?>
