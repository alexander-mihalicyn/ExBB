<?php
echo <<<DATA

			<h1>{$fm->LANG['ModuleTitle']}</h1>
			<form action="setmodule.php" method="post">
				<input type="hidden" name="module" value="watches" />
				<input type="hidden" name="doSend" value="yes" />
				<table width="65%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['WatchesConfig']}</th>
					</tr>
					<tr class="gen">
						<td width="350" class="row1" align="right">{$fm->LANG['WatchesDays']}</td>
						<td class="row2"><input type="text" name="days" class="post" size="4" maxlength="4" value="{$days}" /> 
							{$fm->LANG['WatchesDaysAgo']}</td>
					</tr>
					<tr>
						<td class="catBottom" colspan="5" align="center"><input type="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<div class="gensmall" align="center"><br />Watches Mod for ExBB FM  {$fm->exbb['version']} by 
			<a href="http://www.exbb.org/">yura3d</a></div>
			
DATA;
