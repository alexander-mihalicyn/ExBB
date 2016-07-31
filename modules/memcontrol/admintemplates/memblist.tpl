<?php
echo <<<MEMBERS
<script language="JavaScript" type="text/JavaScript">
<!--
function ChekUncheck() {
	var i;
	for (i = 0; i < document.memberlist.elements.length; i++) {
		if (document.memberlist.chek.checked==true){
			document.memberlist.elements[i].checked = true;
		} else {
				document.memberlist.elements[i].checked = false;
		}
	}
}
//-->
</script>
			<H1>{$fm->LANG['Memberlist']}</H1>
			<form method="post" action="setmodule.php?module=memcontrol">
				<table width="100%" cellspacing="2" cellpadding="2" border="0" align="center">
					<tr>
						<td align="left" colspan="2"><span class="nav">{$pages}</span></td>
					</tr>
					<tr>
						<td align="left"><span class="genmed">{$fm->LANG['PrintBy']} <input name="pg" type="text" value="{$per_page}" size="2">{$fm->LANG['UsersByList']}</span></td>
						<td align="right" nowrap="nowrap">
							<span class="genmed">{$fm->LANG['SortBy']}&nbsp;
								<select name="s" class="dats">
									<option value="d"{$d_selected}>{$fm->LANG['SortByJoin']}</option>
									<option value="p"{$p_selected}>{$fm->LANG['SortByPost']}</option>
									<option value="n"{$n_selected}>{$fm->LANG['SortByName']}</option>
								</select>&nbsp;&nbsp;
								<select name="order" class="dats">
									<option value="ASC"{$ASC_selcted}>{$fm->LANG['SortASC']}</option>
									<option value="DESC"{$DESC_selcted}>{$fm->LANG['SortDESC']}</option>
								</select>&nbsp;&nbsp;
								<input type="submit" name="submit" value="{$fm->LANG['Sorting']}" class="liteoption" />
							</span>
						</td>
					</tr>
				</table>
			</form>
			<form method="post" name="memberlist" action="setmodule.php?module=memcontrol&action=deletemember">
			<input type="hidden" name="s" value="{$sort}">
			<input type="hidden" name="order" value="{$order}">
			<input type="hidden" name="p" value="{$fm->input['p']}">
			<input type="hidden" name="pg" value="{$per_page}">
				<table width="100%" cellpadding="3" cellspacing="1" border="0" class="forumline">
					<tr>
						<th class="thHead" colspan="7" height="25" nowrap="nowrap">{$fm->LANG['Memberlist']}</th>
					</tr>
					<tr>
						<th height="25" class="thCornerL" nowrap="nowrap" width="16%">{$fm->LANG['Name']}</th>
						<th class="thTop" nowrap="nowrap" width="16%">{$fm->LANG['Status']}</th>
						<th class="thTop" nowrap="nowrap" width="12%">Email</th>
						<th class="thTop" nowrap="nowrap" width="16%">{$fm->LANG['From']}</th>
						<th class="thTop" nowrap="nowrap" width="16%">{$fm->LANG['RegedDate']}</th>
						<th class="thTop" nowrap="nowrap" width="10%">{$fm->LANG['PostsTotal']}</th>
						<th class="thCornerR" nowrap="nowrap"width="4%"><input name="chek" type="checkbox" value="" onClick="ChekUncheck()"></th>
					</tr>
					{$memb_data}
					<tr>
						<td class="catbottom" colspan="7" height="28" align="right"><input name="mode" type="submit" value="{$fm->LANG['DelNotify']}">&nbsp;<input name="delete" type="submit" value="{$fm->LANG['Delete']}">&nbsp;</td>
					</tr>
				</table>
			</form>
			<form method="post" action="setmodule.php?module=memcontrol">
				<table width="100%" cellspacing="0" cellpadding="0" border="0">
					<tr>
						<td><span class="nav">{$pages}</span></td>
						<td align="right" nowrap="nowrap">
							<span class="genmed">{$fm->LANG['SortBy']}&nbsp;
								<select name="s" class="dats">
									<option value="d"{$d_selected}>{$fm->LANG['SortByJoin']}</option>
									<option value="p"{$p_selected}>{$fm->LANG['SortByPost']}</option>
									<option value="n"{$n_selected}>{$fm->LANG['SortByName']}</option>
								</select>&nbsp;&nbsp;
								<select name="order" class="dats">
									<option value="ASC"{$ASC_selcted}>{$fm->LANG['SortASC']}</option>
									<option value="DESC"{$DESC_selcted}>{$fm->LANG['SortDESC']}</option>
								</select>&nbsp;&nbsp;
								<input type="submit" name="submit" value="{$fm->LANG['Sorting']}" class="liteoption" />
							</span>
						</td>
					</tr>
				</table>
			</form>
MEMBERS;
