<?php
/****************************************************************************
* "IP BanPlus" mods for  ExBB Full Mods v.0.1.5								*
* Copyright (c) 2004 by Alisher Mutalov aka Markus®    	                    *
*																			*
* http://www.tvoyweb.ru														*
* http://www.tvoyweb.ru/forums/												*
* email: admin@tvoyweb.ru													*
*																			*
****************************************************************************/
if (!defined('IN_EXBB')) die('Hack attempt!');

$fm->_LoadModuleLang('preport');
if ($fm->user['id'] === 0) {
	$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['UserUnreg'], 'loginout.php');
}
$allforums	= $fm->_Read(EXBB_DATA_FORUMS_LIST);
if (($post_id = $fm->_Intval('postid')) === 0 || ($topic_id = $fm->_Intval('topic')) === 0 || ($forum_id = $fm->_Intval('forum')) === 0 || !isset($allforums[$forum_id])) {
	$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost']);
}

$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/list.php');
if (!isset($list[$topic_id]) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/'.$topic_id.'-thd.php')) {
	$fm->_Message($fm->LANG['MainMsg'],"AAA".$fm->LANG['TopicMiss']);
}

$fm->_GetModerators($forum_id,$allforums);
$forumname = $allforums[$forum_id]['name'];
$topicname = $list[$topic_id]['name'];
unset($allforums,$list);

$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/'.$topic_id.'-thd.php', FALSE);
if (!isset($topic[$post_id])) {
	$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['PostNoExists']);
}
$poster_id = $topic[$post_id]['p_id'];
unset($topic);

if ($fm->_Boolean($fm->input,'dosave') === TRUE){
	if ($fm->_POST === FALSE) {
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
	}

	if (!defined('IS_ADMIN')) {
		if (isset($_SESSION['lastposttime']) && ($_SESSION['lastposttime'] + $fm->exbb['flood_limit']) > $fm->_Nowtime) {
			$fm->_Message($fm->LANG['MainMsg'],sprintf($fm->LANG['FloodLimitNew'],$fm->exbb['flood_limit']));
		}
		$_SESSION['lastposttime'] = $fm->_Nowtime;
	}
	if (count($fm->_Moderators) === 0) $fm->_Moderators[] = 1;

	$fm->input['preporttext'] = ($fm->exbb['wordcensor'] === TRUE && $fm->input['preporttext'] !== '') ? $fm->bads_filter($fm->input['preporttext']):$fm->input['preporttext'];
	$fm->input['preporttext'] = ($fm->input['preporttext'] === '') ? '' : '[quote='.$fm->user['name'].']'.substr($fm->input['preporttext'],0,1000).'[/quote]';

	$username = ($fm->_Checkuser($poster_id) === TRUE) ? "[url=".$fm->exbb['boardurl']."/profile.php?action=show&member=".$poster_id."]".$fm->_Getmember($poster_id)."[/url]":$fm->_Getmember($poster_id);

	$message = sprintf($fm->LANG['PMText'],
						$fm->exbb['boardurl'],
						$fm->user['id'],
						$fm->user['name'],
						$fm->exbb['boardurl'],
						$forum_id,
						$topic_id,
						$post_id,
						$post_id,
						$forumname,
						$topicname).$fm->input['preporttext'];
    $sendMail = ($fm->exbb['emailfunctions'] === TRUE && $fm->exbb['pmnewmes'] === TRUE) ? TRUE:FALSE;
    if ($sendMail === TRUE) {
    	$fm->_LoadLang('messenger');
    }

	foreach ($fm->_Moderators as $moder_id) {
			$inbox = $fm->_Read2Write($fp_inbox,'messages/'.$moder_id.'-msg.php');
			$inbox[$fm->_Nowtime]['from']	= $fm->user['name'];
			$inbox[$fm->_Nowtime]['title']	= $fm->LANG['PMSubject'];
			$inbox[$fm->_Nowtime]['msg']	= $message;
			$inbox[$fm->_Nowtime]['frid']	= $fm->user['id'];
			$inbox[$fm->_Nowtime]['mail']	= FALSE;
			$inbox[$fm->_Nowtime]['status']	= FALSE;
			$fm->_Write($fp_inbox,$inbox);
			unset($inbox);

			$moder = $fm->_Read2Write($fp_moder,'members/'.$moder_id.'.php',FALSE);
			$moder['new_pm'] = TRUE;
			$fm->_Write($fp_moder,$moder);

			/* Уведомления по E-mail о новых ЛС */
			if ($sendMail === TRUE && $moder['sendnewpm'] === TRUE){
				$email = sprintf($fm->LANG['NewPMNotify'],
								$moder['name'],
								$fm->exbb['boardname'],
								$fm->exbb['boardurl'],
								$fm->user['name'],
								$fm->LANG['PMSubject'],
								$message);

				$fm->_Mail($fm->exbb['boardname'],$fm->exbb['adminemail'],$moder['mail'],$fm->LANG['EmailNewPMTitle'],$email);
			}
			/* Уведомления по E-mail о новых ЛС */
			unset($moder);
	}
	$fm->_Message($fm->LANG['TableTitle'],$fm->LANG['ReportAddedOk'],'topic.php?forum='.$forum_id.'&topic='.$topic_id.'&postid='.$post_id.'#'.$post_id);
} else {
		$fm->_Title = ' :: '.$fm->LANG['TableTitle'];
		include('./templates/'.DEF_SKIN.'/all_header.tpl');
		include('./templates/'.DEF_SKIN.'/modules/preport/post_report.tpl');
		include('./templates/'.DEF_SKIN.'/footer.tpl');
}
?>