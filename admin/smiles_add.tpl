<?php
echo <<<DATA
<script language="javascript" type="text/javascript">
<!--
function show_smiley(newimage){
        document.smiley_image.src = "{$smilesdir}/" + newimage;
        document.smile.sm_code.value = "::" + newimage + "::";
}
//-->
</script>
			<h1>{$fm->LANG['AdminSmiles']}</h1>
			<form method="post" name="smile" action="setsmiles.php">
				{$hidden_field}
				<table class="forumline" cellspacing="1" cellpadding="4" border="0" align="center">
					<tr>
						<td class="catHead" colspan="3" align="center">{$tabletitle}</td>
					</tr>
					<tr>
						<th class="thCornerL">{$fm->LANG['SmCode']}</th>
						<th class="thTop">{$fm->LANG['SmileFile']}</th>
						<th class="thCornerR">{$fm->LANG['SmileDesc']}</th>
					</tr>
					<tr class="gen">
						<td class="row2" valign="middle"><input class="post" type="text" name="sm_code" value="{$code}" /></td>
						<td class="row2" valign="middle" nowrap> <img name="smiley_image" src="{$smilesdir}/{$cur_smile}" border="0" alt="" align="middle" /> &nbsp;{$selectsmile}&nbsp;</td>
						<td class="row2" valign="middle"><input class="post" type="text" name="sm_emotion" value="{$sm_emt}" /></td>
					</tr>
					<tr>
						<td class="catBottom" colspan="3" align="center"><input class="mainoption" type="submit" name="SaveSmile" value="{$fm->LANG['Save']}" /></td>
					</tr>
				</table>
			</form>
			<br clear="all" />
DATA;
