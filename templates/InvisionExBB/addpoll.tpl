<?php
$err = ($error) ? '<font color="#ff0000">'.implode('<br>', $error).'<br><br>' : '';

echo <<<DATA
<div id="navstrip" align="left"><br>
	<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/>&nbsp;<a
			href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; <a
			href="forums.php?forum={$forum_id}">{$forumname}</a> &nbsp;&raquo;&nbsp; <a
			href="topic.php?forum={$forum_id}&topic={$topic_id}">$topicname</a>
	<br><br></div>
{$err}
<table class="tableborder" cellpadding="0" cellspacing="0" width="100%">
	<form method="post">
		<tr>
			<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;"
												   width="8" height="8"/>&nbsp;{$fm->LANG['AdditionPoll']}</td>
		</tr>
		<tr>
			<td class="pformleft"><b>{$fm->LANG['PollQuestion']}</b></td>
			<td class="pformright">
				<input type="hidden" name="poll" value="yes">
				<input type="text" name="pollname" style="width:450px" size="40" maxlength="255"
					   value="{$fm->input['pollname']}">
			</td>
		</tr>
		<tr>
			<td class="pformleft" valign="top">
				<b>{$fm->LANG['PollAnswers']}</b>
				<br>
				{$fm->LANG['PollAnswersDesc']}
			</td>
			<td class="pformright"><textarea name="pollansw" style="width:380px" rows="10" cols="35"
											 wrap="soft">{$fm->input['pollansw']}</textarea></td>
		</tr>
		<tr>
			<td class="pformstrip" align="center" style="text-align:center" colspan="2">
				<input type="submit" name="submit" value="{$fm->LANG['Send']}" accesskey="s"/> &nbsp;
				<input type="reset" name="Clear" value="{$fm->LANG['Clear']}"/>
			</td>
		</tr>
	</form>
</table>
DATA;
?>