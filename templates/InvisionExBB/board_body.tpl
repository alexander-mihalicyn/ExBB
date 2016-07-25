<?php
/*
	Login form
*/
$logins = ($fm->user['id'] !== 0) ? '' : <<<LOGINS
<div align="right">
	<form style="display:inline" action="loginout.php" method="post">
		<strong>{$fm->LANG['FastLogin']}</strong>
		<input type=hidden name="action" value="login">
		<input type="text" class="forminput" size="10" name="imembername" onfocus=this.value="" value="User Name"/>
		<input type="password" class="forminput" size="10" name="ipassword" onfocus=this.value="" value="ibfrules"/>
		<input type="submit" class="forminput" name="submit" value="{$fm->LANG['Login']}">
	</form>
</div>
LOGINS;

/*
Chat informer
*/
$chat_informer = '';
if ($fm->exbb['chat'])
$chat_informer = <<<DATA
<div align="center" id="chat_informer"><br/><br/></div>
<script language="JavaScript" src="modules/chat/javascript/informer.js"></script>
DATA;

/*
Board body
*/
echo <<<DATA
<br/>{$chat_informer}
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/>
				<a href="index.php" title="{$fm->exbb['boardname']}">{$fm->exbb['boardname']}</a>
			</div>
			{$fm->LANG['LastVisit']} {$lastvisit}
		</td>
		<td align="right">
			<div id="navstrip" align="right">
				<a href="tools.php?action=threadstop"
				   title="{$fm->LANG['TopicsRaiting']}">{$fm->LANG['TopicsRaiting']}</a>
			</div>
			<a href="index.php?action=resetall" title="{$fm->LANG['MarkAllForums']}">{$fm->LANG['MarkAllForums']}</a>
		</td>
	</tr>
</table>
{$news_data}
{$board_data} <br/>
{$logins}
<br/>
<table cellpadding="4" cellspacing="1" border="0" width="100%" class="tableborder">
	<tr>
		<td class="maintitle" colspan="2">{$fm->LANG['ForumStat']}</td>
	</tr>
	<tr>
		<td width="5%" class="row2" align="center" rowspan="{$rowspan}"><img src="./templates/InvisionExBB/im/user.gif"
																			 border="0" alt="Active Users"/></td>
		<td class="row4" width="95%">{$online_last}
			<br>
			{$fm->_MembersOutput}
			<br>
			<a href="tools.php?action=online" title="{$fm->LANG['ViewFullList']}">{$fm->LANG['WhoOnline']}</a>
		</td>
	</tr>
DATA;
	if ($todayvisit) {
	echo <<<DATA
	<tr>
		<td class="row4">{$todayvisit}</td>
	</tr>
DATA;
	}
	echo <<<DATA
	<!-- ÒÎÏ-ËÈÑÒ ÏÎËÜÇÎÂÀÒÅËÅÉ -->
	{$userstop}
	<!-- ÒÎÏ-ËÈÑÒ ÏÎËÜÇÎÂÀÒÅËÅÉ -->
	<!-- ÄÅÍÜ ÐÎÆÄÅÍÈß -->
	{$birstdaylist}
	<!-- ÄÅÍÜ ÐÎÆÄÅÍÈß -->
	<tr>
		<td class="row2" width="5%" align="center" valign="middle"><img src="./templates/InvisionExBB/im/stats.gif"
																		border="0" alt="Board Stats"/></td>
		<td class="row4" width="95%" align="left">
			{$fm->LANG['NewUser']} <a href="profile.php?action=show&member={$fm->_Stats['last_id']}"
									  title="{$fm->_Stats['lastreg']}">{$fm->_Stats['lastreg']}</a>
			<br/>
			{$fm->LANG['UsersTotal']} <b>{$fm->_Stats['totalmembers']}</b>
			<br/>
			{$fm->LANG['PostsTotal']} <b>{$fm->_Stats['totalposts']}</b>
			<br/>
			{$fm->LANG['TopicsTotal']}: <b>{$fm->_Stats['totalthreads']}</b>
			<br/>{$maximum}
		</td>
	</tr>
</table>
<br/>
<div align="center">
DATA;
	if ($fm->exbb['rss'] === TRUE) {
	echo <<<DATA
	<a href="tools.php?action=rss" target="_blank" title="RSS êàíàë ôîðóìà {$GLOBALS['fm']->exbb['boardname']}"><img
				src="./im/images/rss20.gif" width="80" height="15" alt="RSS" border="0"></a>
DATA;
	}
	echo <<<DATA
</div>
DATA;
?>
