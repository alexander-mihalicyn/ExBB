<?php
echo <<<DATA
			<br />
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['Login']}
			</div>
			<div align="left">
				{$fm->LANG['RegMessage']}
				<br />
				<br />
				{$fm->LANG['FogotMessage']}
			</div>
			<br />
			<form action="loginout.php" method="post">
				<input type="hidden" name="action" value="login">
				<table width="100%" cellpadding="0" cellspacing="1" border="0" align="center" class="tableborder">
					<tr>
						<td class="maintitle" valign="middle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;<b>{$fm->LANG['EnterInfo']}</b></td>
					</tr>
					<tr>
						<td valign="middle" class="pformleftw">{$fm->LANG['EnterName']}</td>
						<td valign="middle" class="pformright"><input type="text" name="imembername" size="20" maxlength="64" value="" class="forminput"></td>
					</tr>
					<tr>
						<td valign="middle" class="pformleftw">{$fm->LANG['EnterPass']}</td>
						<td valign="middle" class="pformright"><input type="password" name="ipassword" value="" size="20" maxlength="64"></td>
					</tr>
					<tr>
						<td valign="middle" align="center" height="29" colspan="2" class="darkrow2"><input type="submit" name="submit" value="{$fm->LANG['Login']}"></td>
					</tr>
				</table>
			</form>
DATA;
?>
