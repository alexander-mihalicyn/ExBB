<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$userstop = '';

if ($fm->exbb['userstop'] === TRUE){
	$fm->_LoadModuleLang('userstop');
	include(EXBB_DATA_DIR_MODULES. '/userstop/config.php');

	$deldate = mktime(0,0,0,date("m"),date("d") - FM_USERSTOP_DAYS,date("Y"));
	$usertop =  $fm->_Read2Write($fp_usertop,EXBB_DATA_DIR_MODULES. '/userstop/data.php');

	$save_flag = FALSE;
	$for_printarray = array();
	foreach ($usertop as $date => $dayarray){
			if ($date <= $deldate) {
				unset($usertop[$date]);
				$save_flag = TRUE;
				continue;
			}
			foreach ($dayarray as $key => $posts) {
					$for_printarray[$key] = (isset($for_printarray[$key])) ? $for_printarray[$key] + $posts:$posts;
			}
	}
	($save_flag === FALSE) ? $fm->_Fclose($fp_usertop):$fm->_Write($fp_usertop,$usertop);
	uasort($for_printarray, "cmp");
	array_splice($for_printarray, FM_USERSTOP_DAYS);
	if (count($for_printarray)){
		foreach ($for_printarray as $key=>$value){
				list($user_id, $user_name) = explode("::", $key);
				$print = (FM_USERSTOP_SHOWPOSTS === TRUE)? ' ['.$value.']':'';
				$userslist[] ='<a href="profile.php?action=show&member='.$user_id.'" title="'.$fm->LANG['UserProfile'].$user_name.'">'.$user_name.'</a>'.$print;
				}
		$userslist = implode ( ', ', $userslist);
	} else {
			$userslist = sprintf($fm->LANG['NoUsersPosts'],FM_USERSTOP_DAYS);
	}
	unset($for_printarray);
	$toptitle = sprintf($fm->LANG['UsersTopList'],FM_USERSTOP_DAYS);
	include('./templates/'.DEF_SKIN.'/modules/userstop/userstop.tpl');
	$rowspan++;
}

function cmp($a, $b){
        if ($a == $b) return 0;
        return ($a > $b) ? -1 : 1;
}