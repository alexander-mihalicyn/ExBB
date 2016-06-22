<?php
echo <<<DATA
			<h1>{$fm->LANG['AdminSmiles']}</h1>
			<form method="post" action="setsmiles.php">
				$hidden
				<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
					<tr>
						<th colspan="2" class="thCornerL">{$tabletitle}</th>
					</tr>
					<tr class="genmed">
						<td class="row2">{$descfieldtitle}</td>
						<td class="row2"><input name="newcatdesc" type="text" value="{$descfieldvalue}" maxlength="255" size="40"></td>
					</tr>
					<tr>
						<td colspan="2" class="catBottom" align="center"><input type="submit" name="SaveCat" value="{$inputtitle}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
?>