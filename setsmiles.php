<?php
/****************************************************************************
* ExBB v.1.9                                                              	*
* Copyright (c) 2002-20xx by Alexander Subhankulov aka Warlock            	*
*                                                                         	*
* http://www.exbb.revansh.com                                             	*
* email: admin@exbb.revansh.com                                           	*
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
$fm->_LoadLang('setsmiles',TRUE);

$curcatid	= $fm->_Intval('cat');
$smilesdir	= './im/emoticons/';
$sm_list	= $fm->_Read2Write($fp_sm,FM_SMILES);
$sm_list	= (count($sm_list) !== 0) ? $sm_list:array('cats'=>array());

if ($fm->input['action'] == "newcat") {
	if ($fm->_String('SaveCat') != '' && $fm->_POST === TRUE) {

		if ($fm->input['newcatdesc'] == '') {
			$fm->_Fclose($fp_sm);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['CatDescNotSet'],'',1);
		}

		$newcatid = 1;
		if (sizeof($sm_list['cats']) > 0) {
			ksort($sm_list['cats'],SORT_NUMERIC);
			end($sm_list['cats']);
			$newcatid = key($sm_list['cats'])+1;
		}

		$sm_list['cats'][$newcatid] = $fm->input['newcatdesc'];
		$fm->_Write($fp_sm,$sm_list);
		$fm->_WriteLog($fm->LANG['LogNewCat'],1);
		$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['NewCatAddedOk'],'setsmiles.php?cat='.$newcatid,1);
	} else {
			$fm->_Fclose($fp_sm);
			$hidden			= '<input type="hidden" name="action" value="newcat"/>';
			$descfieldvalue = '';
			$descfieldtitle = $fm->LANG['EnterNewCatName'];
			$inputtitle		= $fm->LANG['CreateNewCat'];
			$tabletitle		= $fm->LANG['NewCatAdding'];
			include('./admin/all_header.tpl');
			include('./admin/nav_bar.tpl');
			include('./admin/smiles_editcat.tpl');
	}
} elseif ($fm->input['action'] == "editcat") {
		if ($curcatid === 0 || !isset($sm_list['cats'][$curcatid])) {
			$fm->_Fclose($fp_sm);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['CatNotfound'],'',1);
		}

		if ($fm->_String('SaveCat') != '' && $fm->_POST === TRUE) {
			if ($fm->input['newcatdesc'] == '') {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['NewCatNameNotSet'],'',1);
			}

			$sm_list['cats'][$curcatid] = $fm->input['newcatdesc'];
			$fm->_Write($fp_sm,$sm_list);
			$fm->_WriteLog($fm->LANG['LogEditCat'],1);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['CatRenamedOk'],'setsmiles.php?cat='.$curcatid,1);
		} else {
				$fm->_Fclose($fp_sm);
				$curcatdesc = $sm_list['cats'][$curcatid];
				$hidden = '<input type="hidden" name="action" value="editcat"/>
							<input type="hidden" name="cat" value="'.$curcatid.'"/>';
				$descfieldvalue = $curcatdesc;
				$descfieldtitle = $fm->LANG['ChangeCatName'];
				$inputtitle 	= $fm->LANG['SaveChange'];
				$tabletitle 	= sprintf($fm->LANG['CatNameTitle'],$curcatdesc);
				include('./admin/all_header.tpl');
				include('./admin/nav_bar.tpl');
				include('./admin/smiles_editcat.tpl');
		}
} elseif ($fm->input['action'] == "delcat") {
		if ($curcatid === 0 || !isset($sm_list['cats'][$curcatid])) {
			$fm->_Fclose($fp_sm);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['CatNotfound'],'',1);
		}

		unset($sm_list['cats'][$curcatid]);

		$sm_array = $sm_list['smiles'];
		foreach ($sm_array as $code => $smilearray) {
				if ($curcatid === $smilearray['cat']) {
					unset($sm_list['smiles'][$code]);
				}
		}
		$fm->_Write($fp_sm,$sm_list);
		$fm->_WriteLog($fm->LANG['LogDelCat'],1);
		$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['CatDeletedOk'],'setsmiles.php',1);
} elseif ($fm->input['action'] == "addnew") {
		if ($curcatid === 0 || !isset($sm_list['cats'][$curcatid])) {
			$fm->_Fclose($fp_sm);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['CatNotfound'],'',1);
		}

		if ($fm->_String('SaveSmile') != '' && $fm->_POST === TRUE) {

			if (($sm_code = $fm->_String('sm_code')) == '') {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileCodeNotSet'],'',1);
			}

			if ($fm->input['sm_emotion'] == '') {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileDescNotSet'],'',1);
			}

			if (!preg_match("#^[A-Za-z0-9-_]{1,20}\.[A-Za-z]{3,4}$#is",$fm->input['sm_img']) || !file_exists($smilesdir.$fm->input['sm_img'])) {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmFileNotFound'],'',1);
			}

			if (isset($sm_list['smiles'][$sm_code])) {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileExists'],'',1);
			}

			$id = 1;
			if (sizeof($sm_list['smiles']) > 1) {
				uasort($sm_list['smiles'],'sort_by_id');
				end($sm_list['smiles']);
				$id = $sm_list['smiles'][key($sm_list['smiles'])]['id']+1;
			}

			$sm_list['smiles'][$sm_code]['img'] = $fm->input['sm_img'];
			$sm_list['smiles'][$sm_code]['emt'] = $fm->input['sm_emotion'];
			$sm_list['smiles'][$sm_code]['id']	= $id;
			$sm_list['smiles'][$sm_code]['cat'] = $curcatid;
			$fm->_Write($fp_sm,$sm_list);
			$fm->_WriteLog($fm->LANG['LogSmAdd'],1);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileAddedOk'],'setsmiles.php?cat='.$curcatid,1);
		} else {
				$fm->_Fclose($fp_sm);
				$imgoption	= $option = $cur_smile = '';
				foreach ($sm_list['cats'] as $catid => $catname) {
						$selected = ($curcatid === $catid) ? ' selected':'';
						$option .= "\n<OPTION VALUE=\"".$catid."\"".$selected.">".$catname."</OPTION>";
				}

				$d = dir($smilesdir);
				while (false !== ($file = $d->read())) {
						if (is_dir($smilesdir.$file) || !getimagesize($smilesdir.$file)) {
							continue;
						}
						if (empty($cur_smile)) {
							$cur_smile = $file;
						}
						$imgoption .= "\n<OPTION VALUE=\"".$file."\">".$file."</OPTION>";
				}
				$d->close();

				if ($imgoption == '') {
					$fm->_Message($fm->LANG['AdminSmiles'],sprintf($fm->LANG['SmDirEmpty'],$smilesdir),'',1);
				}
				$code			= "::".$cur_smile."::";
				$sm_emt			= $fm->LANG['NoDesc'];
				$tabletitle		= $fm->LANG['AddSmileInCat']." <SELECT NAME=\"cat\">\n".$option."\n</SELECT>";
				$selectsmile	= "<SELECT NAME=\"sm_img\" ONCHANGE=\"show_smiley(this.options[selectedIndex].value);\">\n".$imgoption."\n</SELECT>";
				$hidden_field	= '<input type="hidden" name="action" value="addnew">';
				include('./admin/all_header.tpl');
				include('./admin/nav_bar.tpl');
				include('./admin/smiles_add.tpl');
				include('./admin/footer.tpl');
		}
} elseif ($fm->input['action'] == "edit") {
		if ($curcatid === 0 || !isset($sm_list['cats'][$curcatid])) {
			$fm->_Fclose($fp_sm);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['CatNotfound'],'',1);
		}

        $id = $fm->_Intval('id');

		$findarray =  array_filter($sm_list['smiles'], "array_search_id");
		if (sizeof($findarray) === 0) {
			$fm->_Fclose($fp_sm);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileIdNotExists'],'',1);
		}

		$oldcode = key($findarray);
		unset($findarray);

		if ($fm->_String('SaveSmile') != '' && $fm->_POST === TRUE) {

			if (($sm_code = $fm->_String('sm_code')) == '') {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileCodeNotSet'],'',1);
			}

			if ($fm->input['sm_emotion'] == '') {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileDescNotSet'],'',1);
			}

			if (!preg_match("#^[A-Za-z0-9-_]{1,20}\.[A-Za-z]{3,4}$#is",$fm->input['sm_img']) || !file_exists($smilesdir.$fm->input['sm_img'])) {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmFileNotFound'],'',1);
			}

			if (isset($sm_list['smiles'][$sm_code]) && $sm_list['smiles'][$sm_code]['id'] !== $id) {
				$fm->_Fclose($fp_sm);
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileIdExists'],'',1);
			}
			unset($sm_list['smiles'][$oldcode]);

			$sm_list['smiles'][$sm_code]['img'] = $fm->input['sm_img'];
			$sm_list['smiles'][$sm_code]['emt'] = $fm->input['sm_emotion'];
			$sm_list['smiles'][$sm_code]['id']	= $id;
			$sm_list['smiles'][$sm_code]['cat']	= $curcatid;
			$fm->_Write($fp_sm,$sm_list);
			$fm->_WriteLog($fm->LANG['LogSmEdit'],1);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileSavedOk'],'setsmiles.php?cat='.$curcatid,1);
		} else {
				$fm->_Fclose($fp_sm);
				$option = $filename_list = '';
				foreach ($sm_list['cats'] as $catid => $catname) {
						$selected = ($curcatid === $catid) ? ' selected':'';
						$option .= "\n<OPTION VALUE=\"".$catid."\"".$selected.">".$catname."</OPTION>";
				}

				foreach ($sm_list['smiles'] as $code => $smilearray) {
						if ($id == $smilearray['id']) {
							$sm_selected = 'selected="selected"';
							$cur_smile	= $smilearray['img'];
							$sm_emt		= $smilearray['emt'];
							$sm_code 	= $code;
						} else {
								$sm_selected = '';
						}
						$filename_list .= "\n<option value=\"" . $smilearray['img'] . "\"" . $sm_selected . ">" . $smilearray['img'] . "</option>";
				}

				$code 			= $sm_code;
				$tabletitle		= $fm->LANG['SmileEditing']."<SELECT NAME=\"cat\">\n".$option."\n</SELECT>";
				$selectsmile	= "<SELECT NAME=\"sm_img\" ONCHANGE=\"show_smiley(this.options[selectedIndex].value);\">\n".$filename_list."\n</SELECT>";
				$hidden_field 	=  '<input type="hidden" name="action" value="edit">
									<input type="hidden" name="id" value="'.$id.'">';
				include('./admin/all_header.tpl');
				include('./admin/nav_bar.tpl');
				include('./admin/smiles_add.tpl');
		}
} elseif ($fm->input['action'] == "delete") {
		if ($curcatid === 0 || !isset($sm_list['cats'][$curcatid])) {
			$fm->_Fclose($fp_sm);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['CatNotfound'],'',1);
		}

        $id = $fm->_Intval('id');

		$findarray =  array_filter($sm_list['smiles'], "array_search_id");
		if (sizeof($findarray) === 0) {
			$fm->_Fclose($fp_sm);
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileIdNotExists'],'',1);
		}

		$code = key($findarray);
		unset($sm_list['smiles'][$code],$findarray);
		$fm->_Write($fp_sm,$sm_list);
		$fm->_WriteLog($fm->LANG['LogSmDel'],1);
		$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileDeletedOk'],'setsmiles.php?cat='.$curcatid,1);
} elseif ($fm->input['action'] == "addgroup") {
		$olddir	= 'im/emoticons/temp/';
		if (!is_dir($olddir)) {
			$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['TempDirNotExists'],'',1);
		}
		if ($fm->_String('DoAddGroup') != '' && $fm->_POST === TRUE) {
			if (count($fm->_Array('smile')) === 0) {
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileIdNotExists'],'',1);
			}

			$id = 1;
			if (sizeof($sm_list['smiles']) > 1) {
				uasort($sm_list['smiles'],'sort_by_id');
				end($sm_list['smiles']);
				$id = $sm_list['smiles'][key($sm_list['smiles'])]['id']+1;
			}

			$total_input	= count($fm->input['smile']);
			$added			= 0;
			foreach ($fm->input['smile'] as $smile) {
					$sm_code	= $smile['code'];
					$sm_catid	= $smile['catid'];
                    $oldsmile	= $olddir.$smile['file'];
					$newsmile	= $smilesdir.$smile['file'];

					if (!preg_match("#^[A-Za-z0-9-_]{1,20}\.[A-Za-z]{3,4}$#is",$smile['file']) || !file_exists($oldsmile)) {
						continue;
					}

					if (!isset($sm_list['cats'][$sm_catid])) {
                    	continue;
					}

					if (isset($sm_list['smiles'][$sm_code])) {
                    	continue;
					}

					if (!copy($oldsmile, $newsmile)) {
						continue;
					}

					$sm_list['smiles'][$sm_code]['img'] = $smile['file'];
					$sm_list['smiles'][$sm_code]['emt'] = $smile['desc'];
					$sm_list['smiles'][$sm_code]['id']	= $id;
					$sm_list['smiles'][$sm_code]['cat']	= $sm_catid;

					@chmod($newsmile,$fm->exbb['ch_upfiles']);
					unlink($oldsmile);
                    $id++;$added++;
			}
			$fm->_Write($fp_sm,$sm_list);
			$message = ($total_input > $added) ? sprintf($fm->LANG['SmPartAddedOk'],$total_input,$olddir,$added,$olddir,$smilesdir):$fm->LANG['SmGroupAddedOk'];
			$fm->_WriteLog($fm->LANG['LogAddGroup'],1);
			$fm->_Message($fm->LANG['AdminSmiles'],$message,'setsmiles.php',1);
		} else {
				$fm->_Fclose($fp_sm);
				if (count($sm_list['cats']) > 0) {
					$smoption = $datashow = '';
					foreach ($sm_list['cats'] as $catid => $catname) {
							$smoption .= '<OPTION VALUE="'.$catid.'">'.$catname.'</OPTION>';
					}

					$d = dir($olddir);
					$a = 1;
					while (false !== ($file = $d->read())) {
						if (is_dir($olddir.$file) || !getimagesize($olddir.$file)) {
							continue;
						}
						$code = '::'.preg_replace("#\.[A-Za-z]{3,4}$#is", "", $file ).'::';
						$desc = $fm->LANG['NoDesc'];
						include('admin/smiles_addgroup_data.tpl');
						$a++;
					}
					$d->close();

					if ($datashow == ''){
						$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['NoSmilesInTemp'],'',1);
					}
					include('admin/all_header.tpl');
					include('admin/nav_bar.tpl');
					include('admin/smiles_addgroup.tpl');
					include('admin/footer.tpl');
				} else {
						$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileNoCats'],'',1);
				}
		}
} else {
		$fm->_Fclose($fp_sm);
		if (count($sm_list['cats']) > 0) {
			if ($curcatid === 0 || !isset($sm_list['cats'][$curcatid])) {
				ksort($sm_list['cats'],SORT_NUMERIC);
				reset($sm_list['cats']);
				$curcatid = key($sm_list['cats']);
			}
			$curcatdesc	= $sm_list['cats'][$curcatid];

			$smoption	= $datashow = '';
			foreach ($sm_list['cats'] as $catid => $catname) {
					$selected = ($curcatid === $catid) ? ' selected':'';
					$smoption .= '<OPTION VALUE="'.$catid.'"'.$selected.'>'.$catname.'</OPTION>';
			}

			$smiles_list = array_filter($sm_list['smiles'], "get_smilescat");

			if (count($smiles_list)) {
				uasort($smiles_list,'sort_by_id');
				$back_clr = 'row1';
				foreach ($smiles_list as $code=>$data) {
						$back_clr = ($back_clr == 'row1') ? 'row2' : 'row1';
						include('admin/smiles_data.tpl');
				}
			} else {
					$datashow = '<tr><td colspan="5" align="center">'.$fm->LANG['SmilesNotSet'].'</td></tr>';
			}
		} else {
				$fm->_Message($fm->LANG['AdminSmiles'],$fm->LANG['SmileNoCats'],'',1);
		}
		include('./admin/all_header.tpl');
		include('./admin/nav_bar.tpl');
		include('./admin/smiles_show.tpl');
		include('./admin/footer.tpl');
}
include('page_tail.php');

/*
	Functions
*/
function sort_by_id($a, $b) {
		if ($a['id'] == $b['id']) {
			return 0;
		}
		return ($a['id'] < $b['id']) ? -1 : 1;
}

function get_smilescat($var) {
		global $curcatid;
		return ($curcatid == $var['cat']);
}

function array_search_id($var) {
		global $id;
		return ($id == $var['id']);
}
?>
