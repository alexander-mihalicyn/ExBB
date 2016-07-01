<?php
echo <<<DATA
			<br />
<div id="navstrip" align="left">
	<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;"/> <a
			href="index.php">{$fm->exbb['boardname']}</a> &raquo; {$fm->LANG['Registration']}
</div>
<br/>
<form action="register.php?{$sesid}" method="post">
	<input name="action" type="hidden" value="agreed">
	<table cellpadding="6" cellspacing="1" border="0" width="100%" align="center" class="tableborder">
		<tr>
			<td class="maintitle" align="center" height="29"><b>{$fm->LANG['AgrTerms']}</b></td>
		</tr>
		<tr>
			<td class="tablepad">{$fm->LANG['RegAgreement']}</td>
		</tr>
		<tr>
			<td align="center" height="29" class="darkrow2"><input type="submit" value="{$fm->LANG['IAgreed']}"></td>
		</tr>
	</table>
</form>
DATA;
?>
