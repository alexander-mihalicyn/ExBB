<?php
$online_height = $config['height'] - 13;
echo <<<DATA
            <br>
            <div id="navstrip" align="left">
                <img name="formtop" src="./templates/InvisionExBB/im/nav.gif" border="0"  alt="&gt;" />&nbsp;<a href="index.php">{$fm->exbb['boardname']}</a> &raquo; {$fm->LANG['ModuleTitle']}<br><br>
            </div>
			<table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
				<tr>
					<td class="maintitle" colspan="5"><img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['ModuleTitle']}</td>
				</tr>
				<tr>
					<td align="center" class="row2">
						<table width="80%" cellpadding="4" cellspacing="0">
							<tr>
								<td width="80%" valign="top">
									<div id="messages" class="row1" style="height: {$config['height']}px; overflow: auto; text-align: left">
										{$fm->LANG['ChatConnecting']}
									</div>
								</td>
								<td width="20%" align="center" valign="top">
									<div class="row1">{$fm->LANG['ChatOnlineNow']} <span id="now">0</span>
									<div id="online" style="height: {$online_height}px; overflow: auto; text-align: left"></div></div>
								</td>
							</tr>
							<tr>
								<td>
									<input type="text" id="msg" style="width: 60%" onKeyDown="if (event.keyCode == 13) send_msg()"> 
									<input type="button" value="{$fm->LANG['Send']}" onClick="send_msg()">
									<div align="center" style="margin-top: 4px;">
{$show_smiles}
									</div>
								</td>
								<td></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
<script language="JavaScript" src="modules/chat/javascript/chat_yura3d.js"></script>
<script language="JavaScript" type="text/javascript">
<!--
start_chat();
//-->
</script>
DATA;
?>