<?php
$logins = ($fm->user['id'] !== 0) ? '' : <<<LOGINS
<table width="100%" cellpadding="0" cellspacing="1" class="tableborder">
<tr>
<td class="maintitle"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;Вход на сайт</td>
</tr>
<tr>
<td align="center">
<br />
<form style="display:inline" action="loginout.php" method="post">
<input type=hidden name="action" value="login">
<b>Логин : </b>&nbsp;&nbsp;<input type="text" class="forminput" size="30" name="imembername" onfocus=this.value="" value="" /><br /><br />
<b>Пароль : </b><input type="password" class="forminput" size="30" name="ipassword" onfocus=this.value="" value="" /><br /><br />
<input type="submit" class="forminput" name="submit" value="{$fm->LANG['Login']}">
</form>
<br /><br />
<b><a href="profile.php?action=lostpassword" title="{$fm->LANG['ForgottenPass']}">{$fm->LANG['ForgottenPass']}</a><br /><br />
Еще не зарегистрирован? <a href="register.php" title="{$fm->LANG['Registration']}">Зарегистрируйся!</a></b>
<br /><br />
</td>
</tr>
<tr>
<td class="darkrow2" colspan="2">&nbsp;</td>
</tr>
</table>
LOGINS;

$unread			= ($fm->user['unread'] === 0) ? '':' ( '.$fm->user['unread'].' )';

$admincenter	= (defined('IS_ADMIN')) ? '
&nbsp;&middot; <a href="announcements.php" title="'.$fm->LANG['Announ'].'">'.$fm->LANG['Announ'].'</a>
&nbsp;&middot; <a href="admincenter.php" title="'.$fm->LANG['Admincenter'].'"  target="_blank"><font color=red>'.$fm->LANG['Admincenter'].'</font></a>
'
:
'';

$loginout		= ($fm->user['id'] !== 0) ? '
( <a href="loginout.php?action=logout" title="'.$fm->LANG['Exit'].'">'.$fm->LANG['Exit'].'</a>'.$admincenter .'
&nbsp;&middot; <a href="tools.php?action=rules" title="'.$fm->LANG['ForumRules'].'"><font color="red">'.$fm->LANG['ForumRules'].'</font></a> )
'
:
'( <a href="#win1">Вход</a><a href="#x" class="overlay" id="win1"></a><span class="popup">'.$logins.'<a class="close" title="Закрыть" href="#close"></a></span> 
&middot; <a href="register.php" title="'.$fm->LANG['Registration'].'">'.$fm->LANG['Registration'].'</a>
&nbsp;&middot; <a href="tools.php?action=rules" title="'.$fm->LANG['ForumRules'].'"><font color="red">'.$fm->LANG['ForumRules'].'</font></a>  )
';

$loginact		= ($fm->user['id'] !== 0) ? '
<b><a href="profile.php" title="'.$fm->LANG['YourProfile'].'">'.$fm->LANG['YourProfile'].'</a></b>
&nbsp;&middot; <a href="messenger.php" target="_blank" title="'.$fm->LANG['PMTitle'].'">'.$fm->LANG['PM'].''.$unread.'</a>
&nbsp;&middot; <a href="search.php?action=newposts" title="'.$fm->LANG['NewPosts'].'">'.$fm->LANG['NewPosts'].'</a>
'
:
' <a href="profile.php?action=lostpassword" title="'.$fm->LANG['ForgottenPass'].'">'.$fm->LANG['ForgottenPass'].'</a> 
';

$chat = (isset($fm->exbb['chat']) && $fm->exbb['chat']===TRUE) ? '
<img src="./templates/InvisionExBB/im/chat.png" border="0" alt="" />&nbsp;<a href="tools.php?action=chat" title="'.$fm->LANG['Chat'].'"><b>'.$fm->LANG['Chat'].'</b></a>&nbsp; &nbsp;&nbsp;</a>'
:
'';

echo <<<DATA
{$GLOBALS['fm']->_NewEmail}
			<table width="100%" id='logostrip' cellspacing="0" cellpadding="0">
				<tr>
					<td><a href="index.php" title="{$fm->exbb['boardname']}"><img src="./templates/InvisionExBB/im/logo.gif" alt="{$fm->exbb['boardname']}" width="207" height="52" border="0" /></a></td>
					<td valign="bottom" align="right"><a href="index.php" style="margin-right:20px;color:#ffffff">{$fm->exbb['boardname']}</a></td>
				</tr>
			</table>
			<table width="100%" cellspacing="6" id="logomenu" border="0">
				<tr>
					<td align="left">{$fm->_Banner}</td>
					<td valign="top" align="right"><span style="margin-right:15px;">{$fm->exbb['boarddesc']}</span></td>
				</tr>
				<tr>
					<td align="left"></td>
					<td align="right" valign="bottom" nowrap>
						{$chat}
						<img src="./templates/InvisionExBB/im/help.gif" border="0" alt="" />&nbsp;<a href="tools.php?action=help" title="{$fm->LANG['Help']}"><b>{$fm->LANG['Help']}</b></a>&nbsp; &nbsp;&nbsp;
						<img src="./templates/InvisionExBB/im/search.gif" border="0" alt="" />&nbsp;<a href="search.php" title="{$fm->LANG['Search']}"><b>{$fm->LANG['Search']}</b></a>&nbsp; &nbsp;&nbsp;
						<img src="./templates/InvisionExBB/im/members.gif" border="0" alt="" />&nbsp;<a href="tools.php?action=members" title="{$fm->LANG['Users']}"><b>{$fm->LANG['Users']}</b></a>&nbsp;&nbsp;&nbsp;
						<img src="./templates/InvisionExBB/im/banmembers.gif" border="0" alt="" />&nbsp;<a href="tools.php?action=banmembers" title="{$fm->LANG['BanUsers']}">&nbsp;<b>{$fm->LANG['BanUsers']}</b></a>
					</td>
				</tr>
			</table>
			<table width="100%" id="userlinks" cellspacing="6">
				<tr>
					<td>{$fm->LANG['Hello']} {$fm->user['name']} {$loginout}</td>
					<td align='right'>$loginact</td>
				</tr>
			</table>
DATA;
?>
