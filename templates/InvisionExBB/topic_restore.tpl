<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['TopicRestore']} <a href="forums.php?forum={$forum_id}">{$forumname}</a>
			</div>
			<br>
			<form action="postings.php" method="post">
				<input type="hidden" name="action" value="restore">
				<input type="hidden" name="request" value="yes">
				<input type="hidden" name="forum" value="{$forum_id}">
				<input type="hidden" name="topic" value="{$topic_id}">
				<table cellpadding="4" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
				<tr>
					<td class="maintitle" valign="middle" colspan="2" align="center" height="29"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['TopicRestore']}</td>
				</tr>
				<tr>
					<td valign="middle" class="profilleft">{$fm->LANG['RecoverName']}</td>
					<td valign="middle" class="profilright"><input type="text" name="topictitle" value="{$topicname}" size="40" maxlength="255"></td>
				</tr>
				<tr>
					<td class="profilleft" valign="middle">{$fm->LANG['RecoverDesc']}</td>
					<td class="profilright" valign="middle"><input type="text" name="description" value="{$description}" size="40" maxlength="160"></td>
				</tr>
				<tr>
					<td class="titlemedium" valign="middle" colspan="2" align="center"><b>{$fm->LANG['RecoverHelp']}</b></td>
				</tr>
				<tr valign="middle">
					<td class="profilleft">{$fm->LANG['TopicMisc']}</td>
					<td class="profilright">
						$author
						<br>
						$time ($date)
						<br>
						{$fm->exbb['boardurl']}/topic.php?forum={$forum_id}&topic={$topic_id};
					</td>
				</tr>
				<tr valign="middle">
					<td class="profilleft">{$fm->LANG['FirstPost']}</td>
					<td class="profilright">$post</td>
				</tr>
				<tr valign="middle">
					<td class="profilleft" colspan="2">{$fm->LANG['RecoverNote']}</td>
				</tr>
				<tr>
					<td class="pformstrip" align="center" colspan="2">
						<input type="submit" name="submit" value="{$fm->LANG['Recover']}">
					</td>
				</tr>
			</table>
		</form>
DATA;

