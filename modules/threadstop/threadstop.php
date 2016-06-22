<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$fm->_LoadModuleLang('threadstop');

$allforums = $fm->_Read(FM_ALLFORUMS);
if (!defined('IS_ADMIN') && $fm->user['status'] != 'sm')
	foreach ($allforums as $forum)
		$fm->_GetModerators($forum['id'], $allforums);
if (($forum_id = $fm->_intval('inforum')) === 0 || !isset($allforums[$forum_id])) {
	$divtext = $fm->LANG['CorrectPost'];
} elseif (($topic_id = $fm->_intval('intopic')) === 0 || !file_exists('forum'.$forum_id.'/'.$topic_id.'-thd.php')) {
		$divtext = $fm->LANG['CorrectPost'];
} elseif ($allforums[$forum_id]['private'] == TRUE && !defined('IS_ADMIN') && (!isset($fm->user['private'][$forum_id]) || $fm->user['private'][$forum_id] !== TRUE)) {
		$divtext = $fm->LANG['TsNoPerms'];
}
elseif (!$fm->user['id'] && $allforums[$forum_id]['stview'] == 'reged' || !defined('IS_ADMIN') && $fm->user['status'] != 'sm' && empty($fm->_Moderator) && $allforums[$forum_id]['stview'] == 'admo') {
		$divtext = $fm->LANG['TsNoPerms'];
} else {
		$topic = $fm->_Read('forum'.$forum_id.'/'.$topic_id.'-thd.php');
        ksort($topic,SORT_NUMERIC);
		if ($fm->_Intval('mode') == 1){
			reset($topic);
			$title = $fm->LANG['TsFirstPost'];
		} else {
				end($topic);
				$title = $fm->LANG['TsLastPost'];
		}
		$current = current($topic);
		$poster = $fm->LANG['TsAuthor'].' <b>'.GetName($current['p_id']).'</b>';
		$html = (isset($current['html']) && $current['html'] === TRUE) ? TRUE:FALSE;
		$post =  $fm->formatpost($current['post'],$html);
		unset($allforums,$topic,$current);
		$_RESULT = array(
  				"error"		=> 0,
  				"divtext"	=> "<b>$title</b><br>$poster<hr>$post",
  				"topic"		=> $topic_id,
  				"forum"		=> $forum_id,
		);
	die();
}
$_RESULT = array(
	'error' => 0,
	'divtext' => '<b>'.$divtext.'</b>',
	'topic' => $topic_id,
	'forum' => $forum_id
);
?>