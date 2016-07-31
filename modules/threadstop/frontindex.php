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

$fm->_LoadModuleLang('threadstop');

include(EXBB_DATA_DIR_MODULES.'/threadstop/config.php');

$allforums = $fm->_Read(EXBB_DATA_FORUMS_LIST);
$arr_by_viewspost = array();
$arr_by_lastpost  = array();
$arr_by_posts     = array();
foreach ($allforums as $forum_id => $forum){
		if ($forum['private'] === TRUE && !defined('IS_ADMIN') && ($fm->user['id'] === 0 || !isset($fm->user['private'][$forum_id]) || $fm->user['private'][$forum_id] === FALSE)) continue;
		$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/list.php');
		$_views = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/views.php');
		foreach ($_views as $topic_id => $views)
			$topic[$topic_id]['views'] = $views;
		sort_array($topic,'views',$arr_by_viewspost);
		sort_array($topic,'postdate',$arr_by_lastpost);
		sort_array($topic,'posts',$arr_by_posts);
}
unset($allforums,$topic);

$topic_by_views		= return_print($arr_by_viewspost,'views');
$topic_by_lastpost 	= return_print($arr_by_lastpost,'posts');
$topic_by_post		= return_print($arr_by_posts,'posts');
unset($arr_by_viewspost,$arr_by_lastpost,$arr_by_posts,$alltopforum);
$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/hints.js\"></script>
<script type=\"text/javascript\" language=\"JavaScript\">
<!--
var LANG = {
	firstText:		'{$fm->LANG['FirstText']}',
	lastText:		'{$fm->LANG['LastText']}',
	firstTitle:		'{$fm->LANG['FirstTitle']}',
	lastTitle:		'{$fm->LANG['LastTitle']}'
};
//-->
</script>";
$fm->_Title = ' :: '.$fm->LANG['TopicsRaiting'];
include('./templates/'.DEF_SKIN.'/all_header.tpl');
include('./templates/'.DEF_SKIN.'/logos.tpl');
include('./templates/'.DEF_SKIN.'/topic_stat_table.tpl');
include('./templates/'.DEF_SKIN.'/footer.tpl');


function return_print($array,$mode) {
		$menu = '';
		$array = (is_array($array))?$array:array();
		foreach ($array as $key =>$value){
			if (!isset($value['name']) || !isset($value['id']) || !isset($value['fid']) || !isset($value['postkey'])) continue;
				$toptopicname = (strlen($value['name'])>33)? substr($value['name'],0,32).'...':$value['name'];
				$toptopicname = (isset($value['tnun'])) ? $toptopicname.' - '.$value['tnun']:$toptopicname;
				$printvalue = (FM_PRINTVAL === TRUE && isset($value[$mode])) ? ' ('.$value[$mode].')':'';
				$menu .= '<span class="hint"><a href="topic.php?forum='.$value['fid'].'&topic='.$value['id'].'&postid='.$value['postkey'].'#'.$value['postkey'].'">'.$toptopicname.$printvalue.'</a></span><br>';
		}
		return $menu;
}

function sort_array($array,$key,&$return_array){
		switch ($key) {
			case 'views'	: 	$state = 'closed';
								break;
			case 'postdate'	:	$state = 'moved';
								break;
			case 'posts'	: 	$state = 'closed';
								break;
		}
		_sort($array, $key, $state);
        $array = array_slice($array, 0, FM_SHOW_TOPICS);
        $return_array = array_merge ($return_array, $array);
        _sort($return_array, $key, $state);
		$return_array = array_slice($return_array,0,FM_SHOW_TOPICS);
}

function _sort(&$array, $key, $state) {
		$function = "if (!isset(\$a['$key'])) \$a['$key'] = 0;
		if (!isset(\$b['$key'])) \$b['$key'] = 0;
		if (isset(\$a['state']) && isset(\$b['state'])) {
		if (\$a['state'] === '$state' && \$b['state'] !== '$state')
			return 1;
		else if (\$a['state'] !== '$state' && \$b['state'] === '$state')
			return -1;
		}
		if (\$a['$key']<\$b['$key'])
			return 1;
		else if (\$a['$key']>\$b['$key'])
			return -1;
		return 0;";
		uasort($array, create_function('$a,$b', $function));
}