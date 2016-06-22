<?php
echo <<<DATA
			<h1>{$fm->LANG['UserAdmin']}</h1>
			<p class="genmed">{$fm->LANG['EditingName']} <b>{$user['name']}</b> (ID: {$user_id})</p>
			<form action="setmembers.php" method="post">
			<input type="hidden" name="action" value="edit_user">
			<input type="hidden" name="checkaction" value="yes">
			<input type="hidden" name="userid" value="{$user_id}">
			<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
				<tr>
					<th class="thHead" colspan="2">{$fm->LANG['General']}</th>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['RegDate']}</td>
					<td class="row2">{$regdate}</td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['LastVisitDate']}</td>
					<td class="row2">{$lastvisitdate}</td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['UserTitle']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="50" name="membertitle" value="{$user['title']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['UserEmail']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="50" name="emailaddress" value="{$user['mail']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['NewName']}<br /><span class="gensmall">{$fm->LANG['NewNameNotice']}</span></td>
					<td class="row2"><input class="post" type="text" maxlength="60" size="50" name="newname" value="{$user['name']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['NewPassword']}<br /><span class="gensmall">{$fm->LANG['NewPassNotice']}</span></td>
					<td class="row2"><input class="post" type="text" maxlength="32" size="35" name="password" value="" /></td>
				</tr>
				<tr>
					<th class="thHead" colspan="2">{$fm->LANG['Profile']}</th>
				</tr>
				<!-- ÄÅÍÜ ÐÎÆÄÅÍÈß -->
				{$admin_birsday}
				<!-- ÄÅÍÜ ÐÎÆÄÅÍÈß -->
				<tr class="gen">
					<td class="row1">{$fm->LANG['WWW']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="50" name="homepage" value="{$user['www']}" /> &nbsp; $homepage</td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['AOL']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="50" name="aolname" value="{$user['aim']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['ICQ']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="20" name="icqnumber" value="{$user['icq']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['From']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="20" name="location" value="{$user['location']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['Interests']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="50" name="interests" value="{$user['interests']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['Signature']}</td>
					<td class="row2"><textarea style="width: 300px" name="signature" cols="40" rows="5" class="post">{$user['sig']}</textarea></td>
				</tr>
				<tr>
					<th class="thHead" colspan="2">{$fm->LANG['BoardOpt']}</th>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['SkinUsed']}</td>
					<td class="row2"><b>{$user['skin']}</b></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['CanUpload']}</td>
					<td class="row2"><input type="checkbox" name="doupload" value="1"{$checked}>{$fm->LANG['CanUploadMes']}</td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['Replies']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="50" name="numberofposts" value="{$user['posts']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['Avatar']}</td>
					<td class="row2"><input class="post" type="text" maxlength="255" size="50" name="avatar" value="{$user['avatar']}" /></td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['PrivateForums']}<br /><span class="gensmall">{$fm->LANG['PrivateNotice']}</span></td>
					<td class="row2">{$private}</td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['UserStatus']}</td>
					<td class="row2">
						<select name="memberstatus">
							{$selectstatus}
						</select>
					</td>
				</tr>
				<tr class="gen">
					<td class="row1">{$fm->LANG['DeletUser']}</td>
					<td class="row2"><input type="checkbox" name="deleteuser" value="yes">{$fm->LANG['DeletUserMes']}</td>
				</tr>
				<tr>
					<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
				</tr>
			</table>
		</form>
DATA;
?>
