<?php
if ($punish_data == '') {
$punish_data =<<<DATA
				<tr>
					<td class="row2" align="center" colspan="4">{$fm->LANG['PunEmpty']}</td>
				</tr>
DATA;
}

echo <<<DATA
			<br>
			<table width="100%" cellpadding="3" cellspacing="1" class="tableborder" align="center">
				<tr>
					<td class="maintitle" colspan="4"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['WinTitle']}</td>
				</tr>
				<tr>
					<td align="center" valign="middle" colspan="4" class="row2" style="padding: 20px;">{$information}</td>
				</tr>
				<tr>
					<td class="maintitle" colspan="4"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['UserPunnedTopics']} <b>{$user['name']}</b> :</td>
				</tr>
				<tr align="center" valign="middle" class="titlemedium">
					<th width="30%">{$fm->LANG['Thread']}</td>
					<th width="25%">{$fm->LANG['Forum']}</td>
					<th width="30%">{$fm->LANG['Who']}</td>
					<th width="15%">{$fm->LANG['Delete']}</td>
				</tr>
{$punish_data}
				<tr>
					<td class="darkrow3" colspan="4">&nbsp;</td>
				</tr>
			</table>
DATA;


?>
