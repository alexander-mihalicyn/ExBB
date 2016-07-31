<br />
<div id="navstrip" align="left">
	<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/> <a
		href="index.php"><?php echo $fm->exbb['boardname']; ?></a> &raquo; <?php echo $PageTitle; ?>
</div>
<br/>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input name="action" type="hidden" value="activate">
	<table cellpadding="6" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
		<tr>
			<td class="maintitle" height="29" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"
															   alt="&gt;" width="8" height="8"/>
				<b><?php echo $fm->LANG['ActivationForm']; ?></b></td>
		</tr>

		<?php if ($PassActivated) : ?>

		<tr>
			<td class="profilleft" style="padding-left:100px;"><b><?php echo $fm->LANG['YouIdOnBoard']; ?></b></td>
			<td class="profilright" style="width:50%;"><input type="text" style="width: 200px" size="35" maxlength="10"
															  name="user"></td>
		</tr>

		<?php endif; ?>

		<tr>
			<td class="profilleft" style="padding-left:100px;"><b><?php echo $ActIdTitle; ?></b></td>
			<td class="profilright" style="width:50%;"><input type="text" style="width: 200px" size="35" maxlength="10"
															  name="{$IdFiledName}"></td>
		</tr>
		<tr>
			<td class="profilleft" style="padding-left:100px;"><b><?php echo $fm->LANG['RegKey']; ?></b></td>
			<td class="profilright" style="width:50%;"><input type="text" style="width: 200px" size="35" maxlength="32"
															  name="code"></td>
		</tr>
		<tr>
			<td align="center" height="29" class="darkrow2" colspan="2"><input type="submit"
																			   value="<?php echo $fm->LANG['Send']; ?>"></td>
		</tr>
	</table>
</form>