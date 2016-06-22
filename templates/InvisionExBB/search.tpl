<?php
echo <<<DATA
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a>&nbsp;&raquo;&nbsp;{$fm->LANG['Search']}
			</div>
			<br>
			<form action="search.php" method="POST">
				<input type=hidden name="action" value="start">
				<table class="tableborder" cellpadding="4" cellspacing="1" border="0" width="100%">
					<tr>
						<th colspan="2" class="maintitle" align="left"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['QUERY']}</th>
					</tr>
					<tr>
						<td width="30%" align="right" class="tdrow1" valign="top">{$fm->LANG['KEYWORDS']}</td>
						<td class="tdrow1">
							<input type="text" style="width: 300px" name="search_keywords" size="30" tabindex="1" />
							<input type="radio" name="stype" tabindex="2" value="AND" title="{$fm->LANG['SearchParamsAND']}" checked>"AND"
							<input type="radio" name="stype" value="OR" title="{$fm->LANG['SearchParamsOR']}">"OR"
						</td>
					</tr>
					<tr>
						<td width="30%" align="right" class="tdrow1" valign="top">{$fm->LANG['SEARCHIN']}</td>
						<td class="tdrow1">
							<select name="src_in">
								{$forums}
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" class="tdrow1" valign="top">{$fm->LANG['KEYWORDSDESC']}</td>
					</tr>
					<tr>
        				<td colspan="2" align="center" height="33" class="darkrow2"><input type="submit" value="{$fm->LANG['StartSearch']}" /></td>
    				</tr>
					<tr>
						<td colspan="2" class="row4" align="center" valign="middle" height="25"><span class="copyright">Search engine: Powered by <A HREF="http://risearch.org/" class="copyright" target=_blank><b>RiSearch PHP</b></A>, &copy; 2002</span></td>
    				</tr>
				</table>
			</form>
DATA;
?>
