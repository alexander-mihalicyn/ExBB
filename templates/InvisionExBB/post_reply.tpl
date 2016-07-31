<?php
include('./templates/InvisionExBB/form_code.tpl');
include('./templates/InvisionExBB/smile_map.tpl');

echo <<<DATA
<script type="text/javascript" language="JavaScript" src="javascript/formcode.js"></script>
			<br>
			<div id="navstrip" align="left">
				<img src="./templates/InvisionExBB/im/nav.gif" border="0" alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &nbsp;&raquo;&nbsp; <a href="forums.php?forum={$forum_id}">{$forumname}</a> &nbsp;&raquo;&nbsp; <a href="topic.php?forum={$forum_id}&topic={$topic_id}">{$topicname}</a>
			</div>
			{$PreviewData}
			<div id="replyform">
				<form name="ReplyFofm" action="post.php" method="POST" onkeypress="ctrlEnter(event, this);" {$enctype}>
					<input type="hidden" name="action" value="addreply">
					<input type="hidden" name="forum" value="{$forum_id}">
					<input type="hidden" name="topic" value="{$topic_id}">
					<table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
						<tr>
							<td class="maintitle" colspan="2"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['ReplyCreate']}</td>
						</tr>
						<tr>
							<td class="pformleft"><b>{$fm->LANG['Name']} {$fm->user['name']}</b> {$reged}</td>
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
							<td class="pformright" valign="top">
      							<textarea cols="80" rows="14" name="inpost" tabindex="3" class="textinput" style="width:900px;" onselect="IEOP();" onclick="IEOP();" onkeyup="IEOP();" onFocus="IEOP();" onChange="IEOP();">{$fm->input['inpost']}</textarea>
								<br />{$smile_map}
      						</td>
						</tr>
						<tr>
							<td class="pformright">
								{$emailnotify}{$smilesbutton}
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
if ($fm->exbb['anti_bot'] && !$fm->user['id'])
echo <<<DATA
<tr>
	<td class="pformright" valign="top">
		{$fm->LANG['CaptchaDesc']}<br />
		{$fm->LANG['CaptchaReload']}<br />
		<img id="captcha" src="regimage.php" alt="Captcha" /><br /><br />
		<input type="text" name="captcha" size="20" maxlength="10" />
		<script language="JavaScript" src="javascript/reload_captcha.js"></script>
	</td>
</tr>
DATA;
if (defined('IS_ADMIN')) {
	echo <<<DATA
						<tr>
							<td class="pformright">
								{$fm->LANG['EnableHTML']} 
								<input name="html" type="radio" value="yes"> {$fm->LANG['Yes']}
								<input name="html" type="radio" value="no" checked> {$fm->LANG['No']}
							</td>
						</tr>\n
DATA;
}
echo <<<DATA
						<tr>
							<td class="pformstrip" align="center" style="text-align:center" colspan="2">
								<input type="submit" name="submit" value="{$fm->LANG['Send']}" onClick="return FormChecker(this.form)" accesskey="s" /> &nbsp;
								<input type="submit" name="preview" value="{$fm->LANG['Preview']}" onClick="Preview(this.form,'newtopic');return false;"> &nbsp;
								<input type="reset" name="Clear" value="{$fm->LANG['Clear']}" />
							</td>
						</tr>
					</table>
<script type="text/javascript" language="JavaScript">
<!--
TextArea = document.ReplyFofm.inpost;
var error= {
	inpost:		'{$fm->LANG['PostEmpty']}'
};
//-->
</script>
				</form>
			</div>
			<br>
			<div class="tableborder">
  				<div class="maintitle">
  					<img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['TopicReview']}: {$topicname} ({$fm->LANG['NewpostsInTop']})
  				</div>
 				{$topic_data}
 			</div>
DATA;
?>