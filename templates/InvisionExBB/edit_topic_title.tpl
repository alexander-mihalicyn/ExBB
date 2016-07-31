<?php
echo <<<DATA
<br>
<div id="navstrip" align="left">
	<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/>&nbsp;<a
			href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; <a
			href="forums.php?forum={$forum_id}">{$forumname}</a> &nbsp;&raquo;&nbsp; <a
			href="topic.php?forum={$forum_id}&topic={$topic_id}">{$topicname}</a>
</div>
<form action="postings.php" method="post" name="postform">
	<input type="hidden" name="action" value="edittopic">
	<input type="hidden" name="request" value="yes">
	<input type="hidden" name="forum" value="{$forum_id}">
	<input type="hidden" name="topic" value="{$topic_id}">
	<table class="tableborder" cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;"
												   width="8" height="8"/>&nbsp;{$fm->LANG['EditTopic']}</td>
		</tr>
		<tr>
			<td class="pformleft"><b>{$fm->LANG['TopicName']}</b></td>
			<td class="pformright"><input type="text" name="topictitle" size="45" maxlength="255" style="width:450px"
										  value="{$topicname}"/></td>
		</tr>
		<tr>
			<td class="pformleft"><b>{$fm->LANG['TopicDesc']}</b></td>
			<td class="pformleft"><input type="text" name="description" size="45" maxlength="160" style="width:450px"
										 value="{$description}"/></td>
		</tr>
		<tr>
			<td class="pformleft"><b>{$fm->LANG['TopicKeywords']}</b></td>
			<td class="pformright"><input type="text" name="keywords" size="45" maxlength="255" style="width: 450px"
										  value="{$keywords}"/></td>
		</tr>
		<tr>
			<td class="pformstrip" align="center" colspan="2">
				<input type="submit" name="Submit" value="{$fm->LANG['Save']}"/>&nbsp;
				<input type="reset" name="{$fm->LANG['Clear']}"/>
			</td>
		</tr>
	</table>
</form>
DATA;
?>
