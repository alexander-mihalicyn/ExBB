<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['SearchInTopic']}
			</div>
			<br>
			<form action="printpage.php" method="post" name="search">
				<input type="hidden" name="action" value="1">
				<input type="hidden" name="forum" value="{$forum_id}">
				<input type="hidden" name="topic" value="{$topic_id}">
				<table cellpadding="4" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
					<tr>
						<th colspan="2" class="maintitle" align="left" height="29"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['SearchInTopic']}</th>
					</tr>
					<tr>
						<td width="30%" align="right" class="tdrow1" valign="top">{$fm->LANG['SearchInText']}</td>
						<td class="tdrow1"><input type="text" name="post" style="width:450px" size="40" maxlength="100"></td>
					</tr>
					<tr>
						<td align=right class="tdrow1">{$fm->LANG['SearchParams']}</td>
						<td class="tdrow1">
							<input type="radio" name="stype" tabindex="2" value="AND" title="{$fm->LANG['SearchParamsAND']}" checked>"and"
							<input type="radio" name="stype" value="OR" title="{$fm->LANG['SearchParamsOR']}">"or"
						</td>
					</tr>
					<tr>
						<td align=right class="tdrow1">{$fm->LANG['SearchAuthor']}</td>
						<td class="tdrow1"><input type="text" name="user" style="width:450px" size="40" maxlength="50"></td>
					</tr>
					<tr>
						<td align=right class="tdrow1">{$fm->LANG['SearchOptions']}</td>
						<td class="tdrow1"><input type="checkbox" name="color" tabindex="4" value="yes" checked> {$fm->LANG['SearchColor']}</td>
					</tr>
					<tr>
						<td class="darkrow2" valign="middle" colspan=2 align="center" height="29">
							<input type="submit" value="{$fm->LANG['StartSearch']}">
							<input type="reset" value="{$fm->LANG['Clear']}">
						</td>
					</tr>
				</table>
			</form>
			<br>
DATA;
?>
