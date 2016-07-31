<?php
$RequestForm = <<<DATA
<br />
				<form action="{$_SERVER['PHP_SELF']}" method="post" name="request">
					{$hiddinfield}
					<table cellpadding="4" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
						<tr>
							<th class="maintitle" align="left">
								<img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$formtitle}
							</th>
						</tr>
						<tr>
							<td class="tdrow1" valign="top">{$request_text}</td>
						</tr>
						<tr>
							<td class="darkrow2" valign="middle" align="center">
								<input type="submit" value="{$fm->LANG['Request']}"> &nbsp;
								<input type="button" value="{$fm->LANG['Cancel']}" onClick="javascript:history.go(-1)">
							</td>
						</tr>
					</table>
				</form>
				<br>
DATA;
