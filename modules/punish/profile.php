<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$mod_punish = '';
if ($fm->exbb['punish'] === TRUE){
	$total_pun = (!isset($user['punned']))? 0:count($user['punned']);
	if ($total_pun !== 0){
		$fm->_LoadModuleLang('punish');
		$showrow = FALSE;
		$punish_data = '';
		foreach ($user['punned'] as $id => $value) {
				list($forum_id,$topic_id,$post_id) = explode(':',$id);
				$forumname = (isset($allforums[$forum_id])) ? $allforums[$forum_id]['name']:$fm->LANG['Unknow'];
				$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/list.php');
				$topicname = (isset($list[$topic_id])) ? $list[$topic_id]['name']:$fm->LANG['Unknow'];
				list($moder,$status) = explode('::',$value);
				$whoadd = (isset($fm->LANG['Pun'.$status]) ? $fm->LANG['Pun'.$status].' - ' : '').$moder;
				include('./templates/'.DEF_SKIN.'/modules/punish/punish_data.tpl');
		}
		include('./templates/'.DEF_SKIN.'/modules/punish/profile.tpl');
	}
}
?>
