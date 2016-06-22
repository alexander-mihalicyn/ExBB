<?php
/****************************************************************************
* ExBB v.1.1                                                              	*
* Copyright (c) 2002-20õõ by Alexander Subhankulov aka Warlock            	*
*                                                                         	*
* http://www.exbb.net                                             			*
* email: admin@exbb.net                                           			*
*                                                                         	*
****************************************************************************/
/****************************************************************************
*                                                                         	*
*   This program is free software; you can redistribute it and/or modify  	*
*   it under the terms of the GNU General Public License as published by  	*
*   the Free Software Foundation; either version 2 of the License, or     	*
*   (at your option) any later version.                                   	*
*                                                                         	*
****************************************************************************/
DEFINE('IN_EXBB', TRUE);
define("IS_LOGIN", TRUE);

include('./include/common.php');
$fm->_GetVars(TRUE);
$fm->_String('action');

if ($fm->input['action'] == 'login' && $fm->_POST === TRUE) {
	$user = FALSE;
	$id = precheck_user();
	if ($id !== 0) {
		$user = $fm->_Read2Write($fp_user,'members/'.$id.'.php',FALSE);
	}

	if (is_array($user) and $user['pass'] == md5($fm->input['ipassword'])) {
        $fm->user['name'] = $user['name'];
		if ($user['status'] == 'banned') {
			$fm->_Fclose($fp_user);
			$fm->_WriteLog($fm->LANG['BannedLoged']);
			if($text_ban = $fm->_AutoUnBan($user, TRUE)) {
			$fm->_Message($fm->LANG['ErrorLogin'],$fm->LANG['LoginDeniedBan'].'<br />'.$text_ban);
			}
			$fm->_Message($fm->LANG['ErrorLogin'],$fm->LANG['LoginDeniedBan']);
        }
        $now_time = time();

		$_SESSION['mid']			= $user['id'];
		$_SESSION['sts']			= $user['status'];
		$_SESSION['lastposttime']	= isset($user['lastpost']['date']) ? $user['lastpost']['date'] : $now_time-180;
		$_SESSION['iden']			= md5($user['name'].$user['pass']._SESSION_ID);

		//$user['last_visit']	= $now_time;
		$fm->_Write($fp_user,$user);
		
		// Advanced Visit Stats for ExBB FM 1.0 RC1 by yura3d
		if ($fm->exbb['statvisit']) {
			$statvisit = $fm->_Read('modules/statvisit/data/config.php');
			if ($statvisit['day']) {
				$today = $fm->_Read2Write($fp_today, 'modules/statvisit/data/today.php');
				if (!isset($today['members'][$user['id']]) && $today['guests'] > 0)
					$today['guests']--;
				$fm->_Write($fp_today, $today);
			}
		}

		$fm->_setcookie('exbbn',$user['id']);
		$fm->_setcookie('exbbp',md5($user['pass']));
		$fm->_setcookie('lastvisit',$fm->_Nowtime);
		$fm->_WriteLog($fm->LANG['LogedIn']);
		header('Location: '.get_rd(1));
		exit;
	}
	$fm->_WriteLog(sprintf($fm->LANG['WrongEntered'],$fm->input['imembername'],$fm->input['ipassword']));
	$fm->_Message($fm->LANG['ErrorLogin'],$fm->LANG['LoginError'],'loginout.php');
} elseif ($fm->input['action'] == 'logout' && $fm->user['id'] !== 0) {
		$user = $fm->_Read2Write($fp_user,'members/'.$fm->user['id'].'.php',FALSE);
		$user['last_visit']	= $fm->_Nowtime;
		$fm->_Write($fp_user,$user);
		$rd = get_rd();
		require('modules/watches/_loginout.php');
		$_SESSION = array();
		session_destroy();
		$fm->_setcookie('exbbn','',-1);
		$fm->_setcookie('exbbp','',-1);
		$fm->_setcookie('t_visits','',-1);
		$fm->_setcookie('lastvisit','',-1);
		$fm->_WriteLog($fm->LANG['LogOuted']);
		header('Location: '.$rd);
		exit();
} elseif ($fm->input['action'] == 'loginadmin' && $fm->user['id'] !== 0) {
		if ($fm->_POST === TRUE) {
			$logged = $fm->_Getmember(precheck_user());
			if (is_array($logged) and $logged['pass'] === md5($fm->input['ipassword']) and $logged["status"] === "ad") {
				$_SESSION['admin'] = true;
				$_SESSION['admin_lasttime'] = time();
				$fm->_WriteLog($fm->LANG['LogedInAdmin']);
				header('Location: admincenter.php');
				exit();
			} else {
					$fm->_WriteLog($fm->LANG['WrongLogedInAdmin']);
					header('Location: index.php');
					exit();
			}
		} else {
				$fm->_LoadLang('all',TRUE);
				include('./admin/all_header.tpl');
				include('./admin/loginform.tpl');
		}
		}  else {
		if ($fm->user['id'] !== 0) {
			header('Location: index.php');
			exit;
		}
		$fm->_Title  = ' :: '.$fm->LANG['LoginOut'];
		include('./templates/'.DEF_SKIN.'/all_header.tpl');
		include('./templates/'.DEF_SKIN.'/logos.tpl');
		include('./templates/'.DEF_SKIN.'/login.tpl');
		include('./templates/'.DEF_SKIN.'/footer.tpl');
}
include('page_tail.php');

function precheck_user() {
		global $fm;

		if ($fm->_String('imembername') == '' || $fm->_String('ipassword') == '') {
			return 0;
		}
		$fm->input['imembername']		= $fm->_LowerCase(substr($fm->input['imembername'],0,32));
		$fm->input['ipassword']			= substr($fm->input['ipassword'],0,32);

		$allusers = $fm->_Read(FM_USERS,FALSE);
		$user_id = 0;
		foreach ($allusers as $u_id=>$info) {
				if ($fm->input['imembername'] == $info['n']) {
					$user_id = $u_id;
					break;
				}
		}
		return $user_id;
}
?>
