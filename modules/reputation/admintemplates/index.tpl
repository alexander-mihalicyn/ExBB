<?php
echo <<<DATA
			<h1>{$fm->LANG['ModuleTitle']}</h1>
			<form action="setmodule.php" method="post">
			<input type="hidden" name="module" value="reputation">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
        			<tr>
          				<th class="thHead" width="70%">{$fm->LANG['Variable']}</th>
          				<th class="thHead">{$fm->LANG['VariableValue']}</th>
       	 			</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RepMsgs']}<br /></td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="msg" value="{$msg}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RepWait']}<br /></td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="wait_days" value="{$wait_days}" /> {$fm->LANG['RepDays']}
						<input class="post" type="text" size="2" maxlength="2" name="wait_hours" value="{$wait_hours}" /> {$fm->LANG['RepHours']}
						<input class="post" type="text" size="2" maxlength="2" name="wait_minutes" value="{$wait_minutes}" /> {$fm->LANG['RepMinutes']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RepProtect']}<br /></td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="protect_days" value="{$protect_days}" /> {$fm->LANG['RepDays']}
						<input class="post" type="text" size="2" maxlength="2" name="protect_hours" value="{$protect_hours}" /> {$fm->LANG['RepHours']}
						<input class="post" type="text" size="2" maxlength="2" name="protect_minutes" value="{$protect_minutes}" /> {$fm->LANG['RepMinutes']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RepReason']}<br /></td>
						<td class="row2">{$fm->LANG['RepFrom']} <input class="post" type="text" size="3" maxlength="3" name="size_min" value="{$size_min}" />
						{$fm->LANG['RepTill']} <input class="post" type="text" size="4" maxlength="4" name="size_max" value="{$size_max}" />
						{$fm->LANG['RepSymbols']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RepPerPage']}<br /></td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="per_page" value="{$per_page}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RepGuest']}<br /></td>
						<td class="row2"><input type="radio" name="guest" value="yes" {$guest_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="guest" value="no" {$guest_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['RepDenied']}<br /></td>
						<td class="row2"><input type="radio" name="denied" value="yes" {$denied_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="denied" value="no" {$denied_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1" valign="top">{$fm->LANG['RepBlackList']}<br /></td>
						<td class="row2"><textarea class="post" style="width: 100%;" rows="8" name="blacklist">{$blacklist}</textarea></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="3" align="center"><input type="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<div align="center" class="gensmall"><br>Reputation Mod for ExBB FM {$fm->exbb['version']} by 
			<a href="http://www.exbb.org/">yura3d</a></div>
DATA;
