<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$PageTitle}
			</div>
			<br>
			<table class="tableborder" width="100%" border="0" cellspacing="1" cellpadding="4">
				<tr>
					<th class="maintitle" align="left">
						<img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$PageTitle}
					</th>
				</tr>
				<tr>
					<th align="center" class="titlemedium">{$fm->LANG['SelectHelp']}</th>
				</tr>
				<tr>
					<td class="row4">{$topics}</td>
				</tr>
				<tr class="darkrow3">
					<td class="postdetails">&nbsp;</td>
				</tr>
			</table>
			<br>
			{$content}
DATA;
?>
