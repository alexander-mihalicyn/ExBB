<?php
echo <<<DATA
			<br />
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['NewPosts']}
			</div>
			<table border="0" width="100%">
				<tr>
					<td>{$searchinmessage}</td>
					<td align="right" valign="bottom"><a href="index.php?action=resetall" title="Отметить все темы как прочтённые">Отметить все темы как прочтённые</a></td>
				</tr>
			</table>
			{$pages}
			<br />
			<br />
			<table width="100%" border="0" cellspacing="1" cellpadding="4" class="tableborder">
				<tr>
					<td class="maintitle" colspan="6">
						<img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['SearchTotalTopics']} {$found}
					</td>
				</tr>
				<tr>
					<th align="center" class="titlemedium"><img src="./templates/InvisionExBB/im/spacer.gif" width="20" height="1" /></th>
					<th width="47%" align="left" nowrap="nowrap" class="titlemedium">{$fm->LANG['Topics']}</th>
					<th width="14%" align="enter" nowrap="nowrap" class="titlemedium">{$fm->LANG['Forum']}</th>
					<th width="14%" align="center" nowrap="nowrap" class="titlemedium">{$fm->LANG['TopicInfo']}</th>
					<th width="25%" align="left" nowrap="nowrap" class="titlemedium">{$fm->LANG['Updates']}</th>
				</tr>
				{$data}
				<tr>
					<td class="titlemedium" colspan="6">&nbsp;</td>
				</tr>
			</table>
			<br />
			{$pages}
			<br />
			<br />
			<table width="100%" border="0" cellspacing="0" cellpadding="2">
				<tr nowrap>
					<td width="33%" nowrap><img src="./templates/InvisionExBB/im/new.gif" border="0" align="absmiddle"/>&nbsp;&nbsp;{$fm->LANG['TopicOpenNew']}</td>
					<td width="33%"><img src="./templates/InvisionExBB/im/locked.gif" border="0" align="absmiddle"/>&nbsp;&nbsp;{$fm->LANG['TopicClosed']}</td>
					<td width="33%" rowspan="4">&nbsp;</td>
				</tr>
				<tr>
					<td nowrap><img src="./templates/InvisionExBB/im/nonew.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicOpenNoNew']}</td>
					<td><img src="./templates/InvisionExBB/im/moved.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicMoved']}</td>
				</tr>
				<tr>
					<td nowrap><img src="./templates/InvisionExBB/im/hotnew.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicHotNew']}&nbsp;&nbsp;&nbsp;</td>
					<td><img src="./templates/InvisionExBB/im/stickynew.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicPinnedNew']}</td>
				</tr>
				<tr>
					<td nowrap><img src="./templates/InvisionExBB/im/hotnonew.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicHotNoNew']}</td>
					<td><img src="./templates/InvisionExBB/im/sticky.gif" border="0" align="absmiddle" />&nbsp;&nbsp;{$fm->LANG['TopicPinnedNoNew']}</td>
				</tr>
			</table>
DATA;
?>
