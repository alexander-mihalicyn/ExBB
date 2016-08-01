<?php
echo <<<DATA
			<h1>{$fm->LANG['MainConfig']}</h1>
			<form action="setvariables.php" method="post">
				<input type="hidden" name="action" value="main">
				<input type="hidden" name="save" value="1">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['MainConfig']}</th>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['BoardURL']}</b></td>
						<td class="row2"><input class="post" type="text" maxlength="255" size="40" name="new_exbb[s][boardurl]" value="{$fm->exbb['boardurl']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['BoardName']}</b></td>
						<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="new_exbb[s][boardname]" value="{$fm->exbb['boardname']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['BoardDesc']}</b></td>
						<td class="row2"><input class="post" type="text" size="40" maxlength="255" name="new_exbb[s][boarddesc]" value="{$fm->exbb['boarddesc']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['Description']}</b><br /><span class="gensmall">{$fm->LANG['DescriptionDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="new_exbb[s][description]" value="{$fm->exbb['description']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['Keywords']}</b><br /><span class="gensmall">{$fm->LANG['KeywordsDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="25" maxlength="100" name="new_exbb[s][keywords]" value="{$fm->exbb['keywords']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AdminSesTime']}</b></td>
						<td class="row2"><input class="post" type="text" size="8" maxlength="4" name="new_exbb[i][ad_sestime]" value="{$fm->exbb['ad_sestime']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AllowNews']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][announcements]" value="yes" {$news_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][announcements]" value="no" {$news_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['UPFilesCHMOD']}</b></td>
						<td class="row2"><input class="post" type="text" size="8" maxlength="4" name="new_exbb[c][ch_upfiles]" value="{$ch_upfiles}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['FilesCHMOD']}</b></td>
						<td class="row2"><input class="post" type="text" size="8" maxlength="4" name="new_exbb[c][ch_files]" value="{$ch_files}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['DirsCHMOD']}</b></td>
						<td class="row2"><input class="post" type="text" size="8" maxlength="4" name="new_exbb[c][ch_dirs]" value="{$ch_dirs}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['RuNicks']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][ru_nicks]" value="yes" {$ru_nicks_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][ru_nicks]" value="no" {$ru_nicks_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['RegSimple']}</b><br /><span class="gensmall">{$fm->LANG['RegSimpleMes']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][reg_simple]" value="yes" {$reg_smpl_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][reg_simple]" value="no" {$reg_smpl_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['DefaultLanguage']}</b></td>
						<td class="row2">
							<select name="new_exbb[s][default_lang]">
								{$langs_select}
							</select>
						</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['DefaultStyle']}</b></td>
						<td class="row2">
							<select name="new_exbb[s][default_style]">
								{$style_select}
							</select>
						</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['Membergone']}</b></td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="new_exbb[i][membergone]" value="{$fm->exbb['membergone']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['EnableGzip']}</b><br /><span class="gensmall">{$fm->LANG['EnableGzipMes']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][gzip_compress]" value="yes" {$gzip_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][gzip_compress]" value="no" {$gzip_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['ForumLog']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][log]" value="yes" {$log_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][log]" value="no" {$log_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['BoardDisable']}</b><br /><span class="gensmall">{$fm->LANG['BoardDisableMes']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][board_closed]" value="yes" {$board_disable_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][board_closed]" value="no" {$board_disable_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['BoardDisableText']}</b></td>
						<td class="row2"><textarea class="post" type="text" cols="60" rows="5" wrap="soft" name="new_exbb[s][closed_mes]">{$fm->exbb['closed_mes']}</textarea></td>
					</tr>
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['Privmsg']}</th>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['Privmsg']}</b><br /><span class="gensmall">{$fm->LANG['PrivmsgMes']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][pm]" value="yes" {$pm_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][pm]" value="no" {$pm_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['AbilitiesSettings']}</th>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['HideLinksFromGuests']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][hideLinksFromGuests]" value="yes" {$hideLinksFromGuests_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][hideLinksFromGuests]" value="no" {$hideLinksFromGuests_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['TextMenu']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][text_menu]" value="yes" {$txtmenu_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][text_menu]" value="no" {$txtmenu_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AllowCodes']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][exbbcodes]" value="yes" {$exbbcodes_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][exbbcodes]" value="no" {$exbbcodes_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AllowSmilies']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][emoticons]" value="yes" {$emoticons_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][emoticons]" value="no" {$emoticons_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['ShowRatings']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][ratings]" value="yes" {$ratings_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][ratings]" value="no" {$ratings_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['Censoring']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][wordcensor]" value="yes" {$censoring_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][wordcensor]" value="no" {$censoring_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['FilesUpload']}</b><br /><span class="gensmall">{$fm->LANG['FilesUploadMes']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][file_upload]" value="yes" {$file_upload_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][file_upload]" value="no" {$file_upload_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['MembUpload']}</b><br /><span class="gensmall">{$fm->LANG['MembUploadMes']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][autoup]" value="yes" {$autoup_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][autoup]" value="no" {$autoup_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AllowSig']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][sig]" value="yes" {$sig_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][sig]" value="no" {$sig_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['MaxSigLength']}</b><br /><span class="gensmall">{$fm->LANG['MaxSigLengthMes']}</span></td>
						<td class="row2"><input class="post" type="text" maxlength="4" size="5" name="new_exbb[i][max_sig_chars]" value="{$fm->exbb['max_sig_chars']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['MaxSigLines']}</b></td>
						<td class="row2"><input class="post" type="text" maxlength="2" size="5" name="new_exbb[i][max_sig_lin]" value="{$fm->exbb['max_sig_lin']}" /></td>
					</tr>
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['AvatarsSettings']}</th>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AllowLocal']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][avatars]" value="yes" {$avatars_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][avatars]" value="no" {$avatars_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AvatarUpload']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][avatar_upload]" value="yes" {$avatars_up_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][avatar_upload]" value="no" {$avatars_up_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AvatarSize']}</b></td>
						<td class="row2"><input class="post" type="text" size="4" maxlength="10" name="new_exbb[i][avatar_size]" value="{$fm->exbb['avatar_size']}" /> Bytes</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AvatarPix']}</b>&nbsp;{$fm->LANG['AvatarPixDesc']}</td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="new_exbb[i][avatar_max_height]" value="{$fm->exbb['avatar_max_height']}"> x <input class="post" type="text" size="3" maxlength="4" name="new_exbb[i][avatar_max_width]" value="{$fm->exbb['avatar_max_width']}" /></td>
					</tr>
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['EmailSettings']}</th>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AdminEmail']}</b></td>
						<td class="row2"><input class="post" type="text" maxlength="100" size="55" name="new_exbb[s][adminemail]" value="{$fm->exbb['adminemail']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['BoardEmail']}</b><br /><span class="gensmall">{$fm->LANG['BoardEmailMes']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][emailfunctions]" value="yes" {$emails_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][emailfunctions]" value="no" {$emails_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr>
						<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
