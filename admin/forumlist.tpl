<?php
$linkaddcatnew = (!$subforum) ? 'setforums.php?action=addcat' : 'setforums.php?action=addforum&catid=f'.$subforum;
echo <<<DATA
			<h1>{$fm->LANG['AdminForums']}</h1>
			{$safe_mode}
			<span class="genmed">{$fm->LANG['AdminForumsDesc']}<br><br></span>
			<span class="gen">{$fm->LANG['RefreshForumInfo']}<br><br></span>
			<table width="100%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
				<tr>
					<th class="thHead" colspan="4"><a href="{$linkaddcatnew}" class="nav"><font color="#FFFFFF">{$fm->LANG['CatAddNew']}</font></a></th>
				</tr>
				{$forum_data}
				<tr>
					<th class="thHead" colspan="4"><a href="{$linkaddcatnew}" class="nav"><font color="#FFFFFF">{$fm->LANG['CatAddNew']}</font></a></th>
				</tr>
			</table>
			<br>
DATA;

