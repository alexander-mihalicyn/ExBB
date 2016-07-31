<?php
echo <<<DATA
			<h1>{$fm->LANG['AdminCatNameEdit']}</h1>
			<form action="setforums.php" method="post">
				<input type="hidden" name="action" value="editcatname">
				<input type="hidden" name="catid" value="{$catid}">
				<input type="hidden" name="doedit" value="yes">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['EditCat']}</th>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['NewCatName']}</td>
						<td class="row2"><input class="post" type="text" size="40" name="catname" value="{$categoryname}"></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;

