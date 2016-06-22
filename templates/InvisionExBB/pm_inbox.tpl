<?php
echo <<<DATA
<script language="JavaScript" type="text/JavaScript">
<!--
function ChekUncheck() {
	var i;
	var MainChek  = (document.delet.chek.checked==true) ? true:false;
	for (i = 0; i < document.delet.elements.length; i++){
		if (MainChek==true){
 			document.delet.elements[i].checked = true;
		} else {
				document.delet.elements[i].checked = false;
		}
	}
}

function Chekcheked() {
	Chek = false;
	for (i = 0; i < document.delet.elements.length; i++){
		if (document.delet.elements[i].checked === true){
        	Chek = true;
        	break;
		}
	}
	if (Chek === false) {
		alert('{$fm->LANG['DeleteNotSelect']}');
		return true;
	}
	return  true;
}
//-->
</script>
			<br />
			<form action="messenger.php" method="post" name="delet">
				<input name="action" type="hidden" value="deletemsg">
				<input name="where" type="hidden" value="inbox">
				<table class="tableborder" cellpadding="0" cellspacing="1" width="100%">
					<tr>
						<td class="maintitle" colspan="6">
							<img src="./templates/InvisionExBB/im/nav_m.gif" border="0" alt="&gt;" width="8" height="8" />&nbsp;{$fm->LANG['InboxTitle']}
						</td>
					</tr>
					<tr>
						<td valign="middle" align="center" class="tablepad" colspan="6">
							<a href="messenger.php?action=inbox"><img src="{$InBoxIcon}" width="108" height="30" border="0"></a> &nbsp; &nbsp; &nbsp;
							<a href="messenger.php?action=outbox"><img src="{$OutBoxIcon}" width="115" height="30" border="0"></a> &nbsp; &nbsp; &nbsp;
							<a href="messenger.php?action=new"><img src="{$NewPMIcon}" width="94" height="30" border="0"></a>
							<br>
							<br>
						</td>
					</tr>
					<tr class="titlemedium" align="center">
						<td width="7%" valign="middle"><b>{$fm->LANG['MessageStatus']}</b></td>
						<td width="15%" valign="middle"><b>{$fm->LANG['Sender']}</b></td>
						<td width="41%" valign="middle"><b>{$fm->LANG['MessageTitle']}</b></td>
						<td width="18%" valign="middle"><b>{$fm->LANG['MessageDate']}</b></td>
						<td width="11%" valign="middle"><b>{$fm->LANG['Reply2Message']}</b></td>
						<td width="8%" valign="middle"><input name="chek" type="checkbox" value="" onClick="ChekUncheck()"></td>
					</tr>
					{$inbox_data}
DATA;
if ($TotalInbox === 0) {
echo <<<DATA
					<tr>
						<td colspan="6" class="row4" style="padding:5px;margin-top:1px" align="center" valign="middle">
							<b>{$fm->LANG['EmptyData']}</b>
						</td>
					 </tr>
DATA;
}
echo <<<DATA
					<tr>
						<td class="pformstrip" align="right" colspan="6"><input type="submit" value="{$fm->LANG['Delete']}" onClick="return Chekcheked();">&nbsp;</td>
					</tr>
					<tr>
						<td class="activeuserstrip" align="center" colspan="6">&nbsp;</td>
					</tr>
				</table>
			</form>
			<br>
			<br>
			<div align="center">
				<img src="./templates/InvisionExBB/im/readed.gif" alt="{$fm->LANG['ReadedSts']}" hspace="3" /> {$fm->LANG['MesReaded']} &nbsp; &nbsp;
				<img src="./templates/InvisionExBB/im/not_readed.gif" alt="{$fm->LANG['NotReadedSts']}" hspace="3" /> {$fm->LANG['MesNotReaded']}
			</div>
DATA;
?>
