<?php
echo <<<DATA
			<br />
			<table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
				<tr>
					<th colspan="3" class="maintitle" align="left">
					<img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['PM']} </th>
				</tr>
				<tr>
					<td valign="middle" align="center" class="tablepad">
						<a href="messenger.php?action=inbox"><img src="{$InBoxIcon}" width="108" height="30" border="0"></a> &nbsp; &nbsp; &nbsp;
						<a href="messenger.php?action=outbox"><img src="{$OutBoxIcon}" width="115" height="30" border="0"></a> &nbsp; &nbsp; &nbsp;
						<a href="messenger.php?action=new"><img src="{$NewPMIcon}" width="94" height="30" border="0"></a>
						<br>
						<br>
						{$fm->LANG['NewPMMessage']}
					</td>
				</tr>
				<tr>
					<td class="pformstrip" align="center">
						<blockquote>{$fm->LANG['NoticePM']}</blockquote>
					</td>
				</tr>
				<tr>
					<td class="activeuserstrip" align="center">&nbsp;</td>
				</tr>
			</table>
DATA;
