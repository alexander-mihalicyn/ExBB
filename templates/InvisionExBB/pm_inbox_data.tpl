<?php
$replycols = '';
if ($fm->input['action'] == 'inbox') {
$replycols	= <<<DATA
							<td>
								<a href="messenger.php?action=replyquote&msg={$message_id}" title="{$fm->LANG['ReplyQuote']}">{$fm->LANG['Reply']}</a></span>
							</td>
DATA;
}

$inbox_data .= <<<DATA
						<tr onmouseover="className='class_over'" onmouseout="className='class_out'" class="row4" align="center" valign="middle">
							<td style="padding:5px;margin-top:1px"><b>$ImgState</b></td>
							<td>$UserName</td>
							<td>$MessageTitle</td>
							<td>$MessageDate</td>
							{$replycols}
							<td><input name="msg[]" type="checkbox" value="$message_id"></td>
						</tr>
DATA;
