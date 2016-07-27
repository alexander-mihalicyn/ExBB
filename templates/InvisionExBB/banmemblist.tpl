<br/>
<div id="navstrip" align="left">
	<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/>&nbsp;<a
			href="index.php"><?php echo $fm->exbb['boardname']; ?></a>&nbsp;&raquo;&nbsp;<a
			href="tools.php?action=banmembers"><?php echo $fm->LANG['BanMemberlist']; ?></a>
</div>
<table width="100%" cellpadding="0" cellspacing="1" class="tableborder">
	<tr>
		<td class="maintitle" colspan="8"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;"
											   width="8" height="8"/>&nbsp;<?php echo $fm->LANG['BanMemberlist']; ?></td>
	</tr>
	<tr class="postlinksbar" align="center">
		<td width="15%" height="29"><?php echo $fm->LANG['BanUser']; ?></td>
		<td width="33%"><?php echo $fm->LANG['BanReason']; ?></td>
		<td width="15%"><?php echo $fm->LANG['BanDate']; ?></td>
		<td width="2%"><?php echo $fm->LANG['BanDays']; ?></td>
		<td width="15%"><?php echo $fm->LANG['BanDateEnd']; ?></td>
		<td width="15%"><?php echo $fm->LANG['BanWho']; ?></td>
		<td width="15%"><?php echo $fm->LANG['BanWhoUnban']; ?></td>
	</tr>
	<?php echo $banmembers_data; ?>
	<tr>
		<td class="activeuserstrip" align="center" colspan="8">&nbsp;</td>
	</tr>
</table>