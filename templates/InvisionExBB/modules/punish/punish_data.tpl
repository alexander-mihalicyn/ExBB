<?php
$punish_data .= <<<DATA
				<tr>
					<td class="row4" align="center">&nbsp;<a href="topic.php?forum={$forum_id}&topic={$topic_id}&postid={$post_id}#{$post_id}" target="_blank">{$topicname}</a></td>
					<td class="row4" align="center">&nbsp;<a href="forums.php?forum={$forum_id}" target="_blank">{$forumname}</a></td>
					<td class="row4" align="center">&nbsp;$whoadd</td>
DATA;
if ($showrow === TRUE) {
$punish_data .= <<<DATA
					<td class="row4" align="center">&nbsp;<a href="tools.php?action=punish&doact=delpun&forum={$forum_id}&user={$user['id']}&id={$id}">{$fm->LANG['Delete']}</a></td>
DATA;
}
$punish_data .= <<<DATA
				</tr>
DATA;
