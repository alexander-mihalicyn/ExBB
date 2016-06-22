<?php
echo <<<DATA
<table width="100%" cellpadding="0" cellspacing="4" border="0" align="center">
	<tr>
		<td align="center" width="200" valign="top" nowrap="nowrap">
			<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center">
				<tr>
					<td align="center">
						<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline">
							<tr>
								<th height="25" class="thHead"><b>{$fm->LANG['AdminMenu']}</b></th>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="admincenter.php" class="genmed">{$fm->LANG['AdminIndex']}</a></span></td>
							</tr>
							<tr>
								<td height="28" class="catSides"><span class="cattitle">{$fm->LANG['General']}</span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setvariables.php?action=main" class="genmed">{$fm->LANG['Configuration']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setvariables.php?action=secure" class="genmed">{$fm->LANG['Secure']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setvariables.php?action=posts" class="genmed">{$fm->LANG['PostsSetup']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setmembers.php?action=censor"  class="genmed">{$fm->LANG['WordCensor']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setsmiles.php"  class="genmed">{$fm->LANG['Smilies']}</a></span></td>
							</tr>
							<tr>
								<td height="28" class="catSides"><span class="cattitle">{$fm->LANG['ForumAdmin']}</span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setforums.php" class="genmed">{$fm->LANG['Manage']}</a></span></td>
							</tr>
							<tr>
								<td height="28" class="catSides"><span class="cattitle">{$fm->LANG['Modules']}</span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setvariables.php?action=module"  class="genmed">{$fm->LANG['Manage']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setbannedip.php" class="genmed">{$fm->LANG['BannedIp']}</a></span></td>
							</tr>
							<tr>
								<td height="28" class="catSides"><span class="cattitle">{$fm->LANG['Users']}</span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setmodule.php?module=memcontrol"  class="genmed">{$fm->LANG['UsersList']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setmembers.php" class="genmed">{$fm->LANG['FoundUser']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setmembers.php?action=updatecount" class="genmed">{$fm->LANG['UsersRecount']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setranks.php" class="genmed">{$fm->LANG['Ranks']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setmembers.php?action=massmail" class="genmed">{$fm->LANG['MassEmail']}</a></span></td>
							</tr>
							<tr>
								<td class="row1"><span class="genmed"><a href="setmembers.php?action=log" class="genmed">{$fm->LANG['VisitsLog']}</a></span></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td valign="top">
DATA;
?>
