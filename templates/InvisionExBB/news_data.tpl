<?php
$newsbody .= <<<DATA
<a name="{$id}"></a>
			<table class="tableborder" width="100%" border="0" cellspacing="1" cellpadding="4">
				<tr>
					<th class="maintitle" align="left"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;<b>{$title}</b></th>
				</tr>
DATA;
if (defined('IS_ADMIN')) {
$newsbody .= <<<DATA

				<tr>
					<td class="titlemedium">&nbsp;<a href="announcements.php?action=delete&number={$id}">{$fm->LANG['Delete']}</a> - <a href="announcements.php?action=edit&number={$id}">{$fm->LANG['Change']}</a></td>
				</tr>
DATA;
}
$newsbody .= <<<DATA
				<tr>
					<td class="titlemedium1" width="100%" align="left">{$news}<p></td>
				</tr>
				<tr>
					<td class="darkrow3">
						<div align="left" style="float:left;">&nbsp;{$fm->LANG['PostDate']} <b>{$dateposted}</b></div>
        				<div align="right"><a href="#top" onClick="scroll(0,0);return false"><img src="./templates/InvisionExBB/im/gotop.gif" alt="Top" border="0" /></a></div>
					</td>
				</tr>
			</table>
			<br>
DATA;
