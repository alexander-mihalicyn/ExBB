<?php
echo <<<DATA
			<table width="290" align="center" cellpadding="4" cellspacing="1" border="0" class="tableborder">
				<tr>
					<td class="maintitle" colspan="3" height="25" nowrap="nowrap"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['AllSmiles']}</td>
				</tr>
				<tr>
					<td height="25" class="titlemedium" nowrap="nowrap" align="center" colspan="3">
						<form name="smileselect" action="tools.php?action=smiles" method="POST">
							<select name="cat" ONCHANGE="document.smileselect.submit()">
								{$smoption}
							</select><noscript><input type="submit" value="Go"></noscript>
  						</form>
					</td>
				</tr>
				{$datashow}
				<tr>
					<td class="darkrow2" colspan="3">&nbsp;</td>
				</tr>
			</table>
			<br>
			<div align="center">{$pages}</div>
DATA;
?>
