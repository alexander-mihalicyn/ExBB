<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

@set_time_limit(3600);

$tmpUserArray = Array (
					'id' => 0, 'name' => '', 'pass' => '', 'mail' => '', 'status' => '',
					'title' => '', 'posts' => 0, 'showemail' => FALSE, 'www' => '', 'aim' => '',
					'icq' => '', 'location' => '', 'joined' => 0, 'sig' => '', 'sig_on' => FALSE,
					'timedif' => 0, 'upload' => FALSE, 'avatar' => 'noavatar.gif', 'last_visit' => 0,
					'posted' => Array(), 'lastpost' => Array(), 'lang' => 'russian', 'skin' => 'InvisionExBB',
					'interests' => '', 'private' => Array(), 'new_pm' => FALSE, 'sendnewpm' => FALSE,
					'visible' => FALSE, 'posts2page' => 10, 'topics2page' => 15, 'birstday' => '', 'showyear' => FALSE,
				);

$allforums = $fm->_Read($_ForumRoot.'data/allforums.php');
$private = $birsdaydata = $all_users = array();
foreach ($allforums as $id => $forum) {
		if ($forum['private'] === TRUE) {
			$private[$id] = 1;
		}
}
unset($allforums);

$temp_users = $fm->_Read($_ForumRoot.'install/temp/_users.php');

