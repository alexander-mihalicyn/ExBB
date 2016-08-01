<?php
/****************************************************************************
 * ExBB v.1.9                                                                *
 * Copyright (c) 2002-20хх by Alexander Subhankulov aka Warlock                *
 *                                                                            *
 * http://www.exbb.net                                                        *
 * email: admin@exbb.net                                                    *
 *                                                                            *
 ****************************************************************************/
/****************************************************************************
 *                                                                            *
 *   This program is free software; you can redistribute it and/or modify    *
 *   it under the terms of the GNU General Public License as published by    *
 *   the Free Software Foundation; either version 2 of the License, or        *
 *   (at your option) any later version.                                    *
 *                                                                            *
 ****************************************************************************/
define('IN_ADMIN', true);
define('IN_EXBB', true);

include( './include/common.php' );

$fm->_GetVars();

$fm->_String('action', 'main');
$fm->_LoadLang('setvariables', true);

if (isset( $fm->input['save'] ) && $fm->_POST) {
	save_info();
}

if ($fm->input['action'] == 'secure') {
	$bot_yes = ( $fm->exbb['anti_bot'] ) ? 'checked="checked"' : '';
	$bot_no = ( !$fm->exbb['anti_bot'] ) ? 'checked="checked"' : '';

	$passverif_yes = ( $fm->exbb['passwordverification'] ) ? 'checked="checked"' : '';
	$passverif_no = ( !$fm->exbb['passwordverification'] ) ? 'checked="checked"' : '';

	$newuser_yes = ( $fm->exbb['newusernotify'] ) ? 'checked="checked"' : '';
	$newuser_no = ( !$fm->exbb['newusernotify'] ) ? 'checked="checked"' : '';

	$img_yes = ( $fm->exbb['show_img'] ) ? 'checked="checked"' : '';
	$img_no = ( !$fm->exbb['show_img'] ) ? 'checked="checked"' : '';

	$reg_yes = ( $fm->exbb['reg_on'] ) ? 'checked="checked"' : '';
	$reg_no = ( !$fm->exbb['reg_on'] ) ? 'checked="checked"' : '';

	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/board_secure.tpl' );
	include( './admin/footer.tpl' );
}
elseif ($fm->input['action'] == 'posts') {
	$upp_yes = ( $fm->exbb['userperpage'] ) ? 'checked="checked"' : '';
	$upp_no = ( !$fm->exbb['userperpage'] ) ? 'checked="checked"' : '';

	$loc_yes = ( $fm->exbb['location'] ) ? 'checked="checked"' : '';
	$loc_no = ( !$fm->exbb['location'] ) ? 'checked="checked"' : '';

	$mpost_yes = ( $fm->exbb['mail_posts'] ) ? 'checked="checked"' : '';
	$mpost_no = ( !$fm->exbb['mail_posts'] ) ? 'checked="checked"' : '';

	$subinfo_yes = ( $fm->exbb['sub_main_info'] ) ? 'checked="checked"' : '';
	$subinfo_no = ( !$fm->exbb['sub_main_info'] ) ? 'checked="checked"' : '';

	$hints_yes = ( $fm->exbb['show_hints'] ) ? 'checked="checked"' : '';
	$hints_no = ( !$fm->exbb['show_hints'] ) ? 'checked="checked"' : '';

	$botlight_yes = ( $fm->exbb['botlight'] ) ? ' checked="checked"' : '';
	$botlight_no = ( !$fm->exbb['botlight'] ) ? ' checked="checked"' : '';

	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/board_posts.tpl' );
	include( './admin/footer.tpl' );
}
elseif ($fm->input['action'] == 'main') {
	$ch_upfiles = '0' . base_convert($fm->exbb['ch_upfiles'], 10, 8);
	$ch_files = '0' . base_convert($fm->exbb['ch_files'], 10, 8);
	$ch_dirs = '0' . base_convert($fm->exbb['ch_dirs'], 10, 8);

	$board_disable_yes = ( $fm->exbb['board_closed'] ) ? 'checked="checked"' : '';
	$board_disable_no = ( !$fm->exbb['board_closed'] ) ? 'checked="checked"' : '';

	$ru_nicks_yes = ( $fm->exbb['ru_nicks'] ) ? 'checked="checked"' : '';
	$ru_nicks_no = ( !$fm->exbb['ru_nicks'] ) ? 'checked="checked"' : '';

	$reg_smpl_yes = ( $fm->exbb['reg_simple'] ) ? 'checked="checked"' : '';
	$reg_smpl_no = ( !$fm->exbb['reg_simple'] ) ? 'checked="checked"' : '';

	$news_yes = ( $fm->exbb['announcements'] ) ? 'checked="checked"' : '';
	$news_no = ( !$fm->exbb['announcements'] ) ? 'checked="checked"' : '';

	$gzip_yes = ( $fm->exbb['gzip_compress'] ) ? 'checked="checked"' : '';
	$gzip_no = ( !$fm->exbb['gzip_compress'] ) ? 'checked="checked"' : '';

	$log_yes = ( $fm->exbb['log'] ) ? 'checked="checked"' : '';
	$log_no = ( !$fm->exbb['log'] ) ? 'checked="checked"' : '';

	$pm_yes = ( $fm->exbb['pm'] ) ? 'checked="checked"' : '';
	$pm_no = ( !$fm->exbb['pm'] ) ? 'checked="checked"' : '';

	$txtmenu_yes = ( $fm->exbb['text_menu'] ) ? 'checked="checked"' : '';
	$txtmenu_no = ( !$fm->exbb['text_menu'] ) ? 'checked="checked"' : '';

	$exbbcodes_yes = ( $fm->exbb['exbbcodes'] ) ? 'checked="checked"' : '';
	$exbbcodes_no = ( !$fm->exbb['exbbcodes'] ) ? 'checked="checked"' : '';

	$emoticons_yes = ( $fm->exbb['emoticons'] ) ? 'checked="checked"' : '';
	$emoticons_no = ( !$fm->exbb['emoticons'] ) ? 'checked="checked"' : '';

	$ratings_yes = ( $fm->exbb['ratings'] ) ? 'checked="checked"' : '';
	$ratings_no = ( !$fm->exbb['ratings'] ) ? 'checked="checked"' : '';

	$censoring_yes = ( $fm->exbb['wordcensor'] ) ? 'checked="checked"' : '';
	$censoring_no = ( !$fm->exbb['wordcensor'] ) ? 'checked="checked"' : '';

	$file_upload_yes = ( $fm->exbb['file_upload'] ) ? 'checked="checked"' : '';
	$file_upload_no = ( !$fm->exbb['file_upload'] ) ? 'checked="checked"' : '';

	$autoup_yes = ( $fm->exbb['autoup'] ) ? 'checked="checked"' : '';
	$autoup_no = ( !$fm->exbb['autoup'] ) ? 'checked="checked"' : '';

	$sig_yes = ( $fm->exbb['sig'] ) ? 'checked="checked"' : '';
	$sig_no = ( !$fm->exbb['sig'] ) ? 'checked="checked"' : '';

	$avatars_yes = ( $fm->exbb['avatars'] ) ? 'checked="checked"' : '';
	$avatars_no = ( !$fm->exbb['avatars'] ) ? 'checked="checked"' : '';

	$avatars_up_yes = ( $fm->exbb['avatar_upload'] ) ? 'checked="checked"' : '';
	$avatars_up_no = ( !$fm->exbb['avatar_upload'] ) ? 'checked="checked"' : '';

	$emails_yes = ( $fm->exbb['emailfunctions'] ) ? 'checked="checked"' : '';
	$emails_no = ( !$fm->exbb['emailfunctions'] ) ? 'checked="checked"' : '';

	$hideLinksFromGuests_yes = ( $fm->exbb['hideLinksFromGuests'] ) ? 'checked="checked"' : '';
	$hideLinksFromGuests_no = ( !$fm->exbb['hideLinksFromGuests'] ) ? 'checked="checked"' : '';

	$langs_select = $style_select = '';

	$languagedir = 'language';
	$d = dir($languagedir);
	while (false !== ( $file = $d->read() )) {
		if (is_dir($languagedir . '/' . $file) && $file != '.' && $file != '..') {
			$selected = ( strtolower($file) == strtolower($fm->exbb['default_lang']) ) ? ' selected="selected"' : '';
			$langs_select .= '<option value="' . trim($file) . '"' . $selected . '>' . $file . '</option>';
		}
	}
	$d->close();

	$styledir = 'templates';
	$d = dir($styledir);
	while (false !== ( $file = $d->read() )) {
		if (is_dir($styledir . '/' . $file) && $file != '.' && $file != '..') {
			$selected = ( strtolower($file) == strtolower($fm->exbb['default_style']) ) ? ' selected="selected"' : '';
			$style_select .= '<option value="' . trim($file) . '"' . $selected . '>' . $file . '</option>';
		}
	}
	$d->close();

	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/board_config.tpl' );
	include( './admin/footer.tpl' );
}
elseif ($fm->input['action'] == 'module') {

	$mailer_yes = ( $fm->exbb['mailer'] ) ? 'checked="checked"' : '';
	$mailer_no = ( !$fm->exbb['mailer'] ) ? 'checked="checked"' : '';

	$watches_yes = ( $fm->exbb['watches'] ) ? 'checked="checked"' : '';
	$watches_no = ( !$fm->exbb['watches'] ) ? 'checked="checked"' : '';

	$birstday_yes = ( $fm->exbb['birstday'] ) ? 'checked="checked"' : '';
	$birstday_no = ( !$fm->exbb['birstday'] ) ? 'checked="checked"' : '';

	$threadstop_yes = ( $fm->exbb['threadstop'] ) ? 'checked="checked"' : '';
	$threadstop_no = ( !$fm->exbb['threadstop'] ) ? 'checked="checked"' : '';

	$reputation_yes = ( $fm->exbb['reputation'] ) ? 'checked="checked"' : '';
	$reputation_no = ( !$fm->exbb['reputation'] ) ? 'checked="checked"' : '';

	$karma_yes = ( $fm->exbb['karma'] ) ? 'checked="checked"' : '';
	$karma_no = ( !$fm->exbb['karma'] ) ? 'checked="checked"' : '';

	$punish_yes = ( $fm->exbb['punish'] ) ? 'checked="checked"' : '';
	$punish_no = ( !$fm->exbb['punish'] ) ? 'checked="checked"' : '';

	$userstop_yes = ( $fm->exbb['userstop'] ) ? 'checked="checked"' : '';
	$userstop_no = ( !$fm->exbb['userstop'] ) ? 'checked="checked"' : '';

	$newusergreatings_yes = ( $fm->exbb['newusergreatings'] ) ? 'checked="checked"' : '';
	$newusergreatings_no = ( !$fm->exbb['newusergreatings'] ) ? 'checked="checked"' : '';

	$newpmnewmes_yes = ( $fm->exbb['pmnewmes'] ) ? 'checked="checked"' : '';
	$newpmnewmes_no = ( !$fm->exbb['pmnewmes'] ) ? 'checked="checked"' : '';

	$newshowuseronline_yes = ( $fm->exbb['showuseronline'] ) ? 'checked="checked"' : '';
	$newshowuseronline_no = ( !$fm->exbb['showuseronline'] ) ? 'checked="checked"' : '';

	$statvisit_yes = ( $fm->exbb['statvisit'] ) ? 'checked="checked"' : '';
	$statvisit_no = ( !$fm->exbb['statvisit'] ) ? 'checked="checked"' : '';

	$belong_yes = ( $fm->exbb['belong'] ) ? 'checked="checked"' : '';
	$belong_no = ( !$fm->exbb['belong'] ) ? 'checked="checked"' : '';


	$newimgpreview_yes = ( $fm->exbb['imgpreview'] ) ? 'checked="checked"' : '';
	$newimgpreview_no = ( !$fm->exbb['imgpreview'] ) ? 'checked="checked"' : '';

	$newvisiblemode_yes = ( $fm->exbb['visiblemode'] == 1 ) ? 'checked="checked"' : '';
	$newvisiblemode_no = ( $fm->exbb['visiblemode'] == 0 ) ? 'checked="checked"' : '';

	$preport_yes = ( $fm->exbb['preport'] == 1 ) ? 'checked="checked"' : '';
	$preport_no = ( $fm->exbb['preport'] == 0 ) ? 'checked="checked"' : '';

	$rss_yes = ( $fm->exbb['rss'] == 1 ) ? 'checked="checked"' : '';
	$rss_no = ( $fm->exbb['rss'] == 0 ) ? 'checked="checked"' : '';

	$ads_yes = ( $fm->exbb['ads'] ) ? 'checked="checked"' : '';
	$ads_no = ( !$fm->exbb['ads'] ) ? 'checked="checked"' : '';

	$sponsor_yes = ( $fm->exbb['sponsor'] ) ? 'checked="checked"' : '';
	$sponsor_no = ( !$fm->exbb['sponsor'] ) ? 'checked="checked"' : '';

	$redirect_yes = ( $fm->exbb['redirect'] ) ? 'checked="checked"' : '';
	$redirect_no = ( !$fm->exbb['redirect'] ) ? 'checked="checked"' : '';

	$chat_yes = ( $fm->exbb['chat'] ) ? 'checked="checked"' : '';
	$chat_no = ( !$fm->exbb['chat'] ) ? 'checked="checked"' : '';

	include( './admin/all_header.tpl' );
	include( './admin/nav_bar.tpl' );
	include( './admin/board_module.tpl' );
	include( './admin/footer.tpl' );
}
include( 'page_tail.php' );

