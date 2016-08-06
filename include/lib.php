<?php
/****************************************************************************
 * ExBB v.1.7                                                                *
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
if (!defined('IN_EXBB')) {
	die( 'Hack attempt!' );
}

ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . dirname(__FILE__));

include dirname(__DIR__).'/core/bootstrap.php';

require_once( 'vars.class.php' );
require_once( 'fm.class.php' );

// Advanced internal redirect by yura3d (http://www.exbb.org/)
function get_rd($sess_id = 0) {
	$rd = ( !empty( $_SESSION['rd'] ) ) ? $_SESSION['rd'] : 'index.php';

	if (!$sess_id) {
		return $rd;
	}

	preg_match('#([^\?]+|)(\?([^\#]+)|)(\#(.*)|)#s', $rd, $rd);

	if ($rd[3] === '') {
		$rd[3] = '?' . _SESSION_ID;
	}
	else {
		$rd[3] = $rd[2] . '&' . _SESSION_ID;
	}

	if (empty($rd[5])) {
		$rd[5] = '';
	}

	if ($rd[5] !== '') {
		$rd[5] = $rd[4];
	}

	return $rd = $rd[1] . $rd[3] . $rd[5];
}

function MAP_MAIL($n) {
	global $skip_mails, $usersmails;
	preg_match("#(@.+)$#isu", $n['m'], $match);

	if (!empty($match[1]) && !isset( $skip_mails[$match[1]] )) {
		$usersmails[] = $n['m'];
	}

	return 0;
}

function SkipMails() {
	function clear_skip($n) {
		global $skip_mails;
		$n = trim($n);
		if ($n != '') {
			$skip_mails[$n] = 1;
		}

		return 0;
	}

	$skipeds = ( file_exists(EXBB_DATA_SKIP_MAILS) ) ? file(EXBB_DATA_SKIP_MAILS) : array();
	unset( $skipeds[0] );
	array_filter($skipeds, 'clear_skip');
	unset( $skipeds );
}

function isset_poll($f_id, $poll_id) {
	global $fm;
	$pollfile = EXBB_DATA_DIR_FORUMS . '/' . $f_id . '/' . $poll_id . '-poll.php';
	if (!file_exists($pollfile)) {
		return false;
	}
	$poldata = $fm->_Read($pollfile, false);

	return ( count($poldata) === 0 ) ? false : true;
}

function Banned($n) {
	global $fm;

	return ( preg_match("#^" . $n['regexp'] . "$#", $fm->_IP) );
}

function php($code) {
	global $fm, $html;

	if ($html === false) {
		$code[1] = $fm->html_replace($code[1]);
	}
	$code = trim($code[1]);

	$code = preg_replace("#^<code><font color=\"\#000000\">\s(.+?)<\/font>\s<\/code>$#is", "$1", highlight_string($code, true));
	$array = array( "&nbsp;&nbsp;&nbsp;" => " [%__%] ", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" => " [%__%] [%__%] ", "&nbsp;&nbsp;" => " [%__%]", "&nbsp;" => " " );

	$code = strtr($code, $array);
	$code = str_replace("[%__%]", "&nbsp;", $code);
	$code = preg_replace("#([^\s]+)(,)([^\s]+)#is", "$1, $3", $code);
	//$code = str_replace("&nbsp;&nbsp;&nbsp;", " [%__%] ",$code);
	//$code = str_replace("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", " [%__%] [%__%] ",$code);
	//$code = str_replace("&nbsp;&nbsp;", " [%__%]",$code);
	//$code = str_replace("&nbsp;", " ",$code);
	//$code = str_replace("[%__%]", "&nbsp;",$code);
	return '<br><b>PHP:</b><div class="phpcode">' . $code . '</div>';
}

function forumjump($allforums) {
	global $fm;

	$sub = array();
	$last_cat = -1;
	$options = '<option value="index.php">' . $fm->LANG['ForumJump'] . '</option>';
	foreach ($allforums as $forumid => $forum) {
		$_access = true;
		if (!defined('IS_ADMIN') && $forum['private'] === true) {
			$_access = ( isset( $fm->user['private'][$forum['id']] ) && $fm->user['private'][$forum['id']] === true ) ? true : false;
		}
		if (mb_stristr($forum['catid'], 'f')) {
			$sub[] = $forumid;
			continue;
		}
		if ($_access === true) {
			if ($forum['catid'] != $last_cat) {
				$options .= '<option value="' . $fm->exbb['boardurl'] . "/index.php\">\n";
				$options .= '<option value="' . $fm->exbb['boardurl'] . '/index.php?c=' . $forum['catid'] . '"> ' . $forum['catname'] . "\n";
				$options .= '<option value="' . $fm->exbb['boardurl'] . '/forums.php?forum=' . $forumid . '" target="_self">-- &nbsp; ' . $forum['name'] . "\n";
			}
			else {
				$options .= '<option value="' . $fm->exbb['boardurl'] . '/forums.php?forum=' . $forumid . '" target="_self">-- &nbsp; ' . $forum['name'] . "\n";
			}
			foreach ($allforums as $id => $sforum) {
				if (mb_stristr($sforum['catid'], 'f') && $forumid == mb_substr($sforum['catid'], 1, mb_strlen($sforum['catid']) - 1) && in_array($id, $sub)) {
					$options .= '<option value="' . $fm->exbb['boardurl'] . '/forums.php?forum=' . $id . '" target="_self">---- &nbsp; ' . $sforum['name'] . "\n";
				}
			}
		}
		$last_cat = $forum['catid'];
	}

	return '
<SCRIPT LANGUAGE="JavaScript">
<!--
function JumpTo(){
		var URL = document.jump.jumpto.options[document.jump.jumpto.selectedIndex].value;
		top.location.href = URL;
		target = "_self";
}
// -->
</SCRIPT>
<form action="forums.php" method="post" name="jump">
	<select name="jumpto" onchange="JumpTo()">
		' . $options . '
	</select>
</form>';
}

function Print_Paginator($total, $get, $per, $links, &$first, $dots = false) {
	global $fm, $current_page;

	$p = $fm->_Intval('p');

	///////////////////
	$total_pages = ceil($total / $per);
	$current_page = ( $p <= 0 ) ? 1 : ( ( $p <= $total_pages ) ? $p : $total_pages );
	$first = ( $current_page <= 1 ) ? 0 : ( $current_page - 1 ) * $per;
	////////////////
	$paginator = $fm->LANG['TotalPages'] . '(' . $total_pages . '): ';
	$pagesarray = array();
	$pos = floor($links / 2);
	$links = $pos * 2 + 1;

	$go_start = $go_finish = "";
	if ($total_pages > $links) {
		$go_start = ( $current_page - $pos <= 1 ) ? '' : ' <a href="' . str_replace("{_P_}", '1', $get) . '" title="' . $fm->LANG['ToStart'] . '">' . $fm->LANG['ToStart'] . '</a> ';
		$go_finish = ( $current_page + $pos >= $total_pages ) ? '' : ' <a href="' . str_replace("{_P_}", $total_pages, $get) . '" title="' . $fm->LANG['ToEnd'] . '">' . $fm->LANG['ToEnd'] . '</a> ';
	}

	$prev = ( $current_page == 1 ) ? '' : '<a href="' . str_replace("{_P_}", ( $current_page - 1 ), $get) . '" title="' . $fm->LANG['PrevPage'] . '">&laquo;</a> ';
	$next = ( $current_page == $total_pages ) ? '' : ' <a href="' . str_replace("{_P_}", ( $current_page + 1 ), $get) . '" title="' . $fm->LANG['NextPage'] . '">&raquo;</a>';
	$dots_start = null;
	$dots_finish = null;
	if ($dots === true && $total_pages > $links) {
		$dots_start = ( $current_page - $pos <= 1 ) ? '' : '... ';
		$dots_finish = ( $current_page + $pos >= $total_pages ) ? '' : ' ...';
	}

	$start_links = ( $total_pages > $links ) ? ( ( $current_page - $pos <= 1 ) ? 1 : ( ( $current_page + $pos >= $total_pages ) ? $total_pages - $pos * 2 : $current_page - $pos ) ) : 1;
	$finish_links = ( $total_pages > $links ) ? ( ( $current_page + $pos >= $total_pages ) ? $total_pages : ( ( $current_page - $pos <= 1 ) ? $links : $current_page + $pos ) ) : $total_pages;


	for ($i = $start_links; $i <= $finish_links; $i++) {
		$start = ( $i - 1 ) * $per + 1;
		$pagesarray[] = ( $i != $current_page ) ? '<a href="' . str_replace("{_P_}", $i, $get) . '">' . $i . '</a>' : '<span class="curentpage">[' . $i . ']</span>';
	}
	$paginator .= $go_start . $prev . $dots_start;
	$paginator .= implode(" ", $pagesarray);
	$paginator .= $dots_finish . $next . $go_finish;

	return $paginator;
}

function topic_icon($topic, $read_time = -1, $watched = null) {
	global $fm, $f_readed;

	$icon_path = './templates/' . DEF_SKIN . '/im';

	$read_time = ( $read_time > $f_readed ) ? $read_time : $f_readed;

	if ($topic['pinned'] === true && ( $watched === null && $topic['postdate'] <= $read_time || !$watched )) {
		return '<img src="' . $icon_path . '/sticky.gif" border="0">';
	}
	if ($topic['pinned'] === true && ( $watched === null && $topic['postdate'] > $read_time || $watched )) {
		return '<img src="' . $icon_path . '/stickynew.gif" border="0">';
	}
	if ($topic['state'] == 'closed') {
		return '<img src="' . $icon_path . '/locked.gif" border="0">';
	}
	if ($topic['state'] == 'moved') {
		return '<img src="' . $icon_path . '/moved.gif" border="0">';
	}

	if ($topic['posts'] >= $fm->exbb['hot_topic'] && ( $watched === null && $topic['postdate'] <= $read_time || !$watched )) {
		return '<img src="' . $icon_path . '/hotnonew.gif" border="0">';
	}
	if ($topic['posts'] >= $fm->exbb['hot_topic']) {
		return '<img src="' . $icon_path . '/hotnew.gif" border="0">';
	}
	if ($watched === null && $topic['postdate'] > $read_time || $watched) {
		return '<img src="' . $icon_path . '/new.gif" border="0">';
	}

	return '<img src="' . $icon_path . '/nonew.gif" border="0">';
}

function sort_by_postdate($a, $b) {
	if ($a['postdate'] == $b['postdate']) {
		return 0;
	}

	return ( $a['postdate'] > $b['postdate'] ) ? -1 : 1;
}

function sort_by_post($a, $b) {
	if ($a['p'] == $b['p']) {
		return 0;
	}

	return ( $a['p'] < $b['p'] ) ? -1 : 1;
}

function sort_by_name($a, $b) {
	return strcmp($a['n'], $b['n']);
}

function Check_DefLangSkin($dirtoread, $key, $var) {
	global $fm;

	$return = $fm->exbb[$key];
	if ($var === '') {
		return $return;
	}
	$d = dir($dirtoread);
	$var = preg_replace("#[^A-Za-z0-9]#is", "", $var);
	while (false !== ( $file = $d->read() )) {
		if (is_dir($dirtoread . '/' . $file) && $file != '.' && $file != '..' && $var === $file) {
			$return = strval($file);
			break;
		}
	}
	$d->close();

	return $return;
}

function Generate_pass() {
	$shufflestring = str_shuffle('QqWwEeRrTtYyUuIiOoPpAaSsDdFfGgHhJjKkLlZzXxCcVvBbNnMm0123456789');
	$uniqstring = uniqid($shufflestring, false);
	$newpassword = mb_substr($uniqstring, mt_rand(0, 40), 8);

	return $newpassword;
}

function search_link($code) {
	global $fm;
	$words = trim($code[1]);

	return '<a href="search.php?action=start&amp;search_keywords=' . urlencode($words) . '&amp;stype=AND&amp;src_in=-1" target="_blank" style="color:#006699;decoration:underline;">' . sprintf($fm->LANG['GoSearchByWord'], $words) . '</a>';
}

function GetName($id) {
	global $fm;

	if ($id === 0) {
		return $fm->LANG['Guest'];
	}
	$user = $fm->_Getmember($id);
	if ($user !== false) {
		$name = $user['name'];
		unset( $user );

		return $name;
	}
	else {
		return $fm->LANG['Guest'];
	}
}

function CheckPostSize($key) {
	global $fm;
	if ($fm->_POST !== true) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['CorrectPost']);
	}
	if (mb_strlen($fm->_String($key)) > $fm->exbb['max_posts'] && !defined('IS_ADMIN')) {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['PostsSending'], sprintf($fm->LANG['BigPost'], $fm->exbb['max_posts'] / 1024));
	}
	if ($fm->input[$key] === '') {
		$fm->_FcloseAll();
		$fm->_Message($fm->LANG['PostsSending'], $fm->LANG['PostEmpty']);
	}
}

function ChekPrivate($private, $forum_id) {
	global $fm;

	$privateID = false;
	if ($private === true) {
		if (!defined('IS_ADMIN')) {
			if ($fm->user['id'] === 0) {
				$fm->_FcloseAll();
				$fm->_Message($fm->LANG['MainMsg'], $fm->LANG['UserUnreg'], 'loginout.php');
			}
			$userprivate = ( isset( $fm->user['private'][$forum_id] ) && $fm->user['private'][$forum_id] === true ) ? true : false;
			if ($userprivate === false) {
				$fm->_FcloseAll();
				$fm->_Message($fm->LANG['PrivatForum'], $fm->LANG['PrivatRule']);
			}

		}
		$privateID = $forum_id;
	}

	return $privateID;
}

function CheckForumPerms($permission, $mode) {
	global $fm;
	switch ($permission) {
		case 'admo':
			if ($fm->_Moderator === false) {
				$fm->_FcloseAll();
				$fm->_Message($fm->LANG['MainMsg'], $fm->LANG[$mode . 'admo']);
			}
		break;
		case 'reged':
			if ($fm->user['id'] === 0) {
				$fm->_FcloseAll();
				$fm->_Message($fm->LANG['MainMsg'], $fm->LANG[$mode . 'reged']);
			}
		break;
		default:
		break;
	}

	return true;
}

function add_attach($attach, $forumid, $toipcid, $attach_oldid = 0, $mode = 'new') {
	global $fm;

	$attachdata = $fm->_Read2Write($fp_attach, EXBB_DATA_DIR_FORUMS . '/' . $forumid . '/attaches-' . $toipcid . '.php');
	$return = true;
	if ($mode == 'new') {
		$attach_id = ( count($attachdata) !== 0 ) ? max(array_keys($attachdata)) + 1 : 1;
		$attachdata[$attach_id]['id'] = $attach['STORAGE'];
		$attachdata[$attach_id]['hits'] = 0;
		$attachdata[$attach_id]['file'] = $attach['NAME'];
		$attachdata[$attach_id]['size'] = $attach['SIZE'];
		$attachdata[$attach_id]['type'] = $attach['TYPE'];
		if ($attach['TYPE'] === 'image') {
			$attachdata[$attach_id]['width'] = $attach['WIDTH'];
			$attachdata[$attach_id]['height'] = $attach['HEIGHT'];
		}
		$return = $attach_id;
	}
	elseif ($mode == 'del') {
		if (file_exists('uploads/' . $attachdata[$attach_oldid]['id'])) {
			@unlink('uploads/' . $attachdata[$attach_oldid]['id']);
		}
		if (isset( $attachdata[$attach_oldid] )) {
			unset( $attachdata[$attach_oldid] );
		}
	}
	elseif ($mode == 'rep') {
		if (file_exists('uploads/' . $attachdata[$attach_oldid]['id'])) {
			@unlink('uploads/' . $attachdata[$attach_oldid]['id']);
		}
		$attachdata[$attach_oldid]['id'] = $attach['STORAGE'];
		$attachdata[$attach_oldid]['file'] = $attach['NAME'];
		$attachdata[$attach_oldid]['size'] = $attach['SIZE'];
		if ($attach['TYPE'] === 'image') {
			$attachdata[$attach_oldid]['width'] = $attach['WIDTH'];
			$attachdata[$attach_oldid]['height'] = $attach['HEIGHT'];
		}
		elseif ($attachdata[$attach_oldid]['type'] === 'image') {
			unset( $attachdata[$attach_oldid]['width'] );
			unset( $attachdata[$attach_oldid]['height'] );
		}
		$attachdata[$attach_oldid]['type'] = $attach['TYPE'];
	}
	$fm->_Write($fp_attach, $attachdata);
	if (count($attachdata) == 0) {
		unlink(EXBB_DATA_DIR_FORUMS . '/' . $forumid . '/attaches-' . $toipcid . '.php');
	}

	return $return;

}

function replace_img_link($imlink) {
	global $fm;

	if (ini_get('allow_url_fopen') && ( $size = @getimagesize($imlink) ) !== false) {
		if ($size[0] <= 250) {
			$imgtag = "<img src=\"$imlink\"> ";
		}
		else {
			if ($fm->exbb['redirect'] && !mb_stristr($imlink, 'http://www.' . $fm->exbb_domain) && !mb_stristr($imlink, 'http://' . $fm->exbb_domain)) {
				$_imlink = 'rd2.php?' . $imlink;
			}
			else {
				$_imlink = $imlink;
			}
			$imgtag = $fm->LANG['ImgLinked'] . "<a href=\"$_imlink\" rel=\"clearbox\" title=\"" . $fm->LANG['ToIncrease'] . "\"><img src=\"printfile.php?action=link&img=$imlink\" border=\"0\" style=\"border: 1px outset #DCDCDC;\"></a>";
		}
	}
	else {
		$imgtag = "<img src=\"$imlink\"> ";
	}

	return $imgtag;
}

function keywordsProcessor($keywords) {
	global $fm;

	return preg_replace('#((^|\s|,)[a-zA-Zа-яА-ЯёіўґєїЁІЎҐЄЇ0-9]{1,2}(?=($|\s|,))|[^a-zA-Zа-яА-ЯёіўґєїЁІЎҐЄЇ0-9,\s])#is', '', $fm->html_replace($keywords));
}

?>
