<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$oldallforums = $fm->_Read($_ForumRoot.'_data/allforums.php');

$newallforums = array();
foreach ($oldallforums as $forum_id => $forum) {
		switch($forum['status']) {
			case 'all':		$status = 'all';break;
			case 'reged':	$status = 'reged';break;
			case 'no': 		$status = 'admo';break;
		}

		$newallforums[$forum_id]['id']				= $forum_id;
		$newallforums[$forum_id]['catid']			= $forum['catid'];
		$newallforums[$forum_id]['name']			= $forum['name'];
		$newallforums[$forum_id]['desc']			= (isset($forum['desc']) && $forum['desc'] !== '') ? $forum['desc']:'';
		$newallforums[$forum_id]['catname']			= $forum['catname'];
		$newallforums[$forum_id]['posts']			= $forum['posts'];
		$newallforums[$forum_id]['topics']			= $forum['topics'];
		$newallforums[$forum_id]['position']		= $forum['position'];
		$newallforums[$forum_id]['moderator'] 		= (isset($forum['moderator']) && $forum['moderator'] !== '' && ($moderators = unserialize($forum['moderator'])) !== FALSE) ? $moderators:array();
		$newallforums[$forum_id]['private']			= (isset($forum['private']) && boolean($forum['private']) === TRUE) ? TRUE:FALSE;
		$newallforums[$forum_id]['codes'] 			= (isset($forum['codes']) && boolean($forum['codes']) === TRUE) ? TRUE:FALSE;
		$newallforums[$forum_id]['polls'] 			= (isset($forum['polls']) && boolean($forum['polls']) === TRUE) ? TRUE:FALSE;
		$newallforums[$forum_id]['last_poster']		= (isset($forum['last_poster']) && isset($forum['last_poster_id']) && boolean($forum['last_poster_id']) !== FALSE) ? $forum['last_poster']:FALSE;
		$newallforums[$forum_id]['last_poster_id'] 	= (isset($forum['last_poster_id']) && $forum['last_poster_id'] !== 0) ? $forum['last_poster_id']:0;
		$newallforums[$forum_id]['last_post'] 		= (isset($forum['last_post']) && $forum['last_post'] !== '') ? $forum['last_post']:'';
		$newallforums[$forum_id]['last_post_id'] 	= (isset($forum['last_post_id']) && $forum['last_post_id'] !== 0) ? $forum['last_post_id']:0;
		$newallforums[$forum_id]['last_time']		= (isset($forum['last_time']) && $forum['last_time'] !== 0) ? $forum['last_time']:0;
		$newallforums[$forum_id]['last_key']		= (isset($forum['last_key']) && $forum['last_key'] !== 0) ? $forum['last_key']:0;
		$newallforums[$forum_id]['upload']			= (isset($forum['upload']) && $forum['upload'] != 0) ? $forum['upload']:0;
		$newallforums[$forum_id]['stnew']			= $status;
		$newallforums[$forum_id]['strep'] 			= $status;
		$newallforums[$forum_id]['stview'] 			= 'all';
		$newallforums[$forum_id]['icon'] 			= (isset($forum['icon']) && $forum['icon'] !== '') ? $forum['icon']:'';
}
unset($oldallforums);
uasort($newallforums,'sort_by_catid');
uasort($newallforums,'sort_by_position');
$alltopics = $fm->_Read2Write($fp_allforums,$_ForumRoot.'data/allforums.php');
$fm->_Write($fp_allforums,$newallforums);

$warning = '<div class="ok">'.$lang['NoError'].$lang['ForumsUpdatedOk'].'</div>';

?>