<?php
echo <<<DATA

			<h1>{$fm->LANG['ModuleTitle']}</h1>
			<form action="setmodule.php?module=awards" method="post" enctype="multipart/form-data">
				<input type="hidden" name="module" value="ads" />
				<input type="hidden" name="doSend" value="yes" />
				<table width=70%" cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">
					<tr>
						<th class="thHead" colspan="2">{$fm->LANG['AdsBlockSettings']}</th>
					</tr>
					<tr class="gen">
						<td width="380" class="row1" align="right">{$fm->LANG['AdsOnlyForGuests']}</td>
						<td class="row2"><input type="radio" name="onlyForGuests" id="onlyForGuests_yes"
							value="yes"{$onlyForGuests_yes} /><label for="onlyForGuests_yes"> {$fm->LANG['Yes']}</label>&nbsp; 
							<input type="radio" name="onlyForGuests" id="onlyForGuests_no"
							value="no"{$onlyForGuests_no} /><label for="onlyForGuests_no"> {$fm->LANG['No']}</label></td>
					</tr>
					<tr class="gen">
						<td class="row1" align="right">{$fm->LANG['AdsNeedPosts']}</td>
						<td class="row2"><input type="text" name="needPosts" class="post" size="4" maxlength="4" value="{$needPosts}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1" align="right">{$fm->LANG['AdsAdminsSupmoders']}</td>
						<td class="row2"><input type="radio" name="adminsSupmoders" id="adminsSupmoders_yes"
							value="yes"{$adminsSupmoders_yes} /><label for="adminsSupmoders_yes"> {$fm->LANG['Yes']}</label>&nbsp; 
							<input type="radio" name="adminsSupmoders" id="adminsSupmoders_no"
							value="no"{$adminsSupmoders_no} /><label for="adminsSupmoders_no"> {$fm->LANG['No']}</label></td>
					</tr>
					<tr class="gen">
						<td class="row1" align="right">{$fm->LANG['AdsAfterPost']}</td>
						<td class="row2"><input type="text" name="afterPost" class="post" size="4" maxlength="3" value="{$afterPost}" /></td>
					</tr>
					<tr class="gen">
						<td class="row1" align="right" valign="top">{$fm->LANG['AdsSourceCode']}</td>
						<td class="row2"><div class="gensmall" style="margin-bottom: 3px">{$fm->LANG['AdsSourceCodeDesc']}</div>
							<textarea name="sourceCode" class="post" style="width: 100%;" rows="4">{$sourceCode}</textarea></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="5" align="center"><input type="submit" value="{$fm->LANG['AdsSaveChanges']}"
							class="mainoption" /></td>
					</tr>
				</table>
			</form>
			<div class="gensmall" align="center"><br />Ads Mod for ExBB FM  {$fm->exbb['version']} by 
			<a href="http://www.exbb.org/">yura3d</a></div>

DATA;
?>