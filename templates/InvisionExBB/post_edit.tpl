<?php
include('./templates/InvisionExBB/form_code.tpl');
include('./templates/InvisionExBB/smile_map.tpl');
echo <<<DATA
<script type="text/javascript" language="JavaScript" src="javascript/formcode.js"></script>
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; <a href="forums.php?forum={$forum_id}">{$forumname}</a> &nbsp;&raquo;&nbsp; <a href="topic.php?forum={$forum_id}&topic={$topic_id}">{$topicname}</a>
			</div>
			<div id="preview" class="tableborder">
				<div class="maintitle"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;<b>{$fm->LANG['PreviewTitle']}</b></div>
				<div class="titlemedium1" id="prevtext"></div>
				<div class="darkrow2">&nbsp;</div>
			</div>
			{$PreviewData}
			<div id="replyform">
				<form name="EditPost" action="postings.php" method="POST" onkeypress="ctrlEnter(event, this);" {$enctype}>
					<input type="hidden" name="action" value="processedit">
					<input type="hidden" name="postid" value="{$post_id}">
					<input type="hidden" name="forum" value="{$forum_id}">
					<input type="hidden" name="topic" value="{$topic_id}">
					<table class="tableborder" cellpadding="0" cellspacing="0" width="100%">
						<tr>
      						<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['Topic']}: {$topicname}</td>
						</tr>
						<tr>
							<td class="pformleft"><b>{$fm->LANG['Name']} : {$fm->user['name']}</b></td>
						</tr>
DATA;
if ($forumcodes === TRUE) {
	echo <<<DATA
						<tr>
							<td class="pformright">
								{$form_code}
							</td>
						</tr>
						<tr>
							<td class="helpstyle" valign="top" id="help" height="20">
								{$fm->LANG['HelpStyle']}
							</td>
						</tr>\n
DATA;
}
echo <<<DATA
						<tr>
							<td class="pformright" align="center">
								<b>{$fm->LANG['MessageЕext']}</b>
							</td>
						</tr>
						<tr>
							<td class="pformright" valign="top">
      							<textarea cols="80" rows="14" name="inpost" tabindex="3" class="textinput" style="width:900px;" onselect="IEOP();" onclick="IEOP();" onkeyup="IEOP();" onFocus="IEOP();" onChange="IEOP();">{$inpost}</textarea>
								<br />{$smile_map}
      						</td>
						</tr>
						<tr>
							<td class="pformright">
								{$smilesbutton}
							</td>
						</tr>\n
DATA;
if ($upload !== 0) {
	echo <<<DATA
						<tr valign="top">
							<td class="pformright">
								{$fm->LANG['FileUpload']} <br /> {$fm->LANG['FileUploadMax']}{$upload}
								<br />
								{$fm->LANG['UploadExts']} {$fm->exbb['file_type']}
								<br />
								<input type="hidden" name="MAX_FILE_SIZE" value="{$upload}">
								<input class="input" type="file" size="30" name="FILE_UPLOAD">
							</td>
						</tr>\n
DATA;
}
if (defined('IS_ADMIN')) {
	echo <<<DATA
						<tr>
							<td class="pformleft">{$fm->LANG['EnableHTML']}
								<input name="html" type="radio" value="yes"> {$fm->LANG['Yes']}&nbsp;
								<input name="html" type="radio" value="no" checked> {$fm->LANG['No']}
							</td>
						</tr>\n
DATA;
}
if ($moderform === TRUE) {
echo <<<DATA
						<tr>
							<td class="pformstrip"><b>{$fm->LANG['AdminOptions']}</b></td>
						</tr>
						<tr>
							<td class="pformright">Добавить подпись редактора?
								<input name="modertext" type="radio" value="yes"{$modertext_yes}>&nbsp;{$fm->LANG['Yes']}&nbsp;
								<input name="modertext" type="radio" value="no"{$modertext_no}>&nbsp;{$fm->LANG['No']}
							</td>
						</tr>
						<tr>
							<td class="pformleft" valign="top">{$fm->LANG['DeleteThisPost']}
								<input name="deletepost" type="radio" value="yes">&nbsp;{$fm->LANG['Yes']}&nbsp;
								<input name="deletepost" type="radio" value="no" checked>&nbsp;{$fm->LANG['No']}
							</td>
						</tr>
						<tr>
							<td class="pformleft" valign="top">{$fm->LANG['DoBlockEd']}
								<input name="lockedit" type="radio" value="yes"{$lockedit_yes}>&nbsp;{$fm->LANG['Yes']}&nbsp;
								<input name="lockedit" type="radio" value="no"{$lockedit_no}>&nbsp;{$fm->LANG['No']}
							</td>
						</tr>
						<tr>
							<td class="pformleft" valign="top">
								{$fm->LANG['AdminNotice']} <br /><textarea class="textarea1" cols="60" name="mo_text" rows="3" wrap="virtual">{$mo_text}</textarea>
							</td>
						</tr>
DATA;
}
echo <<<DATA
						<tr>
							<td class="pformstrip" align="center" colspan="2">
								<input type="submit" name="submit" value="{$fm->LANG['Send']}" onClick="return FormChecker(this.form)" accesskey="s" /> &nbsp;
								<input type="submit" name="preview" value="{$fm->LANG['Preview']}" onClick="Preview(this.form,'newtopic');return false;"> &nbsp;
								<input type="reset" name="Clear" value="{$fm->LANG['Clear']}" />
							</td>
						</tr>
					</table>
<script type="text/javascript" language="JavaScript">
<!--
TextArea = document.EditPost.inpost;
var error= {
	inpost:		'{$fm->LANG['PostEmpty']}'
};
//-->
</script>
				</form>
			</div>
DATA;
