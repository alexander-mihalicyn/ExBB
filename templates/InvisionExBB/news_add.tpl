<?php
include('./templates/InvisionExBB/form_code.tpl');
include('./templates/InvisionExBB/smile_map.tpl');
$newsbody = <<<DATA
<script type="text/javascript" language="JavaScript" src="javascript/formcode.js"></script>
			<div id="preview" class="tableborder">
				<div class="maintitle"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;<b>{$fm->LANG['PreviewTitle']}</b></div>
				<div class="titlemedium1" id="prevtext"></div>
				<div class="darkrow2">&nbsp;</div>
			</div>
			{$PreviewData}
			<form name="NewsForm" action="announcements.php" method="post">
				<input type="hidden" name="action" value="{$action}">
				<input type="hidden" name="dosave" value="1">
				{$hidden}
				<table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
					<tr>
						<td class="maintitle" colspan="2">
							<img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$ActionTitle}
						</td>
					</tr>
					<tr>
						<td class="pformleft"><b>{$fm->LANG['NewsTitle']} : </b> <input type="text" name="title" size="60" maxlength="255" value="{$NewsTitle}"></td>
					</tr>
DATA;
if ($fm->exbb['exbbcodes'] === TRUE) {
$newsbody .= <<<DATA
					<tr>
							<td class="pformright">
								{$form_code}
							</td>
						</tr>
						<tr>
							<td class="helpstyle" valign="top" id="help" height="20">
								{$fm->LANG['HelpStyle']}
							</td>
						</tr>
DATA;
}
$newsbody .= <<<DATA
					<tr>
						<td class="pformright" valign="top">
							<textarea cols="80" rows="14" name="news" tabindex="3" class="textinput" style="width:900px;" onselect="IEOP();" onclick="IEOP();" onkeyup="IEOP();" onFocus="IEOP();" onChange="IEOP();">{$NewsText}</textarea>
							<br />{$smile_map}
						</td>
					</tr>
					<tr>
						<td class="pformleft">
							{$fm->LANG['EnableHTML']}  <input name="html" type="radio" value="yes" {$html_yes}> да <input name="html" type="radio" value="no" {$html_no}> нет
						</td>
					</tr>
					<tr>
						<td class="pformstrip" align="center" style="text-align:center" colspan="2">
							<input type="submit" value="{$fm->LANG['Send']}" name="dosend" onClick="return FormChecker(this.form)"> &nbsp;
							<input type="submit" value="{$fm->LANG['Preview']}" name="preview" onClick="Preview(this.form,'news');return false;"> &nbsp;
							<input type="button" value="{$fm->LANG['Cancel']}" onClick="javascript:history.go(-1)">
						</td>
					</tr>
				</table>
<script type="text/javascript" language="JavaScript">
<!--
TextArea = document.NewsForm.news;
var error= {
	title:	'{$fm->LANG['NewsTitleNeeded']}',
	news:	'{$fm->LANG['NewsTextNeeded']}'
};
//-->
</script>
			</form>
DATA;
?>