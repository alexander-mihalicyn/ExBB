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
		</table>
		<br>
DATA;
