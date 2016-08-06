<?php
/***************************************************************************
 * "IP BanPlus" mods for  ExBB v.1.9.1                                      *
 * Copyright (c) 2004 by Alisher Mutalov aka Markus®                        *
 *                                                                          *
 * http://www.tvoyweb.ru                                                    *
 * http://www.tvoyweb.ru/forums/                                            *
 * email: admin@tvoyweb.ru                                                  *
 *                                                                          *
 ***************************************************************************/
define('IN_ADMIN', true);
define('IN_EXBB', true);

include( './include/common.php' );

$fm->_GetVars();
$fm->_String('action');
$fm->_LoadLang('setbannedip', true);

if ($fm->input['action'] == "addip" && $fm->_POST === true) {
	if ($fm->_String('ipbanned') == '') {
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['IpNotEntered'], '', 1);
	}

	if (preg_match("#[^0-9\.\*]#is", $fm->input['ipbanned'])) {
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['WrongCharsInIP'], '', 1);
	}

	if ($fm->_String('ipdesc') == '') {
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['DescNotEntered'], '', 1);
	}
	$banneddata = $fm->_Read2Write($fp_ipban, EXBB_DATA_BANNED_BY_IP_LIST);
	if (Check_Existing_IP($fm->input['ipbanned']) === true) {
		$fm->_Fclose($fp_ipban);
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['IpExists'], '', 1);
	}

	ksort($banneddata);
	end($banneddata);
	$id = key($banneddata) + 1;
	$banneddata[$id]['ipb'] = $fm->input['ipbanned'];
	$banneddata[$id]['regexp'] = ConvertRegExp($fm->input['ipbanned']);
	$banneddata[$id]['ipbd'] = $fm->input['ipdesc'];

	$fm->_Write($fp_ipban, $banneddata);
	$fm->_WriteLog($fm->LANG['LogNewIpBanned'], 1);
	$fm->_Message($fm->LANG['AdminBannedIp'], sprintf($fm->LANG['IpAddedOk'], $fm->input['ipbanned']), 'setbannedip.php', 1);
}
elseif ($fm->input['action'] == "modify") {
	$banneddata = $fm->_Read(EXBB_DATA_BANNED_BY_IP_LIST);
	if (( $id = $fm->_Intval('id') ) === 0 || !isset( $banneddata[$id] )) {
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['IpNotExists'], '', 1);
	}
	$ipb = $banneddata[$id]['ipb'];
	$ipbd = $banneddata[$id]['ipbd'];
	$action = 'savemodify';
	$hidden = '<input type="hidden" name="id" value="' . $id . '">';
	$TableTitle = $fm->LANG['EditIp'];
	$IpTitle = $fm->LANG['IpChang'];
	$DescTitle = $fm->LANG['DescChang'];
	include( 'admin/all_header.tpl' );
	include( 'admin/nav_bar.tpl' );
	include( 'admin/bannedip_show.tpl' );
	include( 'admin/footer.tpl' );
}
elseif ($fm->input['action'] == "savemodify" && $fm->_POST === true) {
	$banneddata = $fm->_Read2Write($fp_ipban, EXBB_DATA_BANNED_BY_IP_LIST);
	if (( $id = $fm->_Intval('id') ) === 0 || !isset( $banneddata[$id] )) {
		$fm->_Fclose($fp_ipban);
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['IpNotExists'], '', 1);
	}

	if ($fm->_String('ipbanned') == '') {
		$fm->_Fclose($fp_ipban);
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['IpNotEntered'], '', 1);
	}

	if (preg_match("#[^0-9\.\*]#is", $fm->input['ipbanned'])) {
		$fm->_Fclose($fp_ipban);
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['WrongCharsInIP'], '', 1);
	}

	if ($fm->_String('ipdesc') == '') {
		$fm->_Fclose($fp_ipban);
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['DescNotEntered'], '', 1);
	}

	if (Check_Existing_IP($fm->input['ipbanned'], $id) === true) {
		$fm->_Fclose($fp_ipban);
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['IpExists'], '', 1);
	}

	$banneddata[$id]['ipb'] = $fm->input['ipbanned'];
	$banneddata[$id]['regexp'] = ConvertRegExp($fm->input['ipbanned']);
	$banneddata[$id]['ipbd'] = $fm->input['ipdesc'];

	$fm->_Write($fp_ipban, $banneddata);
	$fm->_WriteLog($fm->LANG['LogEditingIpBanned'], 1);
	$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['EditingSavedOk'], 'setbannedip.php', 1);
}
elseif ($fm->input['action'] == "delet") {
	$banneddata = $fm->_Read2Write($fp_ipban, EXBB_DATA_BANNED_BY_IP_LIST);
	if (( $id = $fm->_Intval('id') ) === 0 || !isset( $banneddata[$id] )) {
		$fm->_Fclose($fp_ipban);
		$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['IpNotExists'], '', 1);
	}
	unset( $banneddata[$id] );
	$fm->_Write($fp_ipban, $banneddata);
	$fm->_WriteLog($fm->LANG['LogDeleteIp'], 1);
	$fm->_Message($fm->LANG['AdminBannedIp'], $fm->LANG['IpUnbannedOk'], 'setbannedip.php', 1);
}
else {
	$bannedipdata = $fm->_Read(EXBB_DATA_BANNED_BY_IP_LIST);
	ksort($bannedipdata);
	if (count($bannedipdata)) {
		$ipdata = '';
		foreach ($bannedipdata as $id => $info) {
			include( 'admin/bannedip_data.tpl' );
		}
	}
	else {
		$ipdata = '<tr class="gen">
                          	<td class="row2" colspan="4" align="center"><span class="cattitle">' . $fm->LANG['EmptyBannedIp'] . '</span></td>
                          </tr>';
	}
	$ipb = $ipbd = '';
	$action = 'addip';
	$hidden = '';
	$TableTitle = $fm->LANG['AddNewIp'];
	$IpTitle = $fm->LANG['EnterNewIp'];
	$DescTitle = $fm->LANG['EnterNewIpDesc'];
	include( 'admin/all_header.tpl' );
	include( 'admin/nav_bar.tpl' );
	include( 'admin/bannedip_show.tpl' );
	include( 'admin/footer.tpl' );
}
include( 'page_tail.php' );
/****************************************************************
 *                                                               *
 *   Additional functions                                        *
 *                                                               *
 ****************************************************************/

