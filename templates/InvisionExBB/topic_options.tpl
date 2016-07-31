<?php
$mod_options = <<<DATA
<form method="post" name="ModOptions" action="postings.php">
	<input type="hidden" name="forum" value="{$forum_id}">
	<input type="hidden" name="topic" value="{$topic_id}">
	<input type="hidden" name="postkey" value="">
	<select name="action"  style="font-weight:bold;">
		<option value="-1" style="color:black">---- {$fm->LANG['MsgsOptions']} ----</option>
		<option value="delselected">{$fm->LANG['DelSelected']}</option>
		<option value="innew">{$fm->LANG['MoveInNew']}</option>
		<option value="inexists">{$fm->LANG['MoveInExists']}</option>
		<option value="delattach">{$fm->LANG['AttachDelSelected']}</option>
		<option value="-1" style="color:black"></option>
		<option value="-1" style="color:black">---- {$fm->LANG['TopicOptions']} ----</option>
		<option value="edittopic">{$fm->LANG['EditTitle']}</option>
		<option value="{$do}">{$fm->LANG['Unlock']}</option>
		{$pin}
		<option value="delete">{$fm->LANG['Delete']}</option>
		<option value="top_recount">{$fm->LANG['TopRecount']}</option>
		<option value="movetopic">{$fm->LANG['Move']}</option>
		<option value="trackers">{$fm->LANG['DelTrackers']}</option>
		<option value="restore">{$fm->LANG['TopRestore']}</option>
	</select>&nbsp;
	<input name="chek" type="checkbox" onClick="ChekUncheck()" title="{$fm->LANG['SelectAll']}"> &nbsp;
	<input type="button" value="Go!" onClick="CheckFormAction();" />
</form>
DATA;
