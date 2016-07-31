<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/>&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; {$fm->LANG['ProfileEditing']}
			</div>
			<br>
			<form action="profile.php" method=post name="creator"{$enctype}>
				<input type=hidden name="action" value="savemodify">
				<input type="hidden" name="token" value="{$token}">
				<table class="tableborder" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="maintitle" colspan="2">{$fm->LANG['ProfileEditFor']} <u>{$fm->user['name']}</u></td>
					</tr>
					<tr>
						<td class="profilleft" valign="top"><b>{$fm->LANG['Password']}</b><br><span class="desc">{$fm->LANG['PassEnter']}</span></td>
						<td class="profilright"><input type="text" style="width: 200px" name="password" maxlength="16"></td>
					</tr>
DATA;

if ($fm->exbb['emailfunctions'] === TRUE) {
echo <<<DATA
					<tr>
						<td class="profilleft" valign="top"><b>{$fm->LANG['YouEmail']}</b><br><span class="desc">{$fm->LANG['YouEmailDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 200px" name="emailaddress" maxlength="100" value="{$fm->user['mail']}"></td>
					</tr>
DATA;
}
echo <<<DATA
					<tr>
						<td class="pformstrip" align="center" colspan="2">{$fm->LANG['AboutSelf']} ({$fm->LANG['NotNeededInfo']})</td>
					</tr>
					{$select_birstday}
					<tr>
						<td class="profilleft" valign="top"><b>{$fm->LANG['ICQ']}</b><br><span class="desc">{$fm->LANG['ICQDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 130px" name="icqnumber" size=13 maxlength="9" value="{$fm->user['icq']}"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['AOL']}</b><br /><span class="desc">{$fm->LANG['AOLDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 150px" name="aolname" maxlength="32" value="{$fm->user['aim']}"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['WWW']}</b><br /><span class="desc">{$fm->LANG['WWWDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 200px" name="homepage" maxlength="255" value="{$fm->user['www']}"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['From']}</b><br /><span class="desc">{$fm->LANG['FromDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 200px" name="location" maxlength="100" value="{$fm->user['location']}"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['Interests']}</b><br /><span class="desc">{$fm->LANG['InterestsDesc']}</td>
						<td class="profilright"><input type="text" style="width: 200px" name="interests" maxlength="100" value="{$fm->user['interests']}"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['Signature']}</b><br /><span class="desc">{$fm->LANG['SignatureDesc']}</span></td>
						<td class="profilright"><textarea style="width: 300px" name="signature" cols="40" rows="5">{$fm->user['sig']}</textarea></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['ShowYouSig']}</b></td>
						<td class="profilright"><input name="sig_on" type="radio" value="yes"{$sig_onyes}> {$fm->LANG['Yes']} &nbsp; <input name="sig_on" type="radio" value="no"{$sig_onno}> {$fm->LANG['No']}</td>
					</tr>
					<tr>
						<td class="titlemedium" colspan="2" align="center">{$fm->LANG['Options']}</td>
					</tr>
DATA;
if ($fm->exbb['emailfunctions'] === TRUE) {
echo <<<DATA
					<tr>
						<td class="profilleft"><b>{$fm->LANG['ShowEmail']}</b><br /><span class="desc">{$fm->LANG['ShowEmailDesc']}</span></td>
						<td class="profilright"><input name="showemail" type="radio" value="yes"{$showmyes}> {$fm->LANG['Yes']} &nbsp; <input name="showemail" type="radio" value="no"{$showmyno}> {$fm->LANG['No']}</td>
					</tr>
DATA;
}
if ($fm->exbb['emailfunctions'] === TRUE && $fm->exbb['pmnewmes'] === TRUE){
echo <<<DATA
					<tr><!-- /* Уведомления по E-mail о новых ЛС */ -->
						<td class="profilleft" valign="top"><b>{$fm->LANG['NewPMNotify']}</b><br /><span class="desc">{$fm->LANG['NewPMNotifyDesc']}</span></td>
						<td class="profilright"><input type="radio" name="pm_newmes" value="yes"{$pm_newmes_yes} /> {$fm->LANG['Yes']} &nbsp; <input type="radio" name="pm_newmes" value="no"{$pm_newmes_no}/> {$fm->LANG['No']}</td>
					</tr><!-- /* Уведомления по E-mail о новых ЛС */ -->
DATA;
}
if ($fm->exbb['visiblemode'] === TRUE){
echo <<<DATA
					<tr><!-- /* Скрытый режим пребывания на форуме */ -->
						<td class="profilleft" valign="top"><b>{$fm->LANG['VisibleMode']}</b><br /><span class="desc">{$fm->LANG['VisibleModeDesc']}</span></td>
						<td class="profilright"><input type="radio" name="visiblemode" value="yes"{$visiblemode_yes}/> {$fm->LANG['Yes']} &nbsp; <input type="radio" name="visiblemode" value="no"{$visiblemode_no}/> {$fm->LANG['No']}</td>
					</tr><!-- /* Скрытый режим пребывания на форуме */ -->
DATA;
}
if ($fm->exbb['userperpage'] === TRUE) {
echo <<<DATA
					<tr>
						<td class="profilleft" valign="top"><b>{$fm->LANG['Topics2Page']}</b></td>
						<td class="profilright"><input type="text" style="width: 50px" name="topics2page" maxlength="3" value="{$fm->user['topics2page']}"></td>
					</tr>
					<tr>
						<td class="profilleft" valign="top"><b>{$fm->LANG['Posts2Page']}</b></td>
						<td class="profilright"><input type="text" style="width: 50px" name="posts2page" maxlength="3" value="{$fm->user['posts2page']}"></td>
					</tr>
DATA;
}
echo <<<DATA
					<tr>
						<td class="profilleft"><b>{$fm->LANG['DefaultLanguage']}</b></td>
						<td class="profilright">
							<select name="default_lang">
								{$langs_select}
							</select>
						</td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['DefaultStyle']}</b></td>
						<td class="profilright">
							<select name="default_style">
								{$style_select}
							</select>
						</td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['TimeZone']}</b><br />{$fm->LANG['CurrTime']} {$basetimes}<br /><span class="desc">{$fm->LANG['YouZone']}</span></td>
						<td class="profilright">
							<select name="timedifference">
								{$timezones}
							</select>
						</td>
					</tr>
DATA;
if ($fm->exbb['avatars'] === TRUE) {
echo <<<DATA
<script language="javascript">
function showimage() {
	document.images.useravatars.src="./im/avatars/"+document.creator.useravatar.options[document.creator.useravatar.selectedIndex].value;
}
</script>
					<tr>
						<td valign="top" class="profilleft"><b>{$fm->LANG['Avatar']}</b><br /><span class="desc">{$fm->LANG['YourAvatar']}</span></td>
						<td class="profilright">
							<select name="useravatar" size="6" onChange="showimage()">
								{$avatars_select}
							</select>
							<img src="./im/avatars/{$fm->user['avatar']}" name="useravatars" border="0" hspace="15">
							<br><br>
							<input type="checkbox" name="noavatar" value="yes"> {$fm->LANG['NotUsedAvatar']}
						</td>
					</tr>
DATA;
	if ($fm->exbb['avatar_upload'] === TRUE) {
echo <<<DATA
					<tr>
						<td class="profilleft" valign="top"><b>{$fm->LANG['AvatarUploads']}</b><br><span class="desc">{$avatar_info}</span></td>
						<td class="profilright">
						<input type="hidden" name="MAX_FILE_SIZE" value="{$fm->exbb['avatar_size']}">
						<input type="file" size="30" name="FILE_UPLOAD"></td>
					</tr>
DATA;
	}
}
echo <<<DATA
					<tr>
						<td class="pformstrip" align="center" colspan="2"><input type="submit" value="{$fm->LANG['Save']}" name="DoSave"></td>
					</tr>
				</table>
			</form>
DATA;
?>
