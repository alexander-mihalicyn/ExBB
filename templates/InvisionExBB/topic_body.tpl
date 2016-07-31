<?php
$navi = ($subf) ? '<a href="index.php?c='.$pcatid.'">'.$pcatname.'</a>&nbsp;&raquo;&nbsp;<a href="forums.php?forum='.$subf.'">'.$pforumname.'</a>' : '<a href="index.php?c='.$catid.'">'.$category.'</a>';

echo <<<DATA
			<br />
			<div id="navstrip" align="left">
				<H1><a href="topic.php?forum={$forum_id}&topic={$topic_id}" style="text-decoration:none">{$topic['name']}</a></H1>
				<img src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$navi}&nbsp;&raquo;&nbsp;<a href="forums.php?forum={$forum_id}">{$forumname}</a>&nbsp;&nbsp;<font style="font-size: 10px; color: #ff0000;"><b>{$fm->_Modoutput}</b></font>
			</div>
			<br />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="left" width="20%" nowrap="nowrap">&nbsp;{$pages}&nbsp;</td>
					<td align="right" width="80%">{$ReplyButton}{$NewPollButton}{$NewTopicButton}</td>
				</tr>
			</table>
			<br />
			{$poll_html}
			<table width="100%" cellpadding="0" cellspacing="1" class="tableborder">
				<tr>
					<td class="maintitle"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$topic['desc']}</td>
				</tr>
				<tr>
  					<td class="postlinksbar" align="right"><strong>{$options}</strong></td>
  				</tr>
				<tr>
					<td>
						{$topic_data}
					</td>
				</tr>
				<tr>
					<td class="postlinksbar" align="right"><strong>{$options}</strong></td>
 	 			</tr>
  				<tr>
  					<td class="row2" style="padding:6px">{$pages}</td>
  				</tr>
DATA;
if ($stattopic) {
echo <<<DATA
				<tr>
  					<td class="row2" style="padding:6px">{$stattopic}</td>
  				</tr>
DATA;
}
echo <<<DATA
  				<tr>
  					<td class="activeuserstrip" align="center"><strong>&laquo; <a href="forums.php?forum={$forum_id}" title="{$fm->LANG['GoForumBack']} &quot;{$forumname}&quot;">{$forumname}</a> &raquo;</strong></td>
  				</tr>
			</table>
			<br />
			<table width="100%" border="0">
    			<tr>
        			<td width="50%">
						{$mod_options}
					</td>
					<td width="50%" align="right">
						{$jumphtml}
					</td>
				</tr>
			</table>
			<br />
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr valign="top">
					<td align="left" width="20%" nowrap="nowrap">{$perms}</td>
					<td align="right" width="80%">{$ReplyButton}{$NewPollButton}{$NewTopicButton}</td>
				</tr>
			</table><!--
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="right" width="80%">{$ReplyButton}{$NewPollButton}{$NewTopicButton}</td>
				</tr>
			</table>-->
			<br />
			{$post_form}
			<br />
			<br />
DATA;
?>
