<?php
$subforums .= <<<DATA
				<tr>
					<td class="row4" align="center">{$folderpicture}</td>
					<td class="row4">
DATA;
if ($sponsor) {
$subforums .= <<<DATA
<div style="float: right">
{$sponsor}
</div>
DATA;
}
$subforums .= <<<DATA
						<b>{$sforumname}</b><i>{$viewing}</i>
						<br />
						<span class="desc">{$sforumdescription}
						<br />
						{$fm->_Modoutput}</span>
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
?>