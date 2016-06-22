<?php
$topics .= <<<DATA
<a href="tools.php?action={$fm->input['action']}#{$id}" title="{$topic}">{$topic}</a> {$desc}
<br />
DATA;
$content.= <<<DATA
			<a name="{$id}"></a>
			<table class="tableborder" width="100%" border="0" cellspacing="1" cellpadding="4">
				<tr>
					<th align="center" class="titlemedium">{$topic}</th>
				</tr>
				<tr>
					<td class="row4">
						{$text}
					</td>
				</tr>
				<tr class="darkrow3">
					<td class="postdetails"><a href="#top" onClick="scroll(0,0);return false"><img src="./templates/InvisionExBB/im/gotop.gif" alt="Top" border="0" /></a></td>
				</tr>
		</table>
		<br>
DATA;
?>
