<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
$show_birstday = '';
if ($fm->exbb['birstday'] === TRUE){
	$fm->_LoadModuleLang('birstday');
	$fullbirstday = $fm->LANG['NotEntered'];
	if (isset($user['birstday']) && preg_match("#(\d{1,2}):(\d{1,2}):(\d{4})#is",$user['birstday'],$info)) {
		$birsyear = ($user['showyear'] === FALSE) ? ' &nbsp;&nbsp;<span class="copyright">'.$user['name'].$fm->LANG['HideYears'].'</span>':$info[3].$fm->LANG['Years'];
		$fullbirstday = $info[1].' '.$fm->LANG['mshow_ar'][$info[2]].' '.$birsyear;
	}
	include ('templates/'.DEF_SKIN.'/modules/birstday/profile_show.tpl');
}