<?php
echo <<<DATA
			<h1>{$fm->LANG['ModuleConfig']}</h1>
			<form action="setvariables.php" method="post">
				<input type="hidden" name="save" value="yes">
				<input type="hidden" name="action" value="module">
				<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" width="70%">{$fm->LANG['ModuleName']}</th>
						<th class="thHead" colspan="2">{$fm->LANG['ModuleActive']}/{$fm->LANG['EditModul']}</th>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['MailerMod']}</b><br /><span class="gensmall">{$fm->LANG['MailerDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][mailer]" value="yes" {$mailer_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][mailer]" value="no" {$mailer_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=mailer">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['WatchesMod']}</b><br /><span class="gensmall">{$fm->LANG['WatchesModDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][watches]" value="yes" {$watches_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][watches]" value="no" {$watches_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=watches">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['UsersBirsydayMod']}</b></td>
						<td class="row2"><input type="radio" name="new_exbb[b][birstday]" value="yes" {$birstday_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][birstday]" value="no" {$birstday_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=birstday">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['TreadsTopMod']}</b><br /><span class="gensmall">{$fm->LANG['TreadsTopDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][threadstop]" value="yes" {$threadstop_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][threadstop]" value="no" {$threadstop_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=threadstop">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['ReputationMod']}</b><br /><span class="gensmall">{$fm->LANG['ReputationDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][reputation]" value="yes" {$reputation_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][reputation]" value="no" {$reputation_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=reputation">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['KarmaMod']}</b><br /><span class="gensmall">{$fm->LANG['KarmaDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][karma]" value="yes" {$karma_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][karma]" value="no" {$karma_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['PunishMod']}</b><br /><span class="gensmall">{$fm->LANG['PunishDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][punish]" value="yes" {$punish_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][punish]" value="no" {$punish_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=punish">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['UsersTopMod']}</b><br /><span class="gensmall">{$fm->LANG['UsersTopDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][userstop]" value="yes" {$userstop_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][userstop]" value="no" {$userstop_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=userstop">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['NewUserGreatingsMod']}</b><br /><span class="gensmall">{$fm->LANG['NewUserGreatingsDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][newusergreatings]" value="yes" {$newusergreatings_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][newusergreatings]" value="no" {$newusergreatings_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['PMNewMesMod']}</b><br /><span class="gensmall">{$fm->LANG['PMNewMesDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][pmnewmes]" value="yes" {$newpmnewmes_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][pmnewmes]" value="no" {$newpmnewmes_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['UserStatusMod']}</b><br /><span class="gensmall">{$fm->LANG['UserStatusDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][showuseronline]" value="yes" {$newshowuseronline_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][showuseronline]" value="no" {$newshowuseronline_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['StatVisitMod']}</b><br /><span class="gensmall">{$fm->LANG['StatVisitDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][statvisit]" value="yes" {$statvisit_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][statvisit]" value="no" {$statvisit_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=statvisit">{$fm->LANG['EditModul']}</a></td>
					</tr>
                    <tr class="gen">
                        <td class="row1"><b>{$fm->LANG['BelongMod']}</b><br /><span class="gensmall">{$fm->LANG['BelongModDesc']}</span></td>
                        <td class="row2"><input type="radio" name="new_exbb[b][belong]" value="yes" {$belong_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][belong]" value="no" {$belong_no} /> {$fm->LANG['No']}</td>
                        <td class="row2"><a href="setmodule.php?module=belong">{$fm->LANG['EditModul']}</a></td>
                    </tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['ImgPreviewMod']}</b><br /><span class="gensmall">{$fm->LANG['ImgPreviewModDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][imgpreview]" value="yes" {$newimgpreview_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][imgpreview]" value="no" {$newimgpreview_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['UserHideMode']}</b><br /><span class="gensmall">{$fm->LANG['UserHideModeDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][visiblemode]" value="yes" {$newvisiblemode_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][visiblemode]" value="no" {$newvisiblemode_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['BadPostReport']}</b><br /><span class="gensmall">{$fm->LANG['BadPostReportDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][preport]" value="yes" {$preport_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][preport]" value="no" {$preport_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['RSSFeed']}</b><br /><span class="gensmall">{$fm->LANG['RSSFeedDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][rss]" value="yes" {$rss_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][rss]" value="no" {$rss_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['AdsMod']}</b><br /><span class="gensmall">{$fm->LANG['AdsModDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][ads]" value="yes" {$ads_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][ads]" value="no" {$ads_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=ads">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['SponsorMod']}</b><br /><span class="gensmall">{$fm->LANG['SponsorModDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][sponsor]" value="yes" {$sponsor_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][sponsor]" value="no" {$sponsor_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['RedirectMod']}</b><br /><span class="gensmall">{$fm->LANG['RedirectModDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][redirect]" value="yes" {$redirect_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][redirect]" value="no" {$redirect_no} /> {$fm->LANG['No']}</td>
						<td class="row2">{$fm->LANG['EditModul']}</td>
					</tr>
					<tr class="gen">
						<td class="row1"><b>{$fm->LANG['ChatMod']}</b><br /><span class="gensmall">{$fm->LANG['ChatModDesc']}</span></td>
						<td class="row2"><input type="radio" name="new_exbb[b][chat]" value="yes" {$chat_yes} /> {$fm->LANG['Yes']}&nbsp;&nbsp;<input type="radio" name="new_exbb[b][chat]" value="no" {$chat_no} /> {$fm->LANG['No']}</td>
						<td class="row2"><a href="setmodule.php?module=chat">{$fm->LANG['EditModul']}</a></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="3" align="center"><input type="submit" name="submit" value="{$fm->LANG['Save']}" class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
