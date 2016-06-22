<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
$admin_birsday = '';
if ($fm->exbb['birstday'] === TRUE){
	$fm->_LoadModuleLang('birstday');
	if ($includemode == 'edit') {
		$fullbirstday = $fm->LANG['NotEntered'];
		if (isset($user['birstday']) && preg_match("#(\d{1,2}):(\d{1,2}):(\d{4})#is",$user['birstday'],$info)) {
			$fullbirstday = $info[1].' '.$fm->LANG['mshow_ar'][$info[2]].' '.$info[3].$fm->LANG['Years'];
		}
		include ('modules/birstday/admintemplates/edit_users.tpl');
	} else {
			$birsdaydata = $fm->_Read2Write($fp_birsday,'modules/birstday/data/birstday_data.php',FALSE);
			$new_data = array();
			foreach ($birsdaydata as $day => $ids) {
					foreach ($ids as $id => $info) {
							if (isset($users[$id])) {
								$new_data[$day][$id] = $info;
							}
					}
			}
			unset($birsdaydata);
			$fm->_Write($fp_birsday,$new_data);
			unset($new_data);
	}
}
?>
