<?php
echo <<<DATA
			<h1>{$fm->LANG['VisitsLogs']}</h1>
			<p class="genmed">{$fm->LANG['VisitsLogsNotify']}</p>
			<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
				<tr>
					<th class="thHead">
						<form name="log" action="setmembers.php" method="post">
							{$fm->LANG['LogTitle']}
							<input name="action" type="hidden" value="log">
							<select name="logdate" onchange="document.log.submit()">
								{$selectlog}
							</select>
							<noscript>
								<input type="submit" name="showlog" value="{$fm->LANG['ShowLog']}">
							</noscript>
						</form>
					</th>
				</tr>
				<tr class="gen">
					<td class="row1"><div style="font-size:12px;overflow: auto;width:100%;height: 300px;">{$logdata}<br><br></div></td>
				</tr>
				<tr class="gen">
					<td class="catBottom" align="center">
						<form name="logfile" action="setmembers.php" method="post">
                        	<input name="action" type="hidden" value="log">
                        	<input name="log_name" type="hidden" value="{$log_name}">
                        	{$fm->LANG['DelLog']} &nbsp; <input class="mainoption" type="submit" name="DelLog" value="{$fm->LANG['DelLogDay']}"> &nbsp;
							<input class="mainoption" type="submit" name="DelAllLog" value="{$fm->LANG['DelAllLogs']}">
</form>

</a></td>
</tr>
</table>
DATA;
