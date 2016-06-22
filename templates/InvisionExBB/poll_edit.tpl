<?php
echo <<<POLL
			<br />
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; <a href="forums.php?forum={$forum_id}">{$forumname}</a> &nbsp;&raquo;&nbsp; <a href="topic.php?forum={$forum_id}&topic={$topic_id}">{$topicname}</a>
			</div>
			<form name="pollform" action="postings.php" method="POST">
				<input type="hidden" name="action" value="poll">
				<input type="hidden" name="savepoll" value="yes">
				<input type="hidden" name="forum" value="{$forum_id}">
				<input type="hidden" name="topic" value="{$topic_id}">
				<table class="tableborder" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['PollEdit']}</td>
					</tr>
					<tr>
						<td class="pformleft"><b>{$fm->LANG['PollQuestion']}</b></td>
						<td class="pformright"><input type="text" name="pollname" style="width:300px" size="40" maxlength="255" value="{$pollname}"></td>
					</tr>
					<tr>
						<td class="pformleft" valign="top">
							<b>{$fm->LANG['PollAnswers']}</b>
							<br>
							{$fm->LANG['PollAnswersDesc']}
							<br>{$fm->LANG['PollEditMes']}
						</td>
						<td class="pformright"><textarea class="textinput" name="pollansw" rows="11" cols="90" style="width:300px" wrap="soft">{$pollansw}</textarea></td>
					</tr>
					<tr>
						<td class="pformleft"><b>&nbsp;</b></td>
						<td class="pformright">
							<input type="checkbox" name="respoll" value="yes">{$fm->LANG['ResetPoll']}<br>
							<input type="checkbox" name="delpoll" value="yes">{$fm->LANG['PollDel']}
						</td>
					</tr>
					<tr>
						<td class="pformstrip" align="center" style="text-align:center" colspan="2">
							<input type="submit" value={$fm->LANG['Save']} name="editpoll">
						</td>
					</tr>
				</table>
			</form>
			<br>
POLL;
?>