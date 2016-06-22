<?php
echo <<<DATA
			<h1>{$do}</h1>
			{$safe_mode}
			<form action="setforums.php" method="post">
				{$hidden}
				<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['ForumAddNew']}</th>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['NewForumInCat']}</td>
						<td class="row2">{$cathtml}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ForumName']}</td>
						<td class="row2"><input class="post" type="text" size="40" name="forumname" value="{$forumname}" class="post" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ForumDesc']}</td>
						<td class="row2"><input class="post" type="text" size="40" name="forumdescription" value="{$forumdescription}"></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ForumModers']}<br /><span class="gensmall">{$fm->LANG['ForumModersMes']}</span></td>
						<td class="row2"><input class="post" type="text" maxlength="255" size="40" name="forummoderator" value="$forummoderator" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ExbbCode']}</td>
						<td class="row2">
							<select name="codestate">
								<option value="yes" {$codes_on}>{$fm->LANG['On']}</option>
								<option value="no" {$codes_off}>{$fm->LANG['Off']}</option>
							</select>
						</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['PollEnable']}</td>
						<td class="row2">
							<input type="radio" name="polls" value="yes" {$polls_on} /> {$fm->LANG['Yes']}&nbsp;&nbsp;
							<input type="radio" name="polls" value="no" {$polls_off} /> {$fm->LANG['No']}
						</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['MakePrivate']}</td>
						<td class="row2">
							<select class="gen" name="privateforum">
								<option value="yes" {$private_on}>{$fm->LANG['Yes']}</option>
								<option value="no" {$private_off}>{$fm->LANG['No']}</option>
							</select>
						</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['Access2View']}</td>
						<td class="row2">
							<select class="gen" name="access2view">
								<option value="all" {$access2view_all}>{$fm->LANG['AllGuests']}</option>
								<option value="reged" {$access2view_reged}>{$fm->LANG['AllReged']}</option>
								<option value="admo" {$access2view_no}>{$fm->LANG['TeamOnly']}</option>
							</select>
						</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['Access2New']}</td>
						<td class="row2">
							<select class="gen" name="access2new">
								<option value="all" {$access2new_all}>{$fm->LANG['AllGuests']}</option>
								<option value="reged" {$access2new_reged}>{$fm->LANG['AllReged']}</option>
								<option value="admo" {$access2new_no}>{$fm->LANG['TeamOnly']}</option>
							</select>
						</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['Access2Reply']}</td>
						<td class="row2">
							<select class="gen" name="access2reply">
								<option value="all" {$access2reply_all}>{$fm->LANG['AllGuests']}</option>
								<option value="reged" {$access2reply_reged}>{$fm->LANG['AllReged']}</option>
								<option value="admo" {$access2reply_no}>{$fm->LANG['TeamOnly']}</option>
							</select>
						</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['UploadStatus']}<br /><span class="gensmall">{$fm->LANG['UploadStatusMes']}</span></td>
						<td class="row2"><input class="post" type="text" maxlength="8" size="40" name="upsize" value="{$upsize}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ForumPic']}<br /><span class="gensmall">{$fm->LANG['ForumPicMes']}</span></td>
						<td class="row2"><input class="post" type="text" maxlength="255" size="40" name="forumgraphic" value="{$forumgraphic}" /></td>
					</tr>
DATA;
if ($fm->exbb['sponsor']) {
echo <<<DATA
					<tr valign="top" class="gen">
						<td class="row1">{$fm->LANG['Sponsor']}<br /><span class="gensmall">{$fm->LANG['SponsorDesc']}</span></td>
						<td class="row2"><textarea name="sponsor" cols="40" rows="7">{$sponsor}</textarea></td>
					</tr>
DATA;
}
echo <<<DATA
					<tr>
						<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{$button}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
?>
