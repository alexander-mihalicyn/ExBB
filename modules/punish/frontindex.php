<?php
/***************************************************************************
* "Punishment of the user" mods for  ExBB v.1.9.1                          *
* Copyright (c) 2004 by Alisher Mutalov aka Markus®                        *
*                                                                          *
* http://www.tvoyweb.ru                                                    *
* http://www.tvoyweb.ru/forums/                                            *
* email: admin@tvoyweb.ru                                                  *
*                                                                          *
***************************************************************************/
if (!defined('IN_EXBB')) die('Hack attempt!');

$fm->_String('doact','show');
$fm->_LoadModuleLang('punish');

include('modules/punish/data/config.php');

$topic_id	= $fm->_Intval('topic');
$post_id	= $fm->_Intval('postid');
$user_id 	= $fm->_Intval('user');
global $user, $allforums;
$allforums 	= $fm->_Read(EXBB_DATA_FORUMS_LIST);

if (($forum_id = $fm->_Intval('forum')) === 0 || !isset($allforums[$forum_id])) {
	$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost']);
}
$fm->_GetModerators($forum_id,$allforums);

if ($fm->_Moderator === TRUE) {
	if ($fm->input['doact'] == 'addpun') {
		$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/list.php');
		if ($topic_id === 0 || !isset($list[$topic_id]) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/'.$topic_id.'-thd.php')) {
			$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost']);
		}
		$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/'.$topic_id.'-thd.php');
		if ($post_id === 0 || !isset($topic[$post_id])) {
			$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost']);
		}

		$user_id = $topic[$post_id]['p_id'];
		if ($user_id === 0 || $fm->_Checkuser($user_id) === FALSE) {
			$fm->_Message($fm->LANG['MainMessage'],$fm->LANG['UserNotFound']);
		}
		$pun_id = $forum_id.':'.$topic_id.':'.$post_id;
		$user = $fm->_Read2Write($fp_user,'members/'.$user_id.'.php');
		if (isset($user['punned']) && isset($user['punned'][$pun_id])){
			$fm->_Fclose($fp_user);
			$fm->_Message($fm->LANG['MainMessage'],$fm->LANG['UserPunAlredy']);
		}
		$total_pun = (isset($user['punned']) && is_array($user['punned'])) ? count($user['punned']):0;

		$pun_day = floor((time() - (isset($user['lastpun']) ? $user['lastpun']:0))/86400);
		switch ($total_pun) {
			case 5: $fm->_Fclose($fp_user);
					$fm->_Message($fm->LANG['MainMessage'],$fm->LANG['CantPunPunEmpty']);
					break;
			case 4: if ($pun_day <= FM_PUNISH4){
						$fm->_Fclose($fp_user);
						$fm->_Message($fm->LANG['MainMessage'],$fm->LANG['CantPunTimeNotEmpty']);
						break;
					}
			case 3: if ($pun_day <= FM_PUNISH3){
						$fm->_Fclose($fp_user);
						$fm->_Message($fm->LANG['MainMessage'],$fm->LANG['CantPunTimeNotEmpty']);
						break;
					}
					break;
			default:break;
		}
		$fm->LANG['PmMainPun'] = sprintf($fm->LANG['PmMainPun'],$forum_id,$topic_id,$post_id,$allforums[$forum_id]['name'],$list[$topic_id]['name'],$fm->exbb['boardurl']);

		switch ($fm->user['status']) {
			case 'ad':	$status = 'Ad'; break;
			case 'sm':	$status = 'Sm'; break;
			default:	$status = 'Mo'; break;
		}
		$user['punned'][$forum_id.':'.$topic_id.':'.$post_id] = $fm->user['name'].'::'.$status;
		$user['lastpun'] = $fm->_Nowtime;

		switch ($total_pun+1) {
			case 5:		$fm->LANG['PmMainPun'] .= $fm->LANG['PmLastPunMess']; break;
			case 4:		$fm->LANG['PmMainPun'] .= sprintf($fm->LANG['PmSomeBlockPunMess'],FM_PUNISH4); break;
			case 3:		$fm->LANG['PmMainPun'] .= sprintf($fm->LANG['PmSomeBlockPunMess'],FM_PUNISH3); break;
			default:	$fm->LANG['PmMainPun'] .= $fm->LANG['PmButYouMay']; break;
		}
        send_pm($fm->LANG['PmMainPun']);
		$fm->_Write($fp_user,$user);
		$information = $fm->LANG['SuccessfulPunned'];
		$punish_data = PrintUserPunish($user);
	} elseif ($fm->input['doact'] == 'delpun') {
			if ($user_id === 0 || $fm->_Checkuser($user_id) === FALSE) {
				$fm->_Message($fm->LANG['MainMessage'],$fm->LANG['UserNotFound']);
			}
			$user = $fm->_Read2Write($fp_user,'members/'.$user_id.'.php');
            if (($pun_id = $fm->_String('id')) == '' ||!isset($user['punned']) || !is_array($user['punned']) || !isset($user['punned'][$pun_id])){
                $fm->_Fclose($fp_user);
                $fm->_Message($fm->LANG['MainMessage'],$fm->LANG['PunNotFound']);
            }
            unset($user['punned'][$pun_id]);
            if (count($user['punned']) === 0) {
            	unset($user['punned'],$user['lastpun']);
            }
            $fm->_Write($fp_user,$user);
            $information = $fm->LANG['PunRemoved'];
            $punish_data = PrintUserPunish($user);

	} else {
			$list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/list.php');
			if ($topic_id === 0 || !isset($list[$topic_id]) || !file_exists(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/'.$topic_id.'-thd.php')) {
				$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost']);
			}
			$topic = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/'.$topic_id.'-thd.php');
			if ($post_id === 0 || !isset($topic[$post_id])) {
				$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost']);
			}

			$user_id = $topic[$post_id]['p_id'];
			if ($user_id === 0 || $fm->_Checkuser($user_id) === FALSE) {
				$fm->_Message($fm->LANG['MainMessage'],$fm->LANG['UserNotFound']);
			}
			$user = $fm->_Getmember($user_id);

            $forumname = $allforums[$forum_id]['name'];
            $topicname = $list[$topic_id]['name'];

            $information = sprintf($fm->LANG['YouAreSure'],$user['name'],$topicname,$forumname,$forum_id,$topic_id,$post_id);
            $punish_data = PrintUserPunish($user);
	}
} else {
	header("location: index.php");
	exit();
}
$fm->_Title = ' :: '.$fm->LANG['WinTitle'];
include('./templates/'.DEF_SKIN.'/all_header.tpl');
include('./templates/'.DEF_SKIN.'/modules/punish/punish.tpl');
include('./templates/'.DEF_SKIN.'/footer.tpl');
include('page_tail.php');
/*
	Functions
*/

function send_pm($message) {
		global $fm, $user;

        $user['new_pm'] = true;
		$inbox = $fm->_Read2Write($fp_inbox,'messages/'.$user['id'].'-msg.php');
		$inbox[$fm->_Nowtime]['from']	= $fm->user['name'];
		$inbox[$fm->_Nowtime]['title']	= $fm->LANG['YouPunned'];
		$inbox[$fm->_Nowtime]['msg']	= $message;
		$inbox[$fm->_Nowtime]['frid']	= $fm->user['id'];
		$inbox[$fm->_Nowtime]['mail']	= FALSE;
		$inbox[$fm->_Nowtime]['status']	= FALSE;
        $fm->_Write($fp_inbox,$inbox);
        unset($inbox);
}

function PrintUserPunish($user) {
		global $fm, $allforums;

        $punish_data = '';
        if (!isset($user['punned']) || count($user['punned']) === 0) {
        	return $punish_data = '';
        }

        $showrow = TRUE;
        foreach ($user['punned'] as $id => $value){
        		list($forum_id,$topic_id,$post_id) = explode(':',$id);
                $forumname = (isset($allforums[$forum_id])) ? $allforums[$forum_id]['name']:$fm->LANG['Unknow'];
                $list = $fm->_Read(EXBB_DATA_DIR_FORUMS . '/' . $forum_id.'/list.php');
                $topicname = (isset($list[$topic_id])) ? $list[$topic_id]['name']:$fm->LANG['Unknow'];
                list($moder,$status) = explode('::',$value);
                $whoadd = $fm->LANG['Pun'.$status].' - '.$moder;
                include('./templates/'.DEF_SKIN.'/modules/punish/punish_data.tpl');
        }
        return $punish_data;
}
?>
