<?php
echo <<<DATA
			<h1>{$fm->LANG['AdminRanks']}</h1>
			<p class="genmed">{$ActionTitleDesc}</p>
			<form method="post" action="setranks.php">
				<input type=hidden name="action" value="do{$fm->input['action']}">
				{$hidden}
				<table class="forumline" cellspacing="1" cellpadding="4" border="0" align="center">
					<tr>
						<th class="thHead" colspan="2">{$ActionTitle}</th>
					</tr>
					<tr class="gen">
						<td class="row2">{$fm->LANG['RankTitle']}</td>
						<td class="row2"><input class="post" type="text" name="title" size="25" maxlength="50" value="{$ranks['title']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RankMinimum']}<br /><span class="gensmall">{$fm->LANG['RankMinimumMes']}</span></td>
						<td class="row1"><input type="text" name="min_posts" size="5" maxlength="10" value="{$ranks['posts']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RankImage']}<br /><span class="gensmall">{$fm->LANG['RankImageMes']}</span></td>
						<td class="row2"><input class="post" type="text" maxlength="40" size="60" name="rank_image" value="{$ranks['icon']}" /></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="2" align="center"><input class="mainoption" type="submit" value="{$fm->LANG['Save']}" /></td>
					</tr>
				</table>
			</form>
DATA;
