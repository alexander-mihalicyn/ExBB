<?php
echo <<<DATA
			<h1>{$fm->LANG['AdminSmiles']}</h1>
			<p class="gensmall">{$fm->LANG['SmGroupHelp']}</p>
			<form method="post" action="setsmiles.php">
				<input type="hidden" name="action" value="addgroup" />
        		<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
					<tr>
                		<th class="thCornerL">{$fm->LANG['InCat']}</th>
                		<th class="thTop">{$fm->LANG['SmCode']}</th>
                		<th class="thTop">{$fm->LANG['SmileFile']}</th>
                		<th class="thTop">{$fm->LANG['SmileDesc']}</th>
        			</tr>
        			$datashow
        			<tr>
                		<td class="catBottom" colspan="5" align="center">
                			<input type="submit" name="DoAddGroup" value="{$fm->LANG['AddGroupSmiles']}" class="mainoption" />
                		</td>
        			</tr>
				</table>
			</form>
DATA;
?>
