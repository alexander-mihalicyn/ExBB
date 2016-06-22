<?php
echo <<<DATA
			<h1>{$fm->LANG['UserAdmin']}</h1>
			<p class="genmed">{$fm->LANG['UserAdminInfo']}</p>
			<form method="post" name="post" action="setmembers.php">
				<table width="80%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
					<tr>
						<th class="thHead">{$fm->LANG['SelectUser']}</th>
					</tr>
					<tr>
						<td class="row1" align="center">
DATA;
if ($_FirstStep === TRUE) {
echo <<<DATA
							{$fm->LANG['FoundByName']} <input type="text" name="username" maxlength="50" size="20" />
							{$fm->LANG['FoundByEmail']} <input type="text" name="usermail" maxlength="50" size="20" />
							<input type="hidden" name="action" value="find" />
							<input type="submit" name="usersubmit" value="{$fm->LANG['FindUser']}"/>
DATA;
} else {
echo <<<DATA
							<select name="userid">
								{$select_data}
							</select>
							<input type="hidden" name="action" value="edit_user" />
							<input type="submit" name="usersubmit" value="{$fm->LANG['SelectUser']}"/>
DATA;
}
echo <<<DATA
						</td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
?>