function ConvertRegExp($ipbaned) {

	if (preg_match("#\*#", $ipbaned)) {
		$iparray = explode(".", $ipbaned);
		while (sizeof($iparray) < 4) {
			array_push($iparray, "*");
		}
		foreach ($iparray as $id => $value) {
			if (preg_match("#\*#", $value)) {
				$value = mb_substr(trim($value), 0, -1);
				$num = mb_strlen($value);
				switch ($num) {
					case 0:
						$Nnum = 3;
					break;
					case 1:
						$Nnum = 2;
					break;
					case 2:
						$Nnum = 1;
					break;
				}
				$iparray[$id] = $value . "d{1," . $Nnum . "}";
			}
		}
		$ipbaned = implode(".", $iparray);

		return addcslashes($ipbaned, "\.\d");
	}
	else {
		return addcslashes($ipbaned, "\.");
	}
}

function Check_Existing_IP($__IP, $__IP_ID = '') {
	global $banneddata;
	$return = false;
	foreach ($banneddata as $ip_id => $info) {
		if (empty( $__IP_ID )) {
			if (preg_match("#^" . $info['regexp'] . "$#", $__IP)) {
				$return = true;
				break;
			}
			else {
				continue;
			}
		}
		else {
			if (preg_match("#^" . $info['regexp'] . "$#", $__IP) && $__IP_ID != $ip_id) {
				$return = true;
				break;
			}
			else {
				continue;
			}
		}
	}

	return $return;
}

?>
