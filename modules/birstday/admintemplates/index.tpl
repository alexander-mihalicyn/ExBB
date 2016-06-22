<?php
echo <<<DATA
			<h1>{$fm->LANG['ModuleTitle']}</h1>
			<form action="setmodule.php" method="post">
				<input type=hidden name="dosave" value="yes">
				<input type=hidden name="module" value="birstday">
					<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
        			<tr>
          				<th class="thHead" width="70%">{$fm->LANG['Variable']}</th>
          				<th class="thHead">{$fm->LANG['VariableValue']}</th>
       	 			</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['BirstPM']}<br /><span class="gensmall">{$fm->LANG['BirstPMDesc']}</span></td>
						<td class="row2"><input type="radio" name="birst_pm" value="yes" {$birst_pm_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="birst_pm" value="no" {$birst_pm_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['BirstEmail']}<br /><span class="gensmall">{$fm->LANG['BirstEmailDesc']}</span></td>
						<td class="row2"><input type="radio" name="birst_em" value="yes" {$birst_em_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="birst_em" value="no" {$birst_em_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr>
						<td class="catBottom" colspan="3" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
?>
