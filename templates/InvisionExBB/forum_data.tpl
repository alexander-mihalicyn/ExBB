<?php
$forum_data .= <<<DATA
				<tr>
					<td align="center" class="row4">{$topicicon}</td>
					<td class="row4">{$uploadicon}{$topictitle}{$pollicon}{$pagestoshow} {$description}</td>
					<td align="center" class="row2"><b>{$author}</b></td>
					<td align="center" class="row4">{$posts}</td>
					<td align="center" class="row2">&nbsp;{$views}&nbsp;</td>
					<td class="row2"><span class="desc">{$last_msg}<br />{$lastpostdate}<br />{$fm->LANG['Author']}: <b>{$poster}</b></span></td>
				</tr>
DATA;
?>
