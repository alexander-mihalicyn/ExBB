<?php
echo <<<DATA
<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/>&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; {$fm->LANG['UserInfo']}
			</div>
			<br>
			<table class="tableborder" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['UserInfo']} <b>{$user['name']}</b></td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['Avatar']}</b></td>
					<td class="pformright" align="center"style="padding:5px;">{$avatar}</td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['RegedDate']}</b></td>
					<td class="pformright">{$user['joined']}</td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['Status']}</b></td>
					<td class="pformright">{$user['title']}{$moders_ban}</td>
				</tr>
					$show_birstday
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['Updates']}</b></td>
					<td class="pformright">$lastpostdetails</td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['UserTotalPosts']}</b></td>
					<td class="pformright">{$user['posts']} [$percentage / $posts_per_day]</td>
				</tr>
				$show_belong
				<tr>
   					<td class="pformleft" valign="top"><b>{$fm->LANG['YouEmail']}</b></td>
   					<td class="pformright">$emailaddress</td>
  				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['WWW']}</b></td>
					<td class="pformright">{$homepage}&nbsp;</td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['ICQ']}</b></td>
					<td class="pformright">$icqlogo&nbsp;{$user['icq']} </td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['AOL']}</b></td>
					<td class="pformright">{$user['aim']}&nbsp;</td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['From']}</b></td>
					<td class="pformright">{$user['location']}&nbsp;</td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['Interests']}</b></td>
					<td class="pformright">{$user['interests']}&nbsp;</td>
				</tr>
				<tr>
					<td class="pformleft" valign="top"><b>{$fm->LANG['PM']}</b></td>
					<td class="pformright"><a href="messenger.php?action=new&touser={$user['id']}" target="_blank" title="{$fm->LANG['SendPm']} {$user['name']}">{$fm->LANG['SendPm']} <b>{$user['name']}</b></a></td>
				</tr>
<!-- ДЕНЬ РОЖДЕНИЯ -->
{$mod_punish}
<!-- ДЕНЬ РОЖДЕНИЯ -->
				<tr>
					<td class="pformstrip" align="center" colspan="2">&nbsp;</td>
				</tr>
			</table>
			<br><br>
			<table class="tableborder" cellpadding="4" cellspacing="0" width="100%">
				<tr>
					<td class="maintitle" colspan="3"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;<b>{$fm->LANG['UserStats']}</b</td>
				</tr>
    			<tr class="pformstrip" align="center">
					<td><b>{$fm->LANG['Forum']}</b></td>
					<td><b>{$fm->LANG['Quantity']}</b></td>
					<td><b>{$fm->LANG['InProc']}</b></td>
				</tr>
{$output}
				<tr class="pformstrip" align="center">
					<td><b>{$fm->LANG['Total']}</b></td>
					<td><b>$countposts</b></td>
					<td><b>100%</b></td>
				</tr>
			</table>
DATA;
?>
