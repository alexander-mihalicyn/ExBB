<?php
echo <<<DATA
			<h1>{$fm->LANG['ConfStatistic']}</h1>
			<table width="400" cellpadding="4" cellspacing="1" border="0" class="forumline">
				<tr class="genmed">
					<th width="50%" nowrap="nowrap" height="25" class="thTop">{$fm->LANG['Statistic']}</th>
					<th width="50%" height="25" class="thCornerR">{$fm->LANG['Value']}</th>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['PostsTotal']}:</td>
					<td class="row2"><b>{$fm->_Stats['totalposts']}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['PostsPerDay']}:</td>
					<td class="row2"><b>{$posts_per_day}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['TopicsTotal']}:</td>
					<td class="row2"><b>{$fm->_Stats['totalthreads']}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['TopicsPerDay']}:</td>
					<td class="row2"><b>{$topics_per_day}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['UsersTotal']}:</td>
					<td class="row2"><b>{$fm->_Stats['totalmembers']}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['UsersPerDay']}:</td>
					<td class="row2"><b>{$users_per_day}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['BoardStarted']}:</td>
					<td class="row2"><b>{$boardstart}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['PHPVer']}:</td>
					<td class="row2"><b>{$php_ver}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['OnlineNow']}:</td>
					<td class="row2"><b>{$onlinedata}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['GzipCompression']}:</td>
					<td class="row2"><b>{$gzip}</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['UploadsSize']}:</td>
					<td class="row2"><b>{$uploads} kB</b></td>
				</tr>
				<tr class="genmed">
					<td class="row1" nowrap="nowrap">{$fm->LANG['ServerLoads']}:</td>
					<td class="row2"><b>{$server_load}</b></td>
				</tr>
			</table>
			<br />
DATA;
