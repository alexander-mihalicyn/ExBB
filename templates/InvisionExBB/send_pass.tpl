<br>
<div id="navstrip" align="left">
	<img src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php"><?php echo $fm->exbb['boardname']; ?></a>&nbsp;&raquo;&nbsp;<?php echo $fm->LANG['SendPassTitle']; ?>
</div>
<br>

<form action="profile.php" method="post">
	<input type="hidden" name="action" value="lostpassword">
	<table cellpadding="4" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
		<tr>
			<td  class="maintitle" colspan="2" height="29"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8"/>&nbsp;<?php echo $fm->LANG['FillForm']; ?></td>
		</tr>
		<tr>
			<td class="profilleft"><b><?php echo $fm->LANG['EnterYouName']; ?></b></td>
			<td class="profilright"><input type="text" style="width: 200px" size="35" maxlength="35" name="membername"></td>
		</tr>

		<?php if ($fm->exbb['anti_bot']) : ?>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['CaptchaCode']; ?></b><br /><span class="desc"><?php echo $fm->LANG['CaptchaBroken']; ?></span></td>
				<td class="profilleft"><img id="captcha" src="regimage.php" border="0" alt="captcha"></td>
				<script language="JavaScript" src="javascript/reload_captcha.js"></script></td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['CaptchaСonfirm']; ?></b><br /><span class="desc"><?php echo $fm->LANG['CaptchaNote']; ?></span></td>
				<td class="profilright"><input type="text" style="width: 130px" name="captcha" size="13" maxlength="10" onblur="verify_register(this);" />
					<span id="verify_captcha"></span>
					<script language="JavaScript" src="javascript/verify_forms.js"></script>
			</tr>
		<?php endif; ?>

		<tr>
			<td class="profilleft"><b><?php echo $fm->LANG['ReSendPass']; ?></b></td>
			<td class="profilright"><input type="checkbox" name="resend" value="yes"> <span class="desc"><?php echo $fm->LANG['ReSendPassDesc']; ?></span></td>
		</tr>
		<tr>
			<td colspan="2" align="center" height="29" class="darkrow2"><input type="submit" value="<?php echo $fm->LANG['Send']; ?>" name="DoSend"></td>
		</tr>
	</table>
</form>