<?php
include('./templates/InvisionExBB/form_code.tpl');
include('./templates/InvisionExBB/smile_map.tpl');
echo <<<DATA
<script type="text/javascript" language="JavaScript" src="javascript/formcode.js"></script>
			<br />
			<table class="tableborder" cellpadding="0" cellspacing="1" width="100%" border="0">
				<tr>
					<td class="maintitle" colspan="2">
						<img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['NewPMCreating']}
					</td>
				</tr>
				<tr>
					<td valign="middle" align="center" class="tablepad" colspan="2">
						<a href="messenger.php?action=inbox"><img src="{$InBoxIcon}" width="108" height="30" border="0"></a> &nbsp; &nbsp; &nbsp;
						<a href="messenger.php?action=outbox"><img src="{$OutBoxIcon}" width="115" height="30" border="0"></a> &nbsp; &nbsp; &nbsp;
						<a href="messenger.php?action=new"><img src="{$NewPMIcon}" width="94" height="30" border="0"></a>
						<br>
						<br>
					</td>
				</tr>
			</table><br>
			<div id="preview" class="tableborder">
				<div class="maintitle"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;<b>{$fm->LANG['PreviewTitle']}</b></div>
				<div class="titlemedium1" id="prevtext"></div>
				<div class="darkrow2">&nbsp;</div>
			</div>
			{$PreviewData}
			<form name="PM" action="messenger.php" method="post" onkeypress="ctrlEnter(event, this);">
				<input type="hidden" name="action" value="send">
				<table class="tableborder" cellpadding="0" cellspacing="1" width="100%" border="0">
					<tr>
						<td class="titlemedium" align="center" width="25%" valign="middle" colspan="2">
							<b>{$fm->LANG['FillFullForm']}</b>
						</td>
					</tr>
					<tr>
						<td class="pformleft"><b>{$fm->LANG['ForUserName']} : </b><input type="text" name="tousername" value="{$ToUserName}" size="40"></td>
					</tr>
					<tr>
						<td class="pformleft"><b>{$fm->LANG['MessageTitle']} : </b><input class="tab" type="text" name="msgtitle" size="40" maxlength="80" value="{$MessageTitle}"></td>
					</tr>
DATA;
if ($fm->exbb['exbbcodes'] === TRUE) {
echo  <<<DATA
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
echo <<<DATA
					<tr>
						<td class="pformright" valign="top">
							<textarea cols="80" rows="14" name="message" wrap="virtual" tabindex="3" class="textinput" style="width:900px;"  onselect="IEOP();" onclick="IEOP();" onkeyup="IEOP();" onFocus="IEOP();" onChange="IEOP();">{$MessageText}</textarea>
								<br />{$smile_map}
							</td>
					</tr>
					<tr>
						<td class="pformright" valign="middle">
							{$fm->LANG['ShowMail']}
							<input name="show" type="radio" value="yes"> &nbsp; {$fm->LANG['Yes']} &nbsp;&nbsp;&nbsp;
							<input name="show" type="radio" value="no" checked> &nbsp; {$fm->LANG['No']}
						</td>
					</tr>
					<tr>
						<td class="pformstrip" align="center" style="text-align:center" colspan="2">
							<input type="submit" value="{$fm->LANG['Send']}" name="dosend" onClick="return FormChecker(this.form)"> &nbsp;
							<input type="submit" value="{$fm->LANG['Preview']}" name="preview" onClick="Preview(this.form,'newtopic');return false;"> &nbsp;
							<input type="reset" name="Clear" value="{$fm->LANG['Clear']}" tabindex="5" class="forminput">
						</td>
					</tr>
				</table>
<script type="text/javascript" language="JavaScript">
<!--
TextArea = document.PM.message;
var error= {
	tousername:	'{$fm->LANG['OwnerNeeded']}',
	msgtitle:	'{$fm->LANG['TitleNeeded']}',
	message:	'{$fm->LANG['MessageNeeded']}'
};
//-->
</script>
			</form>
			<br>
DATA;
?>