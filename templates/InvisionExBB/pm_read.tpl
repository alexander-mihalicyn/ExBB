<?php
echo <<<DATA
<SCRIPT LANGUAGE="javascript">
<!--
function conf() {
	if (confirm('{$fm->LANG['DelConfirm']} {$SenderName}?') ){
		parent.location='messenger.php?action=deletemsg&where=inbox&msg[]={$message_id}';
	}
}
//-->
</SCRIPT>
			<br />
			<table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
				<tr>
					<td class="maintitle">
						<img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['InboxTitle']}
					</td>
				</tr>
				<tr>
					<td valign="middle" align="center" class="tablepad">
						<a href="messenger.php?action=inbox"><img src="{$InBoxIcon}" width="108" height="30" border="0"></a> &nbsp; &nbsp; &nbsp;
						<a href="messenger.php?action=outbox"><img src="{$OutBoxIcon}" width="115" height="30" border="0"></a> &nbsp; &nbsp; &nbsp;
						<a href="messenger.php?action=new"><img src="{$NewPMIcon}" width="94" height="30" border="0"></a>
						<br>
						<br>
					</td>
				</tr>
				<tr>
					<td class="titlemedium" align="center" valign="middle" height="29">{$fm->LANG['MessageFrom']}</td>
				</tr>
				<tr>
					<td class="pformstrip">
						<img src="./templates/InvisionExBB/im/nav_pm.gif" border="0"  alt="&gt;"/>&nbsp;{$fm->LANG['MessageTitle']}: {$MessageTitle}
					</td>
				</tr>
				<tr>
					<td align="left" class="tablepad">{$MessageText}</td>
				</tr>
				<tr>
					<td class="pformstrip" align="center" colspan="4">&laquo; <a href="javascript:conf()">{$fm->LANG['Delete']}</a> | <a href="messenger.php?action=reply&msg={$message_id}">{$fm->LANG['Reply']}</a>  | <a href="messenger.php?action=replyquote&msg={$message_id}" title="{$fm->LANG['ReplyQuote']}">{$fm->LANG['ReplyQuote']}</a> | <a href="profile.php?action=show&member={$sender_id}" title="{$fm->LANG['UserProfile']} {$SenderName}">{$fm->LANG['Profile']}</a> $yes_email &raquo;</td>
				</tr>
				<tr>
					<td class="activeuserstrip" align=center  colspan="4">&nbsp;</td>
				</tr>
			</table>
DATA;
?>
