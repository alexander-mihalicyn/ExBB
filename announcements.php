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
define('IN_EXBB', TRUE);
include('./include/common.php');

$fm->_GetVars();
$fm->_String('action','default');
$fm->_LoadLang('news');
$fm->_Title = ' :: '.$fm->LANG['Announ'];
$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\" src=\"javascript/board.js\"></script>
<script type=\"text/javascript\" language=\"JavaScript\">
var LANG = {
Spoiler: '{$fm->LANG['Spoiler']}',
SpoilerShow: '{$fm->LANG['SpoilerShow']}',
SpoilerHide: '{$fm->LANG['SpoilerHide']}'
};
</script>";
if ($fm->input['action'] != 'default' && defined('IS_ADMIN')) {
	if ($fm->input['action'] == 'add') {
		if ($fm->_Intval('dosave') === 0 || $fm->_String('dosend') == '') {
			$fm->_LoadLang('formcode');
			$preview	= $fm->_String('preview');
            $html 		= $fm->_Boolean($fm->input,'html');
			$PreviewData = $hidden = $NewsText = $NewsTitle = $adminadd = $html_yes = '';

			$ActionTitle	= $fm->LANG['AddNewsTitle'];
			$action			= 'add';
			$html_yes      	= ($html === TRUE) ? ' checked':'';
			$html_no      	= ($html === FALSE) ? ' checked':'';
			$NewsTitle		= ($preview != '') ? $fm->input['title']:'';
			$NewsText		= ($preview != '') ? $fm->input['news']:'';

			if ($preview != '') {
            	$PreviewText    = $fm->formatpost($fm->input['news'],$html);
				$fm->LANG['PreviewTitle']  = ($html === TRUE) ? $fm->html_replace($fm->input['title']):$fm->input['title'];
				include('./templates/'.DEF_SKIN.'/preview.tpl');
			}
			include('./templates/'.DEF_SKIN.'/news_add.tpl');
		} else {
				if ($fm->_POST === FALSE) {
					$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost']);
				}

				if (($NewsTitle = $fm->_String('title')) == '') {
					$fm->_Message($fm->LANG['AddNewsTitle'],$fm->LANG['NewsTitleNeeded']);
				}

				if (($NewsText = $fm->_String('news')) == '') {
					$fm->_Message($fm->LANG['AddNewsTitle'],$fm->LANG['NewsTextNeeded']);
				}
                $TextHash = md5($NewsText);
				if (isset($_SESSION['double'][$TextHash])) {
					$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['DoubleAddedOk'],$_SESSION['double'][$TextHash]);
				}
                $newsdata = $fm->_Read2Write($fp_news,FM_NEWS);
				$newsdata[$fm->_Nowtime]['t'] = $NewsTitle;
				$newsdata[$fm->_Nowtime]['p'] = $NewsText;
				$newsdata[$fm->_Nowtime]['h'] = $fm->_Boolean($fm->input,'html');
				krsort($newsdata);
				$fm->_Write($fp_news,$newsdata);
				$_SESSION['double'][$TextHash] = 'announcements.php';
				$fm->_Message($fm->LANG['AddNewsTitle'],$fm->LANG['NewNewsAddedOk'],'announcements.php');
		}
	} elseif ($fm->input['action'] == 'edit') {
			if ($fm->_Intval('dosave') === 0 || $fm->_String('dosend') == '') {
				$fm->_LoadLang('formcode');
				$newsdata = $fm->_Read(FM_NEWS);
				if (($news_id = $fm->_Intval('number')) == 0 || !isset($newsdata[$news_id])) {
					$fm->_Message($fm->LANG['EditNewsTitle'],$fm->LANG['NewsNotExists']);
				}
				$html 			= $fm->_Boolean($fm->input,'html');
				$PreviewData 	= '';

				$preview		= $fm->_String('preview');
				$ActionTitle	= $fm->LANG['EditNewsTitle'];
				$action			= 'edit';
				$hidden			= '<input type="hidden" name="number" value="'.$news_id.'">';

				if ($preview != '') {
					$html = $fm->_Boolean($fm->input,'html');
					$PreviewText    = $fm->formatpost($fm->input['news'],$html);
            		$fm->LANG['PreviewTitle']   = ($html === TRUE) ? $fm->html_replace($fm->input['title']):$fm->input['title'];
					include('./templates/'.DEF_SKIN.'/preview.tpl');

               		$html_yes      	= ($html === TRUE) ? ' checked':'';
					$html_no      	= ($html === FALSE) ? ' checked':'';

					$NewsTitle		= $fm->input['title'];
					$NewsText		= $fm->input['news'];
				} else {
						$NewsTitle		= $newsdata[$news_id]['t'];
						$NewsText		= $newsdata[$news_id]['p'];
						$html_yes      	= ($newsdata[$news_id]['h'] === TRUE) ? ' checked':'';
						$html_no      	= ($newsdata[$news_id]['h'] === FALSE) ? ' checked':'';
				}
               	include('./templates/'.DEF_SKIN.'/news_add.tpl');
			} else {
					if ($fm->_POST === FALSE) {
						$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',0);
					}

					if (($NewsTitle = $fm->_String('title')) == '') {
						$fm->_Message($fm->LANG['EditNewsTitle'],$fm->LANG['NewsTitleNeeded'],'',0);
					}

					if (($NewsText = $fm->_String('news')) == '') {
						$fm->_Message($fm->LANG['EditNewsTitle'],$fm->LANG['NewsTextNeeded'],'',0);
					}

					$newsdata = $fm->_Read2Write($fp_news,FM_NEWS);
					if (($news_id = $fm->_Intval('number')) == 0 || !isset($newsdata[$news_id])) {
						$fm->_Fclose($fp_news);
						$fm->_Message($fm->LANG['EditNewsTitle'],$fm->LANG['NewsNotExists']);
					}

					$newsdata[$news_id]['t'] = $NewsTitle;
					$newsdata[$news_id]['p'] = $NewsText;
					$newsdata[$news_id]['h'] = $fm->_Boolean($fm->input,'html');
					krsort($newsdata);
					$fm->_Write($fp_news,$newsdata);
					$fm->_Message($fm->LANG['EditNewsTitle'],$fm->LANG['NewsEditedOk'],'announcements.php');
			}
	} elseif ($fm->input['action'] == 'delall') {
			if ($fm->_POST === TRUE) {
				$fm->_Read2Write($fp_news,FM_NEWS);
				$fm->_Write($fp_news,array());
				$fm->_Message($fm->LANG['DelNews'],$fm->LANG['AllNewsDeletedOk'],'announcements.php');
			} else {
					$formtitle      = $fm->LANG['DelRequesting'];
					$hiddinfield    = '<input name="action" type="hidden" value="delall">';
					$request_text   = $fm->LANG['SureDelAllNews'].$fm->LANG['RequestThisAction'];
					include('./templates/'.DEF_SKIN.'/request_form.tpl');
                    $newsbody = $RequestForm;
                    unset($RequestForm);
			}
	} elseif ($fm->input['action'] == 'delete') {
			$newsdata = $fm->_Read2Write($fp_news,FM_NEWS);
			if (($news_id = $fm->_Intval('number')) == 0 || !isset($newsdata[$news_id])) {
				$fm->_Fclose($fp_news);
				$fm->_Message($fm->LANG['DelNews'],$fm->LANG['NewsNotExists']);
			}
			if ($fm->_POST === TRUE) {
				unset($newsdata[$news_id]);
				$fm->_Write($fp_news,$newsdata);
				$fm->_Message($fm->LANG['DelNews'],$fm->LANG['SelectedNewsDeletedOk'],'announcements.php');
			} else {
					$fm->_Fclose($fp_news);
					$NewsTitle		= ($newsdata[$news_id]['h'] === TRUE) ? $fm->html_replace($newsdata[$news_id]['t']):$newsdata[$news_id]['t'];
					$formtitle      = $fm->LANG['DelRequesting'];
					$hiddinfield    = '<input name="action" type="hidden" value="delete">
										<input name="number" type="hidden" value="'.$news_id.'">';
					$request_text   = sprintf($fm->LANG['SureDelSelectedNews'],$NewsTitle).$fm->LANG['RequestThisAction'];
					include('./templates/'.DEF_SKIN.'/request_form.tpl');
                    $newsbody = $RequestForm;
                    unset($RequestForm);
			}
	}
} else {
		$announcements = $fm->_Read(FM_NEWS);
		$totals = count($announcements);
		if ($totals == 0) {
			$dateposted = time();
			$announcements[$dateposted]['t'] = $fm->LANG['EmptyNewsTitle'];
			$announcements[$dateposted]['p'] = $fm->LANG['EmptyNews'];
			$announcements[$dateposted]['h'] = FALSE;
		}
		$newsbody = $adminadd = '';
		foreach ($announcements as $id=>$info) {
				$dateposted = $fm->_DateFormat($id+$fm->user['timedif']*3600);
				$title	= ($info['h'] === TRUE) ? $fm->html_replace($info['t']):$info['t'];
				$news	= $fm->formatpost($info['p'],$info['h']);
				include('./templates/'.DEF_SKIN.'/news_data.tpl');
		}
}

include('./templates/'.DEF_SKIN.'/all_header.tpl');
include('./templates/'.DEF_SKIN.'/logos.tpl');
include('./templates/'.DEF_SKIN.'/news_show.tpl');
include('./templates/'.DEF_SKIN.'/footer.tpl');
include('page_tail.php');
?>
