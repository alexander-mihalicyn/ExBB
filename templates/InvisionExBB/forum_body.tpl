<?php
$navi = ($subf) ? '<a href="index.php?c='.$pcatid.'">'.$pcatname.'</a>&nbsp;&raquo;&nbsp;<a href="forums.php?forum='.$subf.'">'.$pforumname.'</a>' : '<a href="index.php?c='.$catid.'">'.$category.'</a>';
$sub = <<<DATA
<br>
<table class="tableborder" width="100%" border="0" cellspacing="1" cellpadding="4">
				<tr>
					<th colspan="5" class="maintitle" align="left"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$sublist}</a></th>
				</tr>
				<tr>
					<th align="center" width="2%" class="titlemedium"><img src="./templates/InvisionExBB/im/spacer.gif" alt="" width="28" height="1" /></th>
					<th align="left" width="59%" class="titlemedium">{$fm->LANG['ForumInfo']}</th>
					<th align="center" width="7%" class="titlemedium">{$fm->LANG['TopicsTotal']}</th>
					<th align="center" width="7%" class="titlemedium">{$fm->LANG['Replies']}</th>
					<th align="left" width="25%" class="titlemedium">{$fm->LANG['Updates']}</th>
				</tr>
				{$subforums}
</table><br><br>
DATA;
$sub = ($subforums) ? $sub : '';
echo <<<DATA
			<br />
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$navi}&nbsp;&raquo;&nbsp;<a href="forums.php?forum={$forum_id}">{$forumname}</a>&nbsp;&nbsp;<font style="font-size: 10px; color: #ff0000;"><b>{$fm->_Modoutput}</b></font>
			</div>
			{$sub}
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left" width="20%">{$topicpages}</td>
					<td align="right" width="80%">{$ImgNewPollButton} {$ImgNewTopicButton}</td>
				</tr>
			</table>
			<br />
			<table width="100%" border="0" cellspacing="1" cellpadding="4" class="tableborder">
				<tr>
					<td class="maintitle" colspan="6"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$forumname}</td>
				</tr>
				<tr>
					<th align="center" class="titlemedium"><img src="./templates/InvisionExBB/im/spacer.gif" width="20" height="1" /></th>
					<th width="47%" align="left" nowrap="nowrap" class="titlemedium">{$fm->LANG['Topics']}</th>
					<th width="14%" align="center" nowrap="nowrap" class="titlemedium">{$fm->LANG['TopicAuthor']}</th>
					<th width="7%" align="center" nowrap="nowrap" class="titlemedium">{$fm->LANG['Replies']}</th>
					<th width="7%" align="center" nowrap="nowrap" class="titlemedium">{$fm->LANG['Views']}</th>
					<th width="25%" align="left" nowrap="nowrap" class="titlemedium">{$fm->LANG['Updates']}</th>
				</tr>
				{$forum_data}
				<tr>
					<td class="darkrow2" style="padding:6px" colspan="6">&nbsp;</td>
				</tr>
				<tr>
					<td class="row2" style="padding:6px" colspan="3">{$topicpages}</td>
					<td class="row2" style="padding:6px" colspan="3" align="right">{$options}&nbsp;&nbsp;{$markforum}</td>
				</tr>
DATA;
if ($statforum) {
echo <<<DATA
				<tr>
					<td class="row2" style="padding:6px" colspan="6">{$statforum}</td>
				</tr>
DATA;
}
echo <<<DATA
				<tr>
					<td class="darkrow2" style="padding:4px" colspan="6">
						<form name="postform" action="forums.php" method="get" class="gentext" onSubmit="SubmitControl(this)">
							<input type="hidden" name="forum" value="{$forum_id}" />{$fm->LANG['FilterBy']}
							<select name="filterby" class="forminput">
								<option value="topnam">{$fm->LANG['FilterByTopic']}</option>
								<option value="topdesc">{$fm->LANG['FilterByDesc']}</option>
								<option value="author">{$fm->LANG['FilterByAuthor']}</option>
							</select>
							<input type="text" name="word" size=10 value="{$word}" class="forminput" />
							{$resetfiltr}&nbsp;&nbsp;&nbsp;{$fm->LANG['SortBy']}
							<select name="sort">{$sorting}</select>
							<select name="order">{$ordering}</select>&nbsp;&nbsp;&nbsp;
							<input type="submit" value="ok" onClick="return Formchecker(this.form)" class="forminput" />
						</form>&nbsp;&nbsp;&nbsp;
						{$jumphtml}
					</td>
				</tr>
			</table>
			<br />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr valign="top">
					<td align="left" width="20%" nowrap="nowrap">{$perms}</td>
					<td align="right" width="80%">{$ImgNewPollButton} {$ImgNewTopicButton}</td>
				</tr>
			</table>
			<br />
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr>
					<td><img src="./templates/InvisionExBB/im/new.gif" border="0" align="absmiddle"/>&nbsp;&nbsp;{$fm->LANG['TopicOpenNew']}</td>
					<td><img src="./templates/InvisionExBB/im/stickynew.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicPinnedNew']}</td>
					<td><img src="./templates/InvisionExBB/im/hotnew.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicHotNew']}</td>
					<td><img src="./templates/InvisionExBB/im/locked.gif" border="0" align="absmiddle"/>&nbsp;&nbsp;{$fm->LANG['TopicClosed']}</td>
				</tr>
				<tr>
					<td><img src="./templates/InvisionExBB/im/nonew.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicOpenNoNew']}</td>
					<td><img src="./templates/InvisionExBB/im/sticky.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicPinnedNoNew']}</td>
					<td><img src="./templates/InvisionExBB/im/hotnonew.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicHotNoNew']}</td>
					<td><img src="./templates/InvisionExBB/im/moved.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicMoved']}</td>
				</tr>
			</table>
DATA;
?>
