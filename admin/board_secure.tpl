<?php
echo <<<DATA
			<h1>{$fm->LANG['SecureConfig']}</h1>
			<form action="setvariables.php" method="post">
				<input type="hidden" name="action" value="secure">
				<input type="hidden" name="save" value="1">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['SecureConfig']}</th>
					</tr>
					<tr class="gen">
						<td class="row1" width="75%"><b>{$fm->LANG['AntiBot']}</b><br /><span class="gensmall">{$fm->LANG['AntiBotMes']}</span></td>
						<td class="row2" nowrap><input type="radio" name="new_exbb[b][anti_bot]" value="yes" {$bot_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][anti_bot]" value="no" {$bot_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['RegistrationOff']}</b></td>
						<td class="row2">
							<input type="radio" name="new_exbb[b][reg_on]" value="yes" {$reg_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][reg_on]" value="no" {$reg_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['UserActivation']}</b><br /><span class="gensmall">{$fm->LANG['UserActivationMes']}</span></td>
						<td class="row2">
							<input type="radio" name="new_exbb[b][passwordverification]" value="yes" {$passverif_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][passwordverification]" value="no" {$passverif_no} /> {$fm->LANG['No']}
						</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['ShowImg']}</b><br /><span class="gensmall">{$fm->LANG['ShowImgMes']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][show_img]" value="yes" {$img_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][show_img]" value="no" {$img_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['ImgSize']}</b><br /><span class="gensmall">{$fm->LANG['ImgSizeMes']}</span></td>
						<td class="row2"><input class="post" type="text" maxlength="4" size="5" name="new_exbb[i][image_max_width]" value="{$fm->exbb['image_max_width']}" /> * <input class="post" type="text" maxlength="4" size="5" name="new_exbb[i][image_max_height]" value="{$fm->exbb['image_max_height']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['FileType']}</b><br /><span class="gensmall">{$fm->LANG['FileTypeMes']}</span></td>
						<td class="row2"><input class="post" type="text" size="30" name="new_exbb[s][file_type]" value="{$fm->exbb['file_type']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['NewRegNotify']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][newusernotify]" value="yes" {$newuser_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][newusernotify]" value="no" {$newuser_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['FloodInterval']}</b><br /><span class="gensmall">{$fm->LANG['FloodIntervalMes']}</span></td>
						<td class="row2"><input class="post" type="text" maxlength="4" size="5" name="new_exbb[i][flood_limit]" value="{$fm->exbb['flood_limit']}" /></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
