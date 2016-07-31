<?php
echo <<<MEMBERS
			<br/>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;<a href="tools.php?action=members">{$fm->LANG['Memberlist']}</a>
			</div>
			<br/>
			<div align="right">{$pages}<br><br>
				<form method="post" action="tools.php?action=members">
					<div style="float:left;">{$fm->LANG['PrintBy']}
						<input name="pg" type="text" value="{$per_page}" size="2">{$fm->LANG['UsersByList']}
						{$fm->LANG['SortBy']}
					</div>&nbsp;
					<select name="s" class="dats">
						<option value="d"{$d_selected}>{$fm->LANG['SortByJoin']}</option>
						<option value="p"{$p_selected}>{$fm->LANG['SortByPost']}</option>
						<option value="n"{$n_selected}>{$fm->LANG['SortByName']}</option>
					</select>&nbsp;&nbsp;
					<select name="order" class="dats">
						<option value="ASC"{$ASC_selcted}>{$fm->LANG['SortASC']}</option>
						<option value="DESC"{$DESC_selcted}>{$fm->LANG['SortDESC']}</option>
					</select>&nbsp;&nbsp;
					<input type="submit" name="submit" value="{$fm->LANG['Sorting']}">
				</form>
			</div>
			<br/>
			<table width="100%" cellpadding="0" cellspacing="1" class="tableborder">
				<tr>
					<td class="maintitle" colspan="8"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['Memberlist']}</td>
				</tr>
				<tr class="postlinksbar" align="center">
					<td width="20%" height="29">{$fm->LANG['Name']}</td>
					<td width="15%">{$fm->LANG['Status']}</td>
					<td width="15%">{$fm->LANG['PostsTotal']}</td>
					<td width="15%">{$fm->LANG['RegedDate']}</td>
					<td width="15%">{$fm->LANG['From']}</td>
					<td width="8%">E-mail</td>
					<td width="8%">WWW</td>
					<td width="4%">ICQ</td>
				</tr>
				{$members_data}
				<tr>
					<td class="activeuserstrip" align="center" colspan="8">&nbsp;</td>
				</tr>
			</table>
			<br>
			<div align="right">
				<form method="post" action="tools.php?action=members">
					<div style="float:left;">{$fm->LANG['PrintBy']}
						<input name="pg" type="text" value="{$per_page}" size="2">{$fm->LANG['UsersByList']}
						{$fm->LANG['SortBy']}
					</div>&nbsp;
					<select name="s" class="dats">
						<option value="d"{$d_selected}>{$fm->LANG['SortByJoin']}</option>
						<option value="p"{$p_selected}>{$fm->LANG['SortByPost']}</option>
						<option value="n"{$n_selected}>{$fm->LANG['SortByName']}</option>
					</select>&nbsp;&nbsp;
					<select name="order" class="dats">
						<option value="ASC"{$ASC_selcted}>{$fm->LANG['SortASC']}</option>
						<option value="DESC"{$DESC_selcted}>{$fm->LANG['SortDESC']}</option>
					</select>&nbsp;&nbsp;
					<input type="submit" name="submit" value="{$fm->LANG['Sorting']}">
				</form>
				<br><br>{$pages}
			</div>
MEMBERS;

