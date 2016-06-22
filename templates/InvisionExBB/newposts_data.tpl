<?php
$data .= <<<DATA
				<tr>
					<td align="center" class="row4">{$topicicon}</td>
					<td class="row4"><b>{$topicname}</b><br>{$topicdesc}</td>
					<td align="center" class="row2">{$forumname}</td>
					<td align="center" class="row2">
						{$fm->LANG['Replies']}: <b>{$posts}</b>
						<br>
						{$fm->LANG['TopicAuthor']}: <b>{$author}</b></td>
					<td class="row2">
						<span class="desc">
							{$postdate}
							<br>
							{$fm->LANG['Author']}: <b>{$poster}</b>
						</span>
					</td>
				</tr>
DATA;
?>

