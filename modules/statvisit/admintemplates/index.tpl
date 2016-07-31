<?php
echo <<<DATA
			<h1>{$fm->LANG['ModuleTitle']}</h1>
			<form action="setmodule.php" method="post">
			<input type="hidden" name="module" value="statvisit">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
        			<tr>
          				<th class="thHead" width="70%">{$fm->LANG['Variable']}</th>
          				<th class="thHead">{$fm->LANG['VariableValue']}</th>
       	 			</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['StatVisitForum']}<br /></td>
						<td class="row2"><input type="radio" name="forum" value="yes" {$forum_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="forum" value="no" {$forum_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['StatVisitTopic']}<br /></td>
						<td class="row2"><input type="radio" name="topic" value="yes" {$topic_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="topic" value="no" {$topic_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['StatVisitNumbers']}<br /></td>
						<td class="row2"><input type="radio" name="numbers" value="yes" {$numbers_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="numbers" value="no" {$numbers_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1">{$fm->LANG['StatVisitDay']}<br /></td>
						<td class="row2"><input type="radio" name="day" value="yes" {$day_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="day" value="no" {$day_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr>
						<td class="catBottom" colspan="3" align="center"><input type="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<div align="center" class="gensmall"><br>Advanced Visit Stats for ExBB FM {$fm->exbb['version']} by 
			<a href="http://www.exbb.org/">yura3d</a></div>
DATA;
