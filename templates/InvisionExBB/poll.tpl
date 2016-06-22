<?php
$poll_html = <<<POLL
<!-- Poll Table -->
<form method="POST" action="post.php">
	<input type=hidden name="action" value="poll">
	<input type=hidden name="forum" value="{$forum_id}">
	<input type=hidden name="topic" value="{$topic_id}">
	<a name="poll"></a>
	<table width="100%" cellpadding="0" cellspacing="1" class="tableborder">
		<tr>
			<td class="maintitle"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['Poll']}</td>
		</tr>
		<tr>
			<td align="right" class="row4" style="padding:5px;" valign="middle">{$moderlinks}</td>
		</tr>
		<tr>
			<td align="center" class="tablepad">
				<table cellspacing="2" cellpadding="2" border="0">
					<tr>
						<td colspan="4" align="center"><b><span class="medium">{$poll_title}</span></b></td>
					</tr>
{$pollch}
				</table>
			</td>
		</tr>
		<tr>
			<td align="center" class="pformstrip"><span class="bigg">{$do}</span></td>
		</tr>
	</table>
</form>
<br>
<!-- Poll Table -->
POLL;
?>