function save_info() {
	global $fm;

	$fm->exbb['ch_upfiles'] = '0' . base_convert($fm->exbb['ch_upfiles'], 10, 8);
	$fm->exbb['ch_files'] = '0' . base_convert($fm->exbb['ch_files'], 10, 8);
	$fm->exbb['ch_dirs'] = '0' . base_convert($fm->exbb['ch_dirs'], 10, 8);

	//prints($newvars);exit();

	foreach ($fm->input['new_exbb'] as $arr_key => $arr_keyarray) {
		if ($arr_key == 'b') {
			foreach ($arr_keyarray as $key => $variable) {
				$fm->exbb[$key] = $fm->_Boolean($fm->input['new_exbb']['b'], $key);

			}
		}
		elseif ($arr_key == 'i') {
			foreach ($arr_keyarray as $key => $variable) {
				$fm->exbb[$key] = ( $variable == '' || $variable == '0' ) ? '0' : intval($variable);
			}
		}
		elseif ($arr_key == 's') {
			foreach ($arr_keyarray as $key => $variable) {
				switch ($key) {
					case 'file_type':
						$fm->exbb[$key] = ( $variable == '' || $variable == '0' ) ? '.*' : strval(str_replace(' ', '', $variable));
					break;
					case 'default_lang':
						$fm->exbb[$key] = Check_DefLangSkin('language', 'default_lang', $variable);
					break;
					case 'default_style':
						$fm->exbb[$key] = Check_DefLangSkin('templates', 'default_style', $variable);
					break;
					default:
						$fm->exbb[$key] = ( $variable == '' || $variable == '0' ) ? '' : strval($variable);
					break;
				}
			}
		}
		elseif ($arr_key == 'c') {
			foreach ($arr_keyarray as $key => $variable) {
				$fm->exbb[$key] = ( $variable == '' || $variable == '0' || !preg_match("#^0\d{3}$#", $variable) ) ? $fm->exbb[$key] : strval($variable);
			}


		}
		else {
			die( "ERROR = $arr_key" );
		}

	}

	clearstatcache();
	reset($fm->exbb);
	$board_config = "<?php
if (!defined('IN_EXBB')) die('Hack attempt!');";
	foreach ($fm->exbb as $key => $var) {
		switch (gettype($var)) {
			case 'string':
				switch ($key) {
					case 'ch_upfiles':
					case 'ch_files':
					case 'ch_dirs':
					break;
					default:
						$var = "'$var'";
				};
			break;
			case 'boolean':
				$var = ( $var === true ) ? 'TRUE' : 'FALSE';
			break;
		}
		$board_config .= "
\$this->exbb['$key'] = $var;";

	}
	$board_config .= "\n?>";

	$title_key = ucfirst($fm->input['action']) . 'Config';
	@chmod(EXBB_DATA_CONFIG, octdec($fm->exbb['ch_files']));
	if (!empty( $board_config ) && is_writable(EXBB_DATA_CONFIG)) {
		copy(EXBB_DATA_CONFIG, EXBB_DATA_CONFIG_BACKUP);
		@chmod(EXBB_DATA_CONFIG_BACKUP, octdec($fm->exbb['ch_files']));
		$fp = fopen(EXBB_DATA_CONFIG, 'a+');
		flock($fp, 2);
		ftruncate($fp, 0);
		fwrite($fp, $board_config);
		fflush($fp);
		flock($fp, 3);
		fclose($fp);
		@chmod(EXBB_DATA_CONFIG, octdec($fm->exbb['ch_files']));
		$fm->_WriteLog($fm->LANG['LogOk'] . $fm->LANG['Log' . $title_key], 1);
		$fm->_Message($fm->LANG[$title_key], $fm->LANG['BoardinfoOk'], "setvariables.php?action=" . $fm->input['action'], 1);
	}
	else {
		$fm->_WriteLog($fm->LANG['LogEr'] . $fm->LANG['Log' . $title_key], 1);
		$fm->_Message($fm->LANG[$title_key], $fm->LANG['BoardinfoFail'], '', 1);
	}
}

?>
