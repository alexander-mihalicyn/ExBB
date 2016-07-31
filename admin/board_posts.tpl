<?php
echo <<<DATA
			<h1>{$fm->LANG['PostsConfig']}</h1>
			<form action="setvariables.php" method="post">
				<input type="hidden" name="action" value="posts">
				<input type="hidden" name="save" value="yes">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['PostsConfig']}</th>
					</tr>
					<tr class="gen">
						<td class="row1"width="70%"><b>{$fm->LANG['TopicsPerPage']}</td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="new_exbb[i][topics_per_page]" value="{$fm->exbb['topics_per_page']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['PostsPerPage']}</b></td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="new_exbb[i][posts_per_page]" value="{$fm->exbb['posts_per_page']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['HotTopicPosts']}</b></td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="new_exbb[i][hot_topic]" value="{$fm->exbb['hot_topic']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['MaxPollOptions']}</b></td>
						<td class="row2"><input class="post" type="text" size="2" maxlength="3" name="new_exbb[i][max_poll]" value="{$fm->exbb['max_poll']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['SubPostTime']}</b><br /><span class="gensmall">{$fm->LANG['SubPostTimeDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="3" maxlength="4" name="new_exbb[i][sub_post]" value="{$fm->exbb['sub_post']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['EditTime']}</b><br /><span class="gensmall">{$fm->LANG['EditTimeDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="6" maxlength="6" name="new_exbb[i][edit_time]" value="{$fm->exbb['edit_time']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['UserPostTopic2Page']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][userperpage]" value="yes" {$upp_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][userperpage]" value="no" {$upp_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['ShowLocation']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][location]" value="yes" {$loc_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][location]" value="no" {$loc_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['MaxPostSize']}</b></td>
						<td class="row2"><input class="post" type="text" size="15" maxlength="8" name="new_exbb[i][max_posts]" value="{$fm->exbb['max_posts']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['MaxThreadSize']}</b><br /><span class="gensmall">{$fm->LANG['MaxThreadSizeDesc']}</span></td>
						<td class="row2"><input class="post" type="text" size="20" maxlength="12" name="new_exbb[i][max_threads]" value="{$fm->exbb['max_threads']}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['MailFromPosts']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][mail_posts]" value="yes" {$mpost_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][mail_posts]" value="no" {$mpost_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['SubMainInfo']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][sub_main_info]" value="yes" {$subinfo_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][sub_main_info]" value="no" {$subinfo_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['FirstLastHints']}</b><br /><span class="gensmall">{$fm->LANG['FirstLastHintsDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][show_hints]" value="yes" {$hints_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][show_hints]" value="no" {$hints_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['BotLightPages']}</b><br /><span class="gensmall">{$fm->LANG['BotLightPagesDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][botlight]" value="yes" {$botlight_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][botlight]" value="no" {$botlight_no} /> {$fm->LANG['No']}</td>
					</tr>
					<tr>
						<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
