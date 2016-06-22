<?php
echo <<<FORM
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['SendPassTitle']}
			</div>
			<br>
			<form action="profile.php" method="post">
				<input type="hidden" name="action" value="lostpassword">
				<table cellpadding="4" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
					<tr>
						<td  class="maintitle" colspan="2" height="29"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8"/>&nbsp;{$fm->LANG['FillForm']}</td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['EnterYouName']}</b></td>
						<td class="profilright"><input type="text" style="width: 200px" size="35" maxlength="35" name="membername"></td>
					</tr>
FORM;
if ($fm->exbb['anti_bot'] === TRUE) {
echo <<< FORM
					<tr>
						<td class="profilleft"><b>{$fm->LANG['CaptchaCode']}</b><br /><span class="desc">{$fm->LANG['CaptchaBroken']}</span></td>
						<td class="profilleft"><div style="float:left;"><img id="captcha" src="regimage.php" border="0" alt="captcha"></div><div style="vertical-align:middle;" class="button"><input type="button" value="Œ·ÌÓ‚ËÚ¸ Í‡ÚËÌÍÛ" onClick="reload_captcha(); return false;" /></div></td>
						<script language="JavaScript" src="javascript/reload_captcha.js"></script></td>
					</tr>
					<tr>
						<td class="profilleft"><b>{$fm->LANG['Captcha—onfirm']}</b><br /><span class="desc">{$fm->LANG['CaptchaNote']}</span></td>
						<td class="profilright"><input type="text" style="width: 130px" name="captcha" size="13" maxlength="10" onblur="verify_register(this);" />
						<span id="verify_captcha"></span>
						<script language="JavaScript" src="javascript/verify_forms.js"></script>
					</tr>
FORM;
}
echo <<<FORM
					<tr>
						<td class="profilleft"><b>{$fm->LANG['ReSendPass']}</b></td>
						<td class="profilright"><input type="checkbox" name="resend" value="yes"> <span class="desc">{$fm->LANG['ReSendPassDesc']}</span></td>
					</tr>
					<tr>
						<td colspan="2" align="center" height="29" class="darkrow2"><input type="submit" value="{$fm->LANG['Send']}" name="DoSend"></td>
					</tr>
				</table>
			</form>
FORM;
?>
