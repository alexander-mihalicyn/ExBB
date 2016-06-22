<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img name="formtop" src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; <a href="forums.php?forum={$forum_id}">{$forumname}</a> &nbsp;&raquo;&nbsp; <a href="topic.php?forum={$forum_id}&topic={$topic_id}&postid={$post_id}#{$post_id}">$topicname</a>
			</div>
			<form name="PostReport" action="tools.php" method="POST">
				<input type="hidden" name="action" value="preport">
            	<input type="hidden" name="dosave" value="yes">
            	<input type="hidden" name="forum" value="{$forum_id}">
            	<input type="hidden" name="topic" value="{$topic_id}">
            	<input type="hidden" name="postid" value="{$post_id}">
				<table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
    				<tr>
      					<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['TableTitle']}</td>
    				</tr>
    				<tr>
      					<td class="pformleft"><b>{$fm->LANG['Name']}</b></td>
      					<td class="pformright"><b>{$fm->user['name']}</b></td>
    				</tr>
    				<tr>
      					<td class="pformleft" valign="top"><b>{$fm->LANG['YouCanAddMessage']}</b></td>
      					<td class="pformright" valign="top">
      						<textarea cols="80" rows="11" name="preporttext" class="textinput" style="width:560px;"></textarea>
      					</td>
    				</tr>
    				<tr>
      					<td class="pformstrip" align="center" style="text-align:center" colspan="2">
        					<input type="submit" value="{$fm->LANG['Send']}" name="send" /> &nbsp;
        					<input type="reset" name="Clear" value="{$fm->LANG['Clear']}" />
      					</td>
    				</tr>
  				</table>
  			</form>
  		</div>
  		<br>
DATA;
?>
