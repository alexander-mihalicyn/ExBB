<?php
echo <<<DATA
			<h1>{$fm->LANG['Censor']}</h1>
			<p class="gensmall">{$fm->LANG['CensorDesc']}</p>
			<form action="setmembers.php" method="post">
			<input type="hidden" name="action" value="censor">
			<input type="hidden" name="process" value="1">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead">{$fm->LANG['Censor']}</th>
					</tr>
					<tr class="gen">
						<td align="center" class="row2"><textarea class="post" type="text" cols="60" rows="6" wrap="virtual" name="wordarray">{$bads}</textarea></td>
					</tr>
					<tr>
						<td class="catBottom" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
?>
