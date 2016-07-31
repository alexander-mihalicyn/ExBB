<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; {$fm->LANG['TopicMoving']}
			</div>
			<form action="postings.php" method="post" name="postform">
				<input type="hidden" name="action" value="movetopic">
				<input type="hidden" name="request" value="yes">
				<input type="hidden" name="forum" value="{$forum_id}">
				<input type="hidden" name="topic" value="{$topic_id}">
				<table class="tableborder" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['TopicMoving']}</td>
					</tr>
					<tr>
						<td class="pformleft"><b>{$fm->LANG['MoveOptions']}</b></td>
						<td class="pformright">
							<input name="block" type="radio" value="yes" checked> {$fm->LANG['StayBlocked']}<br />
							<input name="block" type="radio" value="no"> {$fm->LANG['TopDelete']}
						</td>
					</tr>
					<tr>
						<td class="pformleft"><b>{$fm->LANG['MoveIn']}</b></td>
						<td class="pformright">
							{$jumphtml}
						</td>
					</tr>
					<tr>
						<td class="pformstrip" align="center" colspan="2"><input type="submit" name="Submit" value="{$fm->LANG['Send']}" /></td>
					</tr>
				</table>
			</form>
DATA;
