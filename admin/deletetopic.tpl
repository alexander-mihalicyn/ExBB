<?php
echo <<<DATA
<form action="postings.php" method="post" name="postform" onsubmit="return checkForm(this)">
	<input type="hidden" name="action" value="delete">
	<input type="hidden" name="checked" value="yes">
	<input type="hidden" name="forum" value="{$inforum}">
	<input type="hidden" name="topic" value="{$intopic}">
	<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
		<tr>
			<td align="left"><span  class="nav"><a href="index.php" class="nav">{$exbb['boardname']}</a> -> {$lang['Topic_deleting']}</span></td>
		</tr>
	</table>
	<table border="0" cellpadding="3" cellspacing="1" width="100%" class="forumline">
		<tr>
			<th class="thHead" height="25"><b>{$lang['Topic_deleting']}</b></th>
		</tr>
		<tr>
			<td class="row1" align="center"><span class="gen"><b>{$lang['Topic_deleting_not']}</b></span></td>
		</tr>
		<tr>
			<td class="catBottom" align="center" height="28"><input type="submit" tabindex="3" name="Submit" class="mainoption" value="{$lang['Delete']}" /></td>
		</tr>
	</table>
</form>
DATA;

