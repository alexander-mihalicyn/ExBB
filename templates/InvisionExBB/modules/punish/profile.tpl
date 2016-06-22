<?php
$mod_punish =<<<PUNISH
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['UserPunnedTopics']}</b></td>
					<td class="pformright">
						<table width="100%" cellpadding="3" cellspacing="1" class="tableborder">
							<tr align="center" valign="middle" class="titlemedium">
								<th width="30%">{$fm->LANG['Thread']}</td>
								<th width="25%">{$fm->LANG['Forum']}</td>
								<th width="30%">{$fm->LANG['Who']}</td>
							</tr>
							{$punish_data}
						</table>
					</td>
				</tr>
PUNISH;
?>
