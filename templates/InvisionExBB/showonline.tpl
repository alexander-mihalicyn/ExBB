<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['WhoOnline']}
			</div>
			<br>
			<table width="100%" cellpadding="0" cellspacing="1" class="tableborder">
				<tr>
					<th class="maintitle" align="left" colspan="3">
						<img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['WhoOnline']}
					</th>
				</tr>
				<tr align="center" class="normal">
					<td width="30%" class="postlinksbar" height="22" width="30%"><b>{$fm->LANG['UsersNames']}</b></td>
					<td width="20%" class="postlinksbar"><b>{$fm->LANG['LastActionTime']}</b></td>
					<td class="postlinksbar"><b>{$fm->LANG['LastAction']}</b></td>
				</tr>
{$output}
				<tr>
					<td class="activeuserstrip" align="center" colspan="3">&nbsp;</td>
				</tr>
			</table>
DATA;

