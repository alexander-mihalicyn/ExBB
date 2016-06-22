<?php
/****************************************************************************
* ExBB v.1.9                                                              	*
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
define('IN_ADMIN', true);
define('IN_EXBB', TRUE);

include('./include/common.php');
$fm->_GetVars();
$fm->_String('action');
$fm->_LoadLang('setranks',TRUE);

if ($fm->input['action'] == 'doadd' || $fm->input['action'] == 'doedit') {

	if ($fm->_POST === FALSE) {
			$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
	} elseif ($fm->input['title'] == '') {
			$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankNoRank'],'',1);
	} elseif (($posts =$fm->_Intval('min_posts')) == 0) {
			$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankNoPost'],'',1);
	} elseif ($fm->input['rank_image'] == '') {
			$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankNoImage'],'',1);
	} elseif (!preg_match("#^[A-Za-z0-9-_]{1,20}\.[A-Za-z]{3,4}$#is",$fm->input['rank_image']) || !file_exists('im/images/'.$fm->input['rank_image'])) {
			$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankImgNotExists'],'',1);
	} else {
			$ranks = $fm->_Read2Write($fp_ranks,FM_TITLES,FALSE);
			if ($fm->input['action'] == 'doedit') {
				if (($rank_id = $fm->_Intval('id')) === 0) {
					$fm->_Fclose($fp_ranks);
					$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
				} elseif (!isset($ranks[$rank_id])) {
						$fm->_Fclose($fp_ranks);
						$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankNotFound'],'',1);
				}
				$action_message = $fm->LANG['RankEditedOk'];
			} else {
					$title = $fm->input['title'];
					if (count(array_filter($ranks,'existing_rank')) != 0) {
						$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankMinExists'],'',1);
					}
					ksort($ranks);
					reset($ranks);
					end($ranks);
					$rank_id = key($ranks) +1;
					$action_message = $fm->LANG['RankAddedOk'];
			}
	}

	$ranks[$rank_id]['title']	= $fm->input['title'];
	$ranks[$rank_id]['posts']	= $fm->input['min_posts'];
	$ranks[$rank_id]['icon']	= $fm->input['rank_image'];
	uasort($ranks,'sort_by_minposts');
	$fm->_Write($fp_ranks,$ranks);

	$fm->_WriteLog(($fm->input['action'] == 'doadd') ? $fm->LANG['LogAddNew']:$fm->LANG['LogEdit'],1);
	$fm->_Message($fm->LANG['AdminRanks'],$action_message,'setranks.php',1);
} elseif ($fm->input['action'] == 'add' || $fm->input['action'] == 'edit') {
		$hidden = $ranks['title'] = $ranks['posts'] = $ranks['icon'] = '';
		if ($fm->input['action'] == 'edit') {
			if (($rank_id = $fm->_Intval('id')) === 0) {
				$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
			}

			$ranks = $fm->_Read(FM_TITLES,FALSE);
			if (!isset($ranks[$rank_id])) {
				$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankNotFound'],'',1);
			}
			$ranks = $ranks[$rank_id];
			$hidden = '<input type=hidden name="id" value="'.$rank_id.'">';
		}
		$ActionTitleDesc 	= ($fm->input['action'] == 'add') ? $fm->LANG['ActionAddDesc']:sprintf($fm->LANG['ActionEditDesc'],$ranks['title']);
		$ActionTitle 		= ($fm->input['action'] == 'add') ? $fm->LANG['ActionAdd']:$fm->LANG['ActionEdit'];
		include('./admin/all_header.tpl');
		include('./admin/nav_bar.tpl');
		include('./admin/ranks_add.tpl');
		include('./admin/footer.tpl');
} elseif ($fm->input['action'] == 'delete') {
		if (($rank_id = $fm->_Intval('id')) === 0) {
			$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
		}

		$ranks = $fm->_Read2Write($fp_ranks,FM_TITLES,FALSE);
		if (!isset($ranks[$rank_id])) {
			$fm->_Fclose($fp_ranks);
			$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankNotFound'],'',1);
		}

		unset($ranks[$rank_id]);
		uasort($ranks,'sort_by_minposts');
		$fm->_Write($fp_ranks,$ranks);
		$fm->_WriteLog($fm->LANG['RankDelete'],1);
		$fm->_Message($fm->LANG['AdminRanks'],$fm->LANG['RankDeletedOk'],'setranks.php',1);
} else {
		$allranks	= $fm->_Read(FM_TITLES,FALSE);
		$back_clr	= 'row1';
		$ranksdata	= '';
		foreach	($allranks as $id => $rank)  {
				$back_clr	= ($back_clr == 'row1') ? 'row2' : 'row1';
				include('./admin/ranks_data.tpl');
		}
		include('./admin/all_header.tpl');
		include('./admin/nav_bar.tpl');
		include('./admin/ranks_show.tpl');
		include('./admin/footer.tpl');
}
include('page_tail.php');

/*
	Functions
*/

function sort_by_minposts($a, $b) {
		if ($a['posts'] == $b['posts']) return 0;
		return ($a['posts'] < $b['posts']) ? -1 : 1;
}

function existing_rank($a) {
		global $posts, $title;
		return ($a['posts'] == $posts || $a['title'] == $title);
}
?>
