<?php
echo <<<DATA
			<h1>{$fm->LANG['AdminBannedIp']}</h1>
			<form action="setbannedip.php" method="post">
				<input type="hidden" name="action" value="$action">
				{$hidden}
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" colspan="2">{$TableTitle}</th>
					</tr>
					<tr class="gen">
						<td class="row1" colspan="2"><span class="gensmall">{$fm->LANG['AddNewIpHelp']}</span></td>
					</tr>
					<tr class="gen">
						<td class="row2" width="40%">{$IpTitle}</td>
						<td class="row2" width="60%">{$DescTitle}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><input class="post" type="text" size="25" maxlength="100" name="ipbanned" value="$ipb" /></td>
						<td class="row1"><input class="post" type="text" size="40" maxlength="255" name="ipdesc" value="$ipbd" /></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
DATA;
if ($fm->input['action'] != 'modify') {
echo <<<DATA
			<br>
			<h1>{$fm->LANG['BannedIpList']}</h1>
			<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
				<tr>
					<th class="thCornerL" width="30%">{$fm->LANG['IpAdress']}</th>
					<th class="thTop" width="50%">{$fm->LANG['DescTitle']}</th>
					<th class="thTop" width="10%">{$fm->LANG['Change']}</th>
					<th class="thCornerR" width="10%">{$fm->LANG['Delete']}</th>
				</tr>
				{$ipdata}
				<tr>
					<td class="catBottom" colspan="4" align="center" height="25"></td>
				</tr>
			</table>
			<br>
DATA;
}
?>
