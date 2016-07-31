<?php
echo <<<DATA
			<h1>{$fm->LANG['ModuleTitle']}</h1>
			<form action="setmodule.php" method="post">
			<input type="hidden" name="module" value="chat">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
        			<tr>
          				<th class="thHead" width="70%">{$fm->LANG['Variable']}</th>
          				<th class="thHead">{$fm->LANG['VariableValue']}</th>
       	 			</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ChatHeight']}</td>
						<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="height" value="{$height}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ChatUpdate']}</td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="update" value="{$update}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ChatScroll']}</td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="3" name="scroll" value="{$scroll}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['ChatHistory']}</td>
						<td class="row2"><input class="post" type="text" size="4" maxlength="4" name="history" value="{$history}" /></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="3" align="center"><input type="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<div align="center" class="gensmall"><br>Chat for ExBB FM {$fm->exbb['version']} by 
			<a href="http://www.exbb.org/">yura3d</a></div>
DATA;