$d = dir($_ForumRoot.'_members/');
while (false !== ($file = $d->read())) {
	if (preg_match("#^\d{1,}\.php$#is",$file)) {
		if (filesize($_ForumRoot.'_members/'.$file) <= 100) continue;
       	$userinfo = $fm->_Read($_ForumRoot.'_members/'.$file);

		if (!isset($userinfo['name']) || !isset($userinfo['id']) || !isset($userinfo['pass']) || !isset($userinfo['mail'])) continue;

		$user_id = $userinfo['id'];
        $userinfo['name']		= htmlspecialchars(pre_replace($userinfo['name']),ENT_QUOTES);

		$day = FALSE; $month = FALSE; $year = FALSE;
		if (isset($userinfo['d'])) {
			$day = (preg_match("#^\d{1,2}$#is",$userinfo['d'])) ? $userinfo['d']:$day;
			unset($userinfo['d']);
		}

		if (isset($userinfo['m'])) {
			$month = (preg_match("#^\d{1,2}$#is",$userinfo['m'])) ? $userinfo['m']:$month;
			unset($userinfo['m']);
		}

		if (isset($userinfo['y'])) {
			$year = (preg_match("#^\d{4}$#is",$userinfo['y'])) ? $userinfo['y']:$year;
			unset($userinfo['y']);
		}

		$userinfo['birstday'] = (isset($userinfo['birstday']) && !empty($userinfo['birstday']) && preg_match("#^\d{1,2}:\d{1,2}:\d{4}$#is",$userinfo['birstday'])) ? $userinfo['birstday']:FALSE;
		$userinfo['showyear'] = (isset($userinfo['showyear'])) ? $userinfo['showyear']:TRUE;

		if ($userinfo['birstday'] === FALSE) {
			if ($day && $month && $year) {
				$userinfo['birstday'] = $day.':'.$month.':'.$year;
			} else {
					unset($userinfo['birstday']);
					unset($userinfo['showyear']);
			}
		}

		if (isset($userinfo['birstday'])) {
			$today = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
			$day_key = substr($userinfo['birstday'], 0, -5);
			$birsdaydata[$day_key][$userinfo['id']] = substr($userinfo['birstday'], -4).':'.$today.':'.$userinfo['name'].':'.$userinfo['mail'].':'.$userinfo['showyear'];
		}

		if (isset($userinfo['postpage'])) {
			$userinfo['posts2page'] = ($userinfo['postpage'] != 0) ? $userinfo['postpage']:$fm->exbb['posts_per_page'];
		    unset($userinfo['postpage']);
		} else {
				$userinfo['posts2page'] = $fm->exbb['posts_per_page'];
		}

		if (isset($userinfo['topicpage'])) {
			$userinfo['topics2page'] = ($userinfo['topicpage'] != 0) ? $userinfo['topicpage']:$fm->exbb['topics_per_page'];
		    unset($userinfo['topicpage']);
		} else {
				$userinfo['topics2page'] = $fm->exbb['topics_per_page'];
		}
		$userinfo['sig_on'] = (isset($userinfo['sig_on']) && $userinfo['sig_on']) ? TRUE:FALSE;

		if (isset($userinfo['total_pun'])) {
			unset($userinfo['total_pun']);
		}
		$userinfo['punned'] = (isset($userinfo['punned']) && is_array($userinfo['punned'])) ? $userinfo['punned']:array();
		if (count($userinfo['punned']) === 0) {
			unset($userinfo['punned']);
			if (isset($userinfo['time_pun'])) {
				unset($userinfo['time_pun']);
			}
		} else {
			$newpun = array();
			foreach ($userinfo['punned'] as $punid => $puninfo) {
					if (preg_match("#^\d+::\d+::\d+::\d+::\d+$#",$punid)) {
						list($f_id,$t_id,$p_id) = explode('::',$punid);
			        	$newpun[$f_id.':'.$t_id.':'.$p_id] = $puninfo;
			       	 	unset($userinfo['punned'][$punid]);
			   		}
			}
			if (count($newpun) !== 0 && isset($userinfo['time_pun'])) {
				$userinfo['punned'] = $newpun;
				$userinfo['lastpun'] = $userinfo['time_pun'];
				unset($userinfo['time_pun']);
			}
		}
        $userinfo['pass'] 		= (isset($_SESSION['nohashed']) && $_SESSION['nohashed'] === TRUE) ? md5($userinfo['pass']):$userinfo['pass'];
        $userinfo['location']	= htmlspecialchars(pre_replace($userinfo['location']),ENT_QUOTES);
		$userinfo['sig']		= htmlspecialchars(pre_replace($userinfo['sig']),ENT_QUOTES);
		$userinfo['avatar']		= (!isset($userinfo['avatar']) || $userinfo['avatar'] == '') ? 'noavatar.gif':$userinfo['avatar'];
		$userinfo['visible']	= (!isset($userinfo['visible']) || $userinfo['visible'] == '') ? FALSE:$userinfo['visible'];
		$userinfo['www']		= (!isset($userinfo['www']) || $userinfo['www'] == '' || $userinfo['www'] == 'http://') ? '':$userinfo['www'];
		$userinfo['last_visit']	= (!isset($userinfo['last_visit'])) ? 0:$userinfo['last_visit'];

		if (isset($userinfo['private'])) {
			if (is_array($userinfo['private'])) {
				$_private	= array();
    			foreach ($userinfo['private'] as $forum_id => $chek) {
					 if ($chek === TRUE && array_key_exists($forum_id,$private)) {
					 	 $_private[$forum_id] = TRUE;
					 }
				}
				$userinfo['private'] = $_private;
			} else {
					$userinfo['private'] = array();
			}
		} else {
				$userinfo['private'] = array();
		}

		if (isset($temp_users[$userinfo['id']])) {
			$userinfo['posts'] = $temp_users[$user_id]['posts'];
			$userinfo['posted'] = $temp_users[$user_id]['posted'];
			$userinfo['lastpost'] = $temp_users[$user_id]['lastpost'];
		} else {
				$userinfo['posts']		= 0;
				$userinfo['posted']		= array();
				//$userinfo['lastpost']	= array();
		}
		unset($temp_users[$user_id]);

		$userinfo = array_merge($tmpUserArray,$userinfo);

		$fm->_Read2Write($fp_user,$_ForumRoot.'members/'.$file);
		$fm->_Write($fp_user,$userinfo);
		$all_users[$user_id]['n'] = $fm->_LowerCase($userinfo['name']);
		$all_users[$user_id]['m'] = $userinfo['mail'];
		$all_users[$user_id]['p'] = $userinfo['posts'];
	}
}
$d->close();
$fm->_Read2Write($fp_users,$_ForumRoot.'data/users.php');
$fm->_Write($fp_users,$all_users);

if (count($birsdaydata) >0) {
	$fm->_Read2Write($fp_birsday,$_ForumRoot.'modules/birstday/data/birstday_data.php');
	$fm->_Write($fp_birsday,$birsdaydata);
}

$warning = '<div class="ok">'.$lang['NoError'].'ƒанные пользователей успешно обновлены!</div>';
$action = 'updatepm';
?>