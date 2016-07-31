<?php
echo <<<TOPIC
			<br />
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" /> <a href="index.php" title="{$fm->exbb['boardname']}">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['TopicsRaiting']}
			</div>
			<table class="tableborder" width="100%" border="0" cellspacing="1" cellpadding="3">
			<tr>
				<th colspan="3" class="maintitle" align="left"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['LovelyTopics']} </th>
			</tr>
			<tr>
				<th class="titlemedium">{$fm->LANG['ByLastPost']}</th>
				<th class="titlemedium">{$fm->LANG['ByCountPosts']}</th>
				<th class="titlemedium">{$fm->LANG['ByCountViews']}</th>
			</tr>
			<tr>
				<td class="row1" width="33%">{$topic_by_lastpost}</td>
				<td class="row1" width="33%">{$topic_by_post}</td>
				<td class="row1" width="33%">{$topic_by_views}</td>
			</tr>
			<tr>
				<td class="darkrow2" colspan="3">&nbsp;</td>
			</tr>
		</table>
TOPIC;
