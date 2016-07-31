<?php
$topic_data .= <<<DATA
<table class="topic">
				<tr class="row4">
					<td class="normalname"><a name="{$key}"></a>{$username}</td>
					<td class="postdetails">
						<div>{$fm->LANG['PostDate']} {$postdate}</div> {$pinmsg} {$reply} {$quote} {$report} {$addpun} {$edit} {$del} {$postId}
					</td>
				</tr>
				<tr class="post2">
					<td class="postdetails">
	        			{$useravatar}<br /><br />
						{$teamcon}<br />
        				{$usertitle}<br />
        				{$usergraphic}<br /><br />
						{$username2}<br />
						{$quote2}<br /><br />
        				{$online}
        				{$posts}<br />
        				{$joined}
        				{$location}<br />
						{$reputation}<br />
        				{$karma}<br />
        				{$pun}<br />
         				<br />
        				<img src="./templates/InvisionExBB/im/spacer.gif" alt="" width="160" height="1" /><br />
					</td>
					<td class="postcolor" id="post{$key}">{$post}{$say_thank_d}</td>
				</tr>
				<tr class="darkrow3">
					<td class="desc">{$postIP}</td>
					<td class="postdetails">
						<div>{$say_thank_b} {$prf} {$eml} {$aim} {$www} {$icq} {$pm}</div>
						{$delbox}
					</td>
				</tr>
			</table>
            <div class="delemiter"></div>
DATA;
?>