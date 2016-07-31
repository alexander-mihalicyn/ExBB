<?php
echo <<<DATA
			<h1>{$fm->LANG['AdminRanks']}</h1>
			<p class="genmed">{$fm->LANG['AdminRanksDesc']}</p>
			<form method="post" action="setranks.php">
				<input type="hidden" name="action" value="add" />
				<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
					<tr>
						<th class="thCornerL">{$fm->LANG['RankTitle']}</th>
						<th class="thTop">{$fm->LANG['RankMinimum']}</th>
						<th class="thTop">{$fm->LANG['Change']}</th>
						<th colspan="2" class="thCornerR">{$fm->LANG['Delete']}</th>
					</tr>
					{$ranksdata}
					<tr>
						<td class="catBottom" colspan="4" align="center"><input type="submit" name="add" value="{$fm->LANG['CreateNewRank']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
DATA;

