<?php
echo <<<DATA
			<h1>{$fm->LANG['AdminSmiles']}</h1>
			<p class="gensmall">{$fm->LANG['AdminSmilesDesc']}</p>
			<div align="center" style="display:inline;">
				<form name="smileselect" action="setsmiles.php"  method="POST">
					{$fm->LANG['GoToCat']}
					<SELECT NAME="cat" ONCHANGE="document.smileselect.submit()">
						{$smoption}
					</SELECT>
					<noscript><input type="submit" name="GoToCat" value="Go!" class="mainoption"></noscript>
				</form>
			</div>
			<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
				<tr>
					<td class="catHead" colspan="5" align="center">
						<form method="post" name="actionselect" action="setsmiles.php">
							<input type="hidden" name="cat" value="{$curcatid}" />
							{$fm->LANG['SelectAction']}
							<SELECT NAME="action">
								<option value="addnew">{$fm->LANG['SmAddNewInCat']}</option>
								<option value="newcat">{$fm->LANG['CreateNewCat']}</option>
								<option value="addgroup">{$fm->LANG['AddTempGroup']}</option>
							</SELECT>
							<input type="submit" name="GoToCat" value="Go!" class="mainoption">
						</form>
					</td>
				</tr>
				<tr>
					<td class="catHead" colspan="5" align="center">{$fm->LANG['SmCat']} <b><u>$curcatdesc</u></b> (<a href="setsmiles.php?action=editcat&amp;cat={$curcatid}">{$fm->LANG['Change']}</a> :: <a href="setsmiles.php?action=delcat&amp;cat={$curcatid}">{$fm->LANG['Delete']}</a>)</td>
				</tr>
				<tr>
					<th class="thCornerL">{$fm->LANG['SmCode']}</th>
					<th class="thTop">{$fm->LANG['Smile']}</th>
					<th class="thTop">{$fm->LANG['SmileDesc']}</th>
					<th colspan="2" class="thCornerR">{$fm->LANG['SmWhatDo']}</th>
				</tr>
				{$datashow}
			</table>
			<br clear="all" />
DATA;
