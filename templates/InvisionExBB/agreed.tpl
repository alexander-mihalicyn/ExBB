<br />
<div id="navstrip" align="left">
	<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/> <a
		href="index.php"><?php echo $fm->exbb['boardname']; ?></a> &raquo; <?php echo $fm->LANG['Registration']; ?>
</div>
<br/>
<form action="register.php?<?php echo $sesid; ?>" method="post" name="creator">
	<input type="hidden" name="action" value="addmember">
	<table cellpadding="6" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
		<tr>
			<td class="maintitle" colspan="2" align="center" height="29"><span
					class="medium"><b><?php echo $fm->LANG['RegInfo']; ?></b></span></td>
		</tr>
		<tr>
			<td class="profilleft"><b><?php echo $fm->LANG['UserName']; ?></b><span class="desc"><?php echo $intern; ?></span></td>
			<td class="profilright">
				<input type="text" style="width: 200px" size="35" maxlength="20" name="inmembername"
					   onblur="verify_register(this);"/>
				<span id="verify_inmembername"></span></td>
		</tr>

		<?php if ($requirepass) : ?>
			<tr>
				<td class="titlemedium" colspan="2" align=center><?php echo $fm->LANG['RegEmailOn']; ?></td>
			</tr>
		<?php else : ?>
			<tr>
				<td class="profilleft">
					<b><?php echo $fm->LANG['Password']; ?></b>
					<br/>
					<span class="desc"><?php echo $fm->LANG['PassEnter']; ?></span>
				</td>
				<td class="profilright">
					<input type="text" size="20" name="password" maxlength="16" onblur="verify_register(this);">
					<span id="verify_password"></span>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ($fm->exbb['emailfunctions']) : ?>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['YouEmail']; ?></b><br/><span
						class="desc"><?php echo $fm->LANG['YouEmailDesc']; ?></span></td>
				<td class="profilright">
					<input type="text" name="emailaddress" style="width: 200px" size="20" maxlength="100"
						   onblur="verify_register(this);"/>
					<span id="verify_emailaddress"></span>
				</td>
				</td>
			</tr>
		<?php endif; ?>

		<?php if ($fm->exbb['anti_bot']) : ?>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['CaptchaCode']; ?></b><br/><span
						class="desc"><?php echo $fm->LANG['CaptchaBroken']; ?></span></td>
				<td class="profilright"><img src="regimage.php" id="captcha" border="0" alt="captcha"></td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['CaptchaÑonfirm']; ?></b><br/><span
						class="desc"><?php echo $fm->LANG['CaptchaNote']; ?></span></td>
				<td class="profilright"><input type="text" style="width: 130px" name="captcha" size="13" maxlength="10"
											   onblur="verify_register(this);"/>
					<span id="verify_captcha"></span>
					<script language="JavaScript" src="javascript/reload_captcha.js"></script>
				</td>
			</tr>
		<?php endif; ?>

		<?php if (!$fm->exbb['reg_simple']) : ?>
			<tr>
				<td class="titlemedium" colspan="2" align="center"><?php echo $fm->LANG['AboutSelf']; ?> (<?php echo $fm->LANG['NotNeededInfo']; ?>)
				</td>
			</tr>

			<?php echo $select_birstday; ?>

			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['ICQ']; ?></b><br/><span class="desc"><?php echo $fm->LANG['ICQDesc']; ?></span></td>
				<td class="profilright"><input type="text" style="width: 130px" name="icqnumber" size="13" maxlength="9">
				</td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['AOL']; ?></b><br/><span class="desc"><?php echo $fm->LANG['AOLDesc']; ?></span></td>
				<td class="profilright"><input type="text" style="width: 150px" name="aolname" size="20" maxlength="32">
				</td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['WWW']; ?></b><br/><span class="desc"><?php echo $fm->LANG['WWWDesc']; ?></span></td>
				<td class="profilright"><input type="text" style="width: 200px" name="homepage" size="20" maxlength="255"
											   value="http://"></td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['From']; ?></b><br/><span class="desc"><?php echo $fm->LANG['FromDesc']; ?></span></td>
				<td class="profilright"><input type="text" style="width: 200px" name="location" size="25" maxlength="100">
				</td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['Interests']; ?></b><br/><span class="desc"><?php echo $fm->LANG['InterestsDesc']; ?>
				</td>
				<td class="profilright"><input type="text" style="width: 200px" name="interests" size="25" maxlength="100">
				</td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['Signature']; ?></b><br/><span
						class="desc"><?php echo $fm->LANG['SignatureDesc']; ?></span></td>
				<td class="profilright"><textarea style="width: 300px" name="signature" cols="40" rows="5"></textarea></td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['ShowYouSig']; ?></b></td>
				<td class="profilright"><input name="sig_on" type="radio" value="yes"> <?php echo $fm->LANG['Yes']; ?> &nbsp; <input
						name="sig_on" type="radio" value="no" checked> <?php echo $fm->LANG['No']; ?></td>
			</tr>
			<tr>
				<td class="titlemedium" colspan="2" align="center"><?php echo $fm->LANG['Options']; ?></td>
			</tr>
			
			<?php if ($fm->exbb['emailfunctions']) : ?>
				<tr>
					<td class="profilleft"><b><?php echo $fm->LANG['ShowEmail']; ?></b><br/><span
							class="desc"><?php echo $fm->LANG['ShowEmailDesc']; ?></span></td>
					<td class="profilright"><input name="showemail" type="radio" value="yes"> <?php echo $fm->LANG['Yes']; ?> &nbsp; <input
							name="showemail" type="radio" value="no" checked> <?php echo $fm->LANG['No']; ?></td>
				</tr>
			<?php endif; ?>

			<?php if ($fm->exbb['emailfunctions'] && $fm->exbb['pmnewmes']) : ?>
				<tr>
					<td class="profilleft" valign="top"><b><?php echo $fm->LANG['NewPMNotify']; ?></b><br/><span
							class="desc"><?php echo $fm->LANG['NewPMNotifyDesc']; ?></span></td>
					<td class="profilright"><input class="tab" type="radio" name="pm_newmes" value="yes"/> <?php echo $fm->LANG['Yes']; ?>
						&nbsp; <input class="tab" type="radio" name="pm_newmes" value="no" checked/> <?php echo $fm->LANG['No']; ?></td>
				</tr>
			<?php endif; ?>
			
			<?php if ($fm->exbb['visiblemode']) : ?>
				<tr>
					<td class="profilleft" valign="top"><b><?php echo $fm->LANG['VisibleMode']; ?></b><br/><span
							class="desc"><?php echo $fm->LANG['VisibleModeDesc']; ?></span></td>
					<td class="profilright"><input class="tab" type="radio" name="visiblemode" value="yes"/> <?php echo $fm->LANG['Yes']; ?>
						&nbsp; <input class="tab" type="radio" name="visiblemode" value="no" checked/> <?php echo $fm->LANG['No']; ?></td>
				</tr>
			<?php endif; ?>

			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['DefaultLanguage']; ?></b></td>
				<td class="profilright">
					<select name="default_lang">
						<?php echo $langs_select; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['DefaultStyle']; ?></b></td>
				<td class="profilright">
					<select name="default_style">
						<?php echo $style_select; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="profilleft"><b><?php echo $fm->LANG['TimeZone']; ?></b><br/><?php echo $fm->LANG['CurrTime']; ?> <?php echo $basetimes; ?><br/><span
						class="desc"><?php echo $fm->LANG['YouZone']; ?></span></td>
				<td class="profilright">
					<select name="timedifference">
						<?php echo $timezones; ?>
					</select>
				</td>
			</tr>
			
			<?php if ($fm->exbb['avatars']) : ?>
			<script language="javascript">
				function showimage() {
					document.images.useravatars.src = "./im/avatars/" + document.creator.useravatar.options[document.creator.useravatar.selectedIndex].value;
				; ?>
			</script>
			<tr>
				<td valign="top" class="profilleft"><b><?php echo $fm->LANG['Avatar']; ?></b><br/><span
						class="desc"><?php echo $fm->LANG['YourAvatar']; ?></span></td>
				<td class="profilright">
					<select name="useravatar" size="6" onChange="showimage()">
						<?php echo $avatars_select; ?>
					</select>
					<img src="./im/avatars/<?php echo $currentface; ?>" name="useravatars" border="0" hspace="15">
				</td>
			</tr>
			<?php endif; ?>
			
		<?php endif; ?>

		<tr>
			<td class="activeuserstrip" align="center" colspan="2">&nbsp;<input type="submit"
																				value="<?php echo $fm->LANG['Send']; ?>"
																				name="submit"/></td>
		</tr>
	</table>
</form>
<script language="JavaScript" src="javascript/verify_forms.js"></script>