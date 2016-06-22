<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

if ($fm->exbb['birstday'] === TRUE){
	$fm->_LoadModuleLang('birstday');
	$fm->_String('d',$fm->LANG['Day']);
	$fm->_String('m',$fm->LANG['Month']);
	$fm->_String('y',$fm->LANG['Year']);

	if ($fm->input['d'] !== $fm->LANG['Day'] &&  $fm->input['m'] !== $fm->LANG['Month'] && $fm->input['y'] !== $fm->LANG['Year']){
		$user['birstday'] 	= $fm->input['d'].':'.$fm->input['m'].':'.$fm->input['y'];
		$user['showyear'] 	= $fm->_Boolean($fm->input,'showyear');
		$new_day_key 		= $fm->input['d'].':'.$fm->input['m'];
		$today 				= mktime(0,0,0,date("m"),date("d")-1,date("Y"));

		$birstday_data = $fm->_Read2Write($fp_birst,'modules/birstday/data/birstday_data.php');

		if (isset($fm->user['birstday']) && preg_match("#(\d{1,2}:\d{1,2}):\d{4}#is",$fm->user['birstday'],$info)) {
			$day_key 	= $info[1];
			if (isset($birstday_data[$day_key]) && isset($birstday_data[$day_key][$fm->user['id']])) {
				if ($day_key !== $new_day_key) {
					unset($birstday_data[$day_key][$fm->user['id']]);
					if (count($birstday_data[$day_key]) == 0) unset($birstday_data[$day_key]);
				}
			}
		}

		$birstday_data[$new_day_key][$fm->user['id']] =  $fm->input['y'].':'.$today.':'.$user['name'].':'.$user['mail'].':'.$user['showyear'];
		$fm->_Write($fp_birst,$birstday_data);
		unset($birstday_data);
	}
}
//[0]  - год; [1] - флаг для мыла и ЛС; [2] - логин; [3] - емаил; [4] - флаг для показа возраста
?>
