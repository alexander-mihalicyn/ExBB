<?php
echo <<<DATA
<br /><br />
<form action="loginout.php" method="post">
	<input type="hidden" name="action" value="loginadmin">
	<table width="400" cellpadding="6" cellspacing="1" border="0" align="center" class="forumline">
		<tr>

			<th class="thRight" align="center" colspan="2"><span style="font-size:22px;">{$fm->LANG['Administrating']}</span></th>
		</tr>
		<tr class="gen">
			<td class="row1" width="100">{$fm->LANG['Login']}</td>
			<td class="row2"><input class="post" type="text" maxlength="60" size="25" name="imembername" value="{$fm->user['name']}" /></td>
		</tr>
		<tr class="gen">
			<td class="row1">{$fm->LANG['Password']}</td>
			<td class="row2"><input class="post" type="password" maxlength="32" size="25" name="ipassword" value="" /></td>
		</tr>
		<tr>
			<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{$fm->LANG['Send']}" class="mainoption" /></td>
		</tr>
	</table>
</form>
DATA;
