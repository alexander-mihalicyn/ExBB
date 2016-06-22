<?php
if ($catrow) {
$linkcatid = (!stristr($forum['catid'], 'f')) ? 'index.php?c='.$forum['catid'] : 'setforums.php';
$forum_data .= <<<DATA
				<tr class="genmed">
					<td class="catLeft" height="28">
						<span class="cattitle">
							<b><a href="{$linkcatid}" class="cattitle">{$forum['catname']}</a></b>
						</span>
					</td>
					<td width="155" class="cat" align="center" valign="middle" nowrap="nowrap">
						<span class="genmed">
							<a href="setforums.php?action=editcatname&catid={$forum['catid']}" class="nav">{$fm->LANG['Change']}</a>
						</span> /
						<span class="genmed">
							<a href="setforums.php?action=delcat&catid={$forum['catid']}" class="nav">{$fm->LANG['Delete']}</a>
						</span>
					</td>
					<td width="225" class="cat" align="center" valign="middle" nowrap="nowrap">
						<span class="genmed">
							<a href="setforums.php?action=catorder&amp;move=-1&amp;catid={$forum['catid']}" class="nav">{$fm->LANG['MoveUp']}</a> /
							<a href="setforums.php?action=catorder&amp;move=1&amp;catid={$forum['catid']}" class="nav">{$fm->LANG['MoveDown']}</a>
						</span>
					</td>
					<td width="190" class="catRight" align="center" valign="middle" nowrap="nowrap">
						<span class="genmed">
							<a href="setforums.php?action=addforum&amp;catid={$forum['catid']}" class="nav">{$fm->LANG['ForumAddNew']}</a>
						</span>
					</td>
				</tr>
DATA;
}

$forum_data .= <<<DATA
				<tr class="genmed">
					<td class="row2">
						<span class="gen">
							<a href="forums.php?forum={$forumid}"><b>{$forum['name']}</b></a>
							<font color=green>{$private}</font>
						</span>
						<br>
						<span class="genmed">
							{$forum['desc']}
							<br>
							<span class="gensmall">
								{$fm->_Modoutput}
								<br>
								{$fm->LANG['Posts']}: <b>{$forum['posts']}</b> | {$fm->LANG['Topics']}: <b>{$forum['topics']}</b>
DATA;
if (!stristr($forum['catid'], 'f'))
$forum_data .= <<<DATA
							| <a href="setforums.php?subforum={$forumid}">{$sf}</a>
DATA;
$forum_data	.= <<<DATA
							</span>
						</span>
					</td>
					<td class="row1" align="center" valign="middle">
						<span class="gen">
							<a href="setforums.php?action=edit&forum={$forumid}">{$fm->LANG['Change']}</a> /
						</span>
						<span class="gen">
							<a href="setforums.php?action=delete&forum={$forumid}">{$fm->LANG['Delete']}</a>
						</span><br>
						<span class="gen">
							<a href="setforums.php?action=searchindex&forum={$forumid}">{$fm->LANG['SearchIndex']}</a>
						</span>
					</td>
					<td class="row2" align="center" valign="middle">
						<span class="gen">
							<a href="setforums.php?action=forumorder&amp;forum={$forumid}&amp;move=-1&amp;catid={$forum['catid']}">{$fm->LANG['MoveUp']}</a>
							<br>
							<a href="setforums.php?action=forumorder&amp;forum={$forumid}&amp;move=1&amp;catid={$forum['catid']}">{$fm->LANG['MoveDown']}</a>
						</span>
					</td>
					<td class="row1" align="center" valign="middle">
						<span class="gen">
							<a href="setforums.php?action=moveforum&amp;forum={$forumid}&amp;catid={$forum['catid']}">{$fm->LANG['MoveTo']}</a>
							<br>
							<a href="setforums.php?action=recount&forum={$forumid}">{$fm->LANG['Resync']}</a>
							<br>
							<a href="setforums.php?action=restore&forum={$forumid}">{$fm->LANG['Restoration']}</a>
						</span>
					</td>
				</tr>
DATA;
?>
