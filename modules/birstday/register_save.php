<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

if ($fm->exbb['birstday'] === TRUE){
	$fm->_LoadModuleLang('birstday');
	$fm->_String('d',$fm->LANG['Day']);
	$fm->_String('m',$fm->LANG['Month']);
	$fm->_String('y',$fm->LANG['Year']);

	if ($fm->input['d'] !== $fm->LANG['Day'] &&  $fm->input['m'] !== $fm->LANG['Month'] && $fm->input['y'] !== $fm->LANG['Year']){
		$user['birstday'] = $fm->input['d'].':'.$fm->input['m'].':'.$fm->input['y'];
		$user['showyear'] = $fm->_Boolean($fm->input,'showyear');

		$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$day_key = $fm->input['d'].':'.$fm->input['m'];

		$birstday_data = $fm->_Read2Write($fp_birst,EXBB_DATA_DIR_MODULES.'/birthday/data.php');
		$birstday_data[$day_key][$id] =  $fm->input['y'].':'.$today.':'.$user['name'].':'.$user['mail'].':'.$user['showyear'];

		$fm->_Write($fp_birst,$birstday_data);
		unset($birstday_data);
	}
}

//[0]  - год; [1] - флаг для мыла и ЛС; [2] - логин; [3] - емаил; [4] - флаг для показа возраста