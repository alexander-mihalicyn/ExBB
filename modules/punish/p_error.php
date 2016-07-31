<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

if ($fm->exbb['punish'] === TRUE && isset($fm->user['punned']) && ($total_pun = count($fm->user['punned'])) !== 0) {
	include(EXBB_DATA_DIR_MODULES.'/punish/config.php');

	$fm->_LoadModuleLang('punish');
	if ($total_pun == 5){
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['PunYouBlocked']);
	}
	if ($total_pun == 4 && ((time() - $fm->user['lastpun'])/86400) <= FM_PUNISH4){
    	$fm->_Message($fm->LANG['MainMsg'],sprintf($fm->LANG['PunYouBlockedDay'], FM_PUNISH4));
	}
	if ($total_pun == 3 && ((time()-$fm->user['lastpun'])/86400)<= FM_PUNISH3){
    	$fm->_Message($fm->LANG['MainMsg'],sprintf($fm->LANG['PunYouBlockedDay'], FM_PUNISH3));
	}
}