<?php
echo <<<DATA
			<br />
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" /> <a href="index.php">{$fm->exbb['boardname']}</a> &raquo; {$fm->LANG['Registration']}
			</div>
			<br />
			<form action="register.php?{$sesid}" method="post" name="creator">
				<input type="hidden" name="action" value="addmember">
				<table cellpadding="6" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
					<tr>
						<td class="maintitle" colspan="2" align="center" height="29"><span class="medium"><b>{$fm->LANG['RegInfo']}</b></span></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['UserName']}</b><span class="desc">{$intern}</span></td>
						<td class="profilright">
						<input type="text" style="width: 200px" size="35" maxlength="20" name="inmembername" onblur="verify_register(this);" />
						<span id="verify_inmembername"></span></td>
					</tr>
DATA;
if ($requirepass === TRUE) {
echo <<<DATA
					<tr>
						<td class="titlemedium" colspan="2" align=center>{$fm->LANG['RegEmailOn']}</td>
					</tr>
DATA;
} else {
echo <<<DATA
					<tr>
						<td class="profilleft">
							<b>{$fm->LANG['Password']}</b>
							<br />
							<span class="desc">{$fm->LANG['PassEnter']}</span>
						</td>
						<td class="profilright">
							<input type="text" size="20" name="password" maxlength="16" onblur="verify_register(this);">
							<span id="verify_password"></span>
						</td>
					</tr>
DATA;
}
if ($fm->exbb['emailfunctions'] === TRUE) {
echo <<<DATA
					<tr>
						<td class="profilleft"><b>{$fm->LANG['YouEmail']}</b><br /><span class="desc">{$fm->LANG['YouEmailDesc']}</span></td>
						<td class="profilright">
							<input type="text" name="emailaddress" style="width: 200px" size="20" maxlength="100" onblur="verify_register(this);" />
							<span id="verify_emailaddress"></span>
						</td>
						</td>
					</tr>
DATA;
}
if ($fm->exbb['anti_bot'] === TRUE) {
echo <<<DATA
					<tr>
						<td class="profilleft"><b>{$fm->LANG['CaptchaCode']}</b><br /><span class="desc">{$fm->LANG['CaptchaBroken']}</span></td>
						<td class="profilright"><img src="regimage.php" id="captcha" border="0" alt="captcha"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['CaptchaСonfirm']}</b><br /><span class="desc">{$fm->LANG['CaptchaNote']}</span></td>
						<td class="profilright"><input type="text" style="width: 130px" name="captcha" size="13" maxlength="10" onblur="verify_register(this);" />
						<span id="verify_captcha"></span>
						<script language="JavaScript" src="javascript/reload_captcha.js"></script></td>
					</tr>
DATA;
}
if ($fm->exbb['reg_simple'] === FALSE) {
echo <<<DATA
					<tr>
						<td class="titlemedium" colspan="2" align="center">{$fm->LANG['AboutSelf']} ({$fm->LANG['NotNeededInfo']})</td>
					</tr>
{$select_birstday}
					<tr>
						<td class="profilleft"><b>{$fm->LANG['ICQ']}</b><br /><span class="desc">{$fm->LANG['ICQDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 130px" name="icqnumber" size="13" maxlength="9"></td>					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['AOL']}</b><br /><span class="desc">{$fm->LANG['AOLDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 150px" name="aolname" size="20" maxlength="32"></td>					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['WWW']}</b><br /><span class="desc">{$fm->LANG['WWWDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 200px" name="homepage" size="20" maxlength="255" value="http://"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['From']}</b><br /><span class="desc">{$fm->LANG['FromDesc']}</span></td>
						<td class="profilright"><input type="text" style="width: 200px" name="location" size="25" maxlength="100"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['Interests']}</b><br /><span class="desc">{$fm->LANG['InterestsDesc']}</td>
						<td class="profilright"><input type="text" style="width: 200px" name="interests" size="25" maxlength="100"></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['Signature']}</b><br /><span class="desc">{$fm->LANG['SignatureDesc']}</span></td>
						<td class="profilright"><textarea style="width: 300px" name="signature" cols="40" rows="5"></textarea></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['ShowYouSig']}</b></td>
						<td class="profilright"><input name="sig_on" type="radio" value="yes"> {$fm->LANG['Yes']} &nbsp; <input name="sig_on" type="radio" value="no" checked> {$fm->LANG['No']}</td>
					</tr>
					<tr>
						<td class="titlemedium" colspan="2" align="center">{$fm->LANG['Options']}</td>
					</tr>
DATA;
	if ($fm->exbb['emailfunctions'] === TRUE) {
echo <<<DATA
					<tr>
						<td class="profilleft"><b>{$fm->LANG['ShowEmail']}</b><br /><span class="desc">{$fm->LANG['ShowEmailDesc']}</span></td>
						<td class="profilright"><input name="showemail" type="radio" value="yes"> {$fm->LANG['Yes']} &nbsp; <input name="showemail" type="radio" value="no" checked> {$fm->LANG['No']}</td>
					</tr>
DATA;
	}
	if ($fm->exbb['emailfunctions'] === TRUE && $fm->exbb['pmnewmes'] === TRUE){
echo <<<DATA
					<tr><!-- /* Уведомления по E-mail о новых ЛС */ -->
						<td class="profilleft" valign="top"><b>{$fm->LANG['NewPMNotify']}</b><br /><span class="desc">{$fm->LANG['NewPMNotifyDesc']}</span></td>
						<td class="profilright"><input class="tab" type="radio" name="pm_newmes" value="yes" /> {$fm->LANG['Yes']} &nbsp; <input class="tab" type="radio" name="pm_newmes" value="no" checked /> {$fm->LANG['No']}</td>
					</tr><!-- /* Уведомления по E-mail о новых ЛС */ -->
DATA;
	}
	if ($fm->exbb['visiblemode'] === TRUE){
echo <<<DATA
					<tr><!-- /* Скрытый режим пребывания на форуме */ -->
						<td class="profilleft" valign="top"><b>{$fm->LANG['VisibleMode']}</b><br /><span class="desc">{$fm->LANG['VisibleModeDesc']}</span></td>
						<td class="profilright"><input class="tab" type="radio" name="visiblemode" value="yes" /> {$fm->LANG['Yes']} &nbsp; <input class="tab" type="radio" name="visiblemode" value="no" checked /> {$fm->LANG['No']}</td>
					</tr><!-- /* Скрытый режим пребывания на форуме */ -->
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
echo <<<AVATAR
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
							<img src="./im/avatars/$currentface" name="useravatars" border="0" hspace="15">
						</td>
					</tr>
AVATAR;
	}
}
echo <<<DATA
					<tr>
						<td class="activeuserstrip" align="center" colspan="2">&nbsp;<input type="submit" value="{$fm->LANG['Send']}" name="submit" /></td>
					</tr>
				</table>
			</form>
<script language="JavaScript" src="javascript/verify_forms.js"></script>
DATA;
?>
