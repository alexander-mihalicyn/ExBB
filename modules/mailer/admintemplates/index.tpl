<?php
echo <<<DATA

			<h1>{$fm->LANG['ModuleTitle']}</h1>
			<form action="setmodule.php" method="post">
			<input type="hidden" name="module" value="mailer" />
			<input type="hidden" name="doSend" value="yes" />
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" width="70%">{$fm->LANG['Variable']}</th>
						<th class="thHead">{$fm->LANG['VariableValue']}</th>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['MailerPeriod']}<br /><span class="gensmall">{$fm->LANG['MailerPeriodDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="2" maxlength="2" name="days" value="{$days}" /> {$fm->LANG['MailerDays']}
							<input class="post" type="text" size="2" maxlength="2" name="hours" value="{$hours}" /> {$fm->LANG['MailerHours']}
							<input class="post" type="text" size="2" maxlength="2" name="minutes" value="{$minutes}" /> {$fm->LANG['MailerMinutes']}
							<input class="post" type="text" size="2" maxlength="2" name="seconds" value="{$seconds}" /> {$fm->LANG['MailerSeconds']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['MailerMessages']}<br /><span class="gensmall">{$fm->LANG['MailerMessagesDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="7" maxlength="7" name="messages" value="{$messages}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['MailerProcess']}<br /><span class="gensmall">{$fm->LANG['MailerProcessDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="7" maxlength="7" name="process" value="{$process}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['MailerReserved']}<br /><span class="gensmall">{$fm->LANG['MailerReservedDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="7" maxlength="7" name="reserved" value="{$reserved}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['MailerCron']}<br /><span class="gensmall">{$fm->LANG['MailerCronDesc']}</span></td>
						<td class="row2"><input type="radio" name="cron" value="yes" {$cron_yes}/> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="cron" value="no" {$cron_no}/> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['MailerStats']}<br /><span class="gensmall">{$fm->LANG['MailerStatsDesc']}</span></td>
						<td class="row2" valign="top">{$last}<br />{$sent} | {$through}<br />{$wSent} | {$wThrough}</td>
					</tr>
					<tr>
						<td class="catBottom" colspan="3" align="center"><input type="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<div align="center" class="gensmall"><br>Advanced Mailer for ExBB FM {$fm->exbb['version']} by 
			<a href="http://www.exbb.org/">yura3d</a></div>

DATA;
?>