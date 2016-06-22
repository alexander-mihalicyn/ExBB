<?php
$board_data_header = <<<DATA
			<br />
			<table class="tableborder" width="100%" border="0" cellspacing="1" cellpadding="4">
				<tr>
					<th colspan="5" class="maintitle" align="left"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;<a href="index.php?c={$in_cat}">{$category}</a></th>
				</tr>
				<tr>
					<th align="center" width="2%" class="titlemedium"><img src="./templates/InvisionExBB/im/spacer.gif" alt="" width="28" height="1" /></th>
					<th align="left" width="59%" class="titlemedium">{$fm->LANG['ForumInfo']}</th>
					<th align="center" width="7%" class="titlemedium">{$fm->LANG['TopicsTotal']}</th>
					<th align="center" width="7%" class="titlemedium">{$fm->LANG['Replies']}</th>
					<th align="left" width="25%" class="titlemedium">{$fm->LANG['Updates']}</th>
				</tr>
DATA;
$board_data_footer = <<<DATA
				<tr>
					<td class="darkrow2" colspan="5">&nbsp;</td>
				</tr>
			</table>
DATA;

$board_data .=  ($catrow) ?  $board_data_header: '';

$board_data .= <<<DATA
				<tr>
					<td class="row4" align="center">{$folderpicture}</td>
					<td class="row4">
DATA;
if ($sponsor) {
$board_data .= <<<DATA
<div style="float: right">
{$sponsor}
</div>
DATA;
}
$board_data .= <<<DATA
						<b>{$forumname}</b><i>{$viewing}</i>
						<br />
						<span class="desc">{$forumdescription}
						<br />
						{$fm->_Modoutput}
						{$sub}</span>
					</td>
					<td class="row2" align="center">{$threads}</td>
					<td class="row2" align="center">{$posts}</td>
					<td class="row2">
						{$fm->LANG['Date']} <b>{$LastTopicDate}</b>
						<br />
						{$LastTopicName}
						<br />
						{$LastPosterName}
					</td>
				</tr>
DATA;
$board_data .=  ($last) ? $board_data_footer: '';
?>
