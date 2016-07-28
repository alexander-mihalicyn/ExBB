<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$birstdaylist = '';
if ($fm->exbb['birstday'] === TRUE){
	include ('modules/birstday/data/config.php');
	$fm->_LoadModuleLang('birstday');

	$birsdayprint = array();
	$birsdaytitle = FALSE;
	$save_flag = FALSE;

	$sendtime			= mktime(0,0,0,date("m"),date("d"),date("Y"));
	$todaykey			= date("j:n", time());
	$todayyear			= date("Y", time());

	$birsdaydata		= $fm->_Read2Write($fp_birsday,'modules/birstday/data/birstday_data.php');
	//[0]  - год; [1] - флаг для мыла и ЛС; [2] - логин; [3] - емаил; [4] - флаг для показа возраста

	if (count($birsdaydata) && isset($birsdaydata[$todaykey])) {
		foreach ($birsdaydata[$todaykey] as $user_id => $info) {
				if (is_null($user_id) || $user_id == "" || $user_id == 0) {
					unset($birsdaydata[$todaykey][$user_id]);
					if (count($birsdaydata[$todaykey]) == 0) unset($birsdaydata[$todaykey]);
					$save_flag = TRUE;
					continue;
				}

				if ($user_id == $fm->user['id']){
					$birsdaytitle = $fm->user['name'].$fm->LANG['PmTitle'];
				}

				$userinfo = explode(":",$info);
				$printage = ($userinfo[4] == 1) ? ' - '.($todayyear - $userinfo[0]):'';
				$birsdayprint[] = '<a href="profile.php?action=show&member='.$user_id.'" title="'.$fm->LANG['ViewBirstProf'].$userinfo[2].'">'.$userinfo[2].$printage.'</a>';

				if ($sendtime > $userinfo[1]) {
					if ($fm->exbb['emailfunctions'] == TRUE && FM_BIRSTEMAIL === TRUE){
						birstday_email($userinfo[2],$userinfo[3]);
					}

					//PM Send
					if (FM_BIRSTPM === TRUE){
						birstday_pm($user_id);
					}

					$birsdaydata[$todaykey][$user_id] = $userinfo[0].':'.$sendtime.':'.$userinfo[2].':'.$userinfo[3].':'.$userinfo[4];
					$save_flag = TRUE;
				}
		}
	}
	($save_flag === TRUE) ? $fm->_Write($fp_birsday,$birsdaydata):fclose($fp_birsday);
	$birsdayprint = (count($birsdayprint)) ? implode ( ', ', $birsdayprint):$fm->LANG['NoBirstToday'];
	include('templates/'.DEF_SKIN.'/modules/birstday/board_body.tpl');
	unset($birstdayconf,$data,$birsdaydata,$birsdayprint);
	$fm->_Title = ($birsdaytitle === FALSE) ? '':' :: '.$birsdaytitle;
	$rowspan++;
}

function birstday_email($name,$emailaddres){
		global $fm;

        $subject 	= $name.$fm->LANG['PmTitle'];
        $email		= sprintf ( $fm->LANG['EmailText'],
        						$fm->exbb['boardurl'],
        						$fm->exbb['boardname'],
        						$name,
        						$fm->exbb['boardname']);
        $fm->_Mail($fm->exbb['boardname'],$fm->exbb['adminemail'],$emailaddres,$subject,$email);
        return;
}

function birstday_pm($id) {
		global $fm;

        $user = $fm->_Read2Write($fp_user,EXBB_DATA_DIR_MEMBERS . '/'.$id.'.php',FALSE);
        $user['new_pm'] = TRUE;
        $fm->_Write($fp_user,$user);

        #SEND BIRSTDAY PM
        $fm->LANG['PmText'] = sprintf($fm->LANG['PmText'],
        							$user['name'],
        							$fm->exbb['boardname']);

        $inbox = $fm->_Read2Write($fp_inbox,'messages/'.$id.'-msg.php');
		$inbox[$fm->_Nowtime]['from']	= $fm->LANG['PMFrom'];
		$inbox[$fm->_Nowtime]['title']	= $user['name'].$fm->LANG['PmTitle'];
		$inbox[$fm->_Nowtime]['msg']	= $fm->LANG['PmText'];
		$inbox[$fm->_Nowtime]['frid']	= 1;
		$inbox[$fm->_Nowtime]['mail']	= FALSE;
		$inbox[$fm->_Nowtime]['status']	= FALSE;

        $fm->_Write($fp_inbox,$inbox);
        unset($inbox,$user);
        if ($fm->user['id'] == $id) $fm->user['new_pm'] = TRUE;
}
?>
