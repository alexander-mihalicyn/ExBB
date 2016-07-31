<?php
/***************************************************************************
* "Members Control" mods for  ExBB v.1.9.1                                 *
* Copyright (c) 2004 by Alisher Mutalov aka Markus®                        *
*                                                                          *
* http://www.tvoyweb.ru                                                    *
* http://www.tvoyweb.ru/forums/                                            *
* email: admin@tvoyweb.ru                                                  *
*                                                                          *
***************************************************************************/
if (!defined('IN_EXBB')) die('Hack attempt!');
$fm->_LoadModuleLang('memcontrol');

$fm->_String('action');

switch ($fm->input['action']) {
	case 'mail'			: user_mail();	break;
	case 'deletemember'	: deletemember();	break;
	default				: memlist();		break;
}

function memlist() {
		global $fm;

		$sort 	= $fm->_String('s');
		$order 	= $fm->_String('order', 'ASC');

        $users = $fm->_Read(EXBB_DATA_USERS_LIST);
		switch ($sort) {
			case 'p': 	uasort($users, 'sort_by_post');
						break;
			case 'n': 	uasort($users, 'sort_by_name');
						break;
			default : 	ksort($users,SORT_NUMERIC);
						break;
		}

		if ($order == 'DESC') $users = array_reverse($users,TRUE);

		$ASC_selcted	= ($order == 'ASC') ? ' selected="selected"':'';
		$DESC_selcted	= ($order == 'DESC') ? ' selected="selected"':'';

		$d_selected		= ($sort === 'd') ? ' selected="selected"':'';
		$p_selected		= ($sort === 'p') ? ' selected="selected"':'';
		$n_selected		= ($sort === 'n') ? ' selected="selected"':'';

		$per_page  = abs($fm->_Intval('pg', 25));
		$get_param = 'setmodule.php?module=memcontrol&s='.$sort.'&order='.$order.'&p={_P_}&pg='.$per_page;
	
		$pages = Print_Paginator(count($users),$get_param,$per_page,8,$first,TRUE);

		$userskeys = array_slice(array_keys($users),$first,$per_page);
		$memb_data = '';
		foreach ($userskeys as $key => $user_id) {
				$user	= $fm->_Getmember($user_id);
				$name 	= $user['name'];
				$posts 	= $user['posts'];

				switch ($user['status']) {
					case 'ad'		:	$status = $fm->LANG['Admin'];
										break;
					case 'sm'		:	$status = $fm->LANG['SuperModer'];
										break;
					case 'me'		:	$status = $fm->LANG['User'];
										break;
					case 'banned'	:	$status = $fm->LANG['Banned'];
										break;
				}

				$location 	= $user['location'];
				$joined 	= date("d.m.Y", $user['joined']);
				$email	= '<a href="tools.php?action=mail&member='.$user_id.'" target="_blank">'.$fm->LANG['Write'].'</a>';
				$class	= (!($key % 2)) ? 'row1' : 'row4';
				include('modules/memcontrol/admintemplates/memb_data.tpl');
				unset($user);
		}
		include('admin/all_header.tpl');
		include('admin/nav_bar.tpl');
		include('modules/memcontrol/admintemplates/memblist.tpl');
		include('admin/footer.tpl');
}

function deletemember() {
		global $fm;

		if (count($del_ids = $fm->_Array('del')) === 0) {
			$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['UsersNotSelected'],'',1);
		}

		if ($fm->_String('mode') !== '') delet_mail();

		$deletedTotal = 0;
		$users = $fm->_Read2Write($fp_users,EXBB_DATA_USERS_LIST);
		foreach ($del_ids as $user_id) {
				if (file_exists(EXBB_DATA_DIR_MEMBERS . '/'.$user_id.'.php')) unlink(EXBB_DATA_DIR_MEMBERS . '/'.$user_id.'.php');
				if (isset($users[$user_id])) unset($users[$user_id]);
				$deletedTotal++;
		}
		$fm->_Write($fp_users,$users);
		$fm->_SAVE_STATS(array ('totalmembers' => array($deletedTotal, -1)));
		$redir = '';
		if ($fm->_String('s')) $redir .= '&s='.$fm->input['s'];
		if ($fm->_String('order')) $redir .= '&order='.$fm->input['order'];
		if ($fm->_Intval('p')) $redir .= '&p='.$fm->input['p'];
		if ($fm->_Intval('pg')) $redir .= '&pg='.$fm->input['pg'];
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['UsersDeleteOk'],'setmodule.php?module=memcontrol'.$redir,1);
}

function delet_mail() {
		global $fm;
		$email = sprintf($fm->LANG['DelMailText'],
                    				$fm->exbb['boardname'],
                    				$fm->exbb['boardname'],
                    				$fm->exbb['boardurl']);
		$fm->_Mail($fm->exbb['boardname'],$fm->user['mail'],$fm->input['del'],$fm->LANG['DelMailSubject'],$email);
		
		$redir = '';
		if ($fm->_String('s')) $redir .= '&s='.$fm->input['s'];
		if ($fm->_String('order')) $redir .= '&order='.$fm->input['order'];
		if ($fm->_Intval('p')) $redir .= '&p='.$fm->input['p'];
		if ($fm->_Intval('pg')) $redir .= '&pg='.$fm->input['pg'];
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['DelMailSendedOk'],'setmodule.php?module=memcontrol'.$redir,1);
}
?>
