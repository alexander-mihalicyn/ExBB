<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['MailByBoard']}
			</div>
			<br>
			<form method="post" action="tools.php">
				<input type="hidden" name="action" value="mail">
				<input type="hidden" name="dosend" value="yes">
				<input type="hidden" name="member" value="{$user_id}">
				<table cellspacing="1" cellpadding="4" border="0" align="center" class="tableborder" width="70%">
					<tr>
						<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['SendMailTo']} {$user['name']}</td>
					</tr>
					<tr>
						<td class="pformleft" align="right"><b>{$fm->LANG['Topic']}</b></td>
						<td class="pformright"><input type="text" name="subject" size="45" maxlength="100" tabindex="2" value="" /></td>
					</tr>
					<tr>
						<td class="pformleft" align="right" valign="top"><b>{$fm->LANG['EmailMessage']}</b></td>
						<td class="pformright"><textarea name="message" rows="15" cols="35" wrap="virtual" style="width:450px" tabindex="3"></textarea></td>
					</tr>
					<tr>
						<td class="darkrow2" align="center" colspan="2"><input type="submit" value="{$fm->LANG['Send']}" name="submit" /></td>
					</tr>
				</table>
			</form>
DATA;
?>
