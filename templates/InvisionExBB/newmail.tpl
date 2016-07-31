<?php
$this->_NewEmail = <<<DATA
<script language='javascript'>
<!--
document.write('<link rel="Stylesheet" href="./templates/InvisionExBB/'+((isMSIE) ? '_ie.css':'_op_nets.css')+'" />');
function HideMe(){
	document.getElementById("theLayer").style.visibility="hidden";

}
function NewPm() {
	JsHttpRequest.query('jsloader.php?loader=newmail', {action: 'inbox'},function(data,text) {
																				if (data.error && data.error == 1) {
																					alert(text);
																				}
																				HideMe();
																			}
						);
	return false;
}
//-->
</script>
<div id="theLayer" style="position:absolute;width:250px;left:248px;top:60px;visibility:visible">
	<div id="newmail-shadow" align="center">
		<div><spacer type="block" width="250" height="180"/></div>
	</div>
	<div id="newmail" align="center">
		<table border="0" cellspacing="0" cellpadding="0" width="300" align="center" >
			<tr>
				<td class="topL" width="15" height="10" nowrap="nowrap"></td>
				<td class="top" width="270"></td>
				<td class="topR" width="15" height="10" nowrap="nowrap"></td>
			</tr>
			<tr valign="top">
				<td class="mdlL"></td>
				<td class="loginForm">
					<table border="0" width="100%" bgcolor="#424242" cellspacing="1" cellpadding="4" class="tableborder">
						<tr>
							<td colspan="2" width="100%" class="maintitle">
								<img src="./templates/InvisionExBB/im/nav_m.gif" border="0"  alt="&gt;" width="8" height="8" /> {$this->LANG['NewPmTitle']}
							</td>
						</tr>
						<tr>
							<td width="100%" style="padding:4px" class="row4" colspan="2">
								<b>{$GLOBALS['fm']->user['name']}</b>{$this->LANG['YouHaveNewPm']}
								<br>
								{$this->LANG['WantGoPM']}
								<br>
							</td>
						</tr>
						<tr align="center" class="row4">
							<td height="30" class="row4">
								<a href="messenger.php?action=inbox" target="_blank"  onClick="HideMe();"><b>{$this->LANG['Yes']}</b></a>
							</td>
							<td height="30" class="row4">
								<a href="messenger.php?action=inbox" onClick="return NewPm();"><b>{$this->LANG['No']}</b></a>
							</td>
						</tr>
						<tr height="25">
							<td class="darkrow2" colspan="2"></td>
						</tr>
					</table>
				</td>
				<td class="mdlR"></td>
			</tr>
			<tr>
				<td colspan="3">
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td class="btmL"><div style="width:50px;height:20px"><spacer /></div></td>
							<td class="btm" width="99%"><div><spacer /></div></td>
							<td class="btmR"><div style="width:50px;height:20px"><spacer /></div></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>
DATA;
