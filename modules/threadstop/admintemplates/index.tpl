<?php
echo <<<DATA
			<h1>{$fm->LANG['ModuleTitle']}</h1>
			<form action="setmodule.php" method="post">
				<input type=hidden name="dosave" value="yes">
				<input type=hidden name="module" value="threadstop">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
        			<tr>
          				<th class="thHead" width="70%">{$fm->LANG['Variable']}</th>
          				<th class="thHead">{$fm->LANG['VariableValue']}</th>
       	 			</tr>
        			<tr class="gen">
						<td class="row1">{$fm->LANG['Threadsnum']}</td>
						<td class="row2"><input class="post" type="text" size="8" maxlength="3" name="threadsnum" value="{$threadsnum}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['PrintValue']}<br /><span class="gensmall">{$fm->LANG['PrintValueDesc']}</span></td>
						<td class="row2"><input type="radio" name="printval" value="yes" {$printval_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="printval" value="no" {$printval_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr>
						<td class="catBottom" colspan="3" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
