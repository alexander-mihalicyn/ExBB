<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
$fm->_LoadModuleLang('userstop');

if ($fm->_Boolean($fm->input,'dosave') === TRUE){
	if ($fm->_POST === FALSE) {
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
	}

	if ($fm->_Intval('fordays') === 0) {
		$fm->_Message($fm->LANG['ModuleTitle'],$fm->LANG['HowDaysFieldEmpty'],'',1);
	}

	$showposts		= ($fm->_Boolean($fm->input,'showposts') === TRUE) ? 'TRUE':'FALSE';
	$moduleconfig 	= "<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

define(\"FM_USERSTOP_DAYS\", {$fm->input['fordays']});
define(\"FM_USERSTOP_SHOWPOSTS\", {$showposts});
?>";
	$fm->_WriteText('modules/userstop/data/config.php', $moduleconfig);
    $fm->_Message($fm->LANG['ModuleTitle'],$fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=userstop', 1);
} else {
		include('modules/userstop/data/config.php');
		$fordays	= FM_USERSTOP_DAYS;
		$showposts_yes	= (FM_USERSTOP_SHOWPOSTS === TRUE) ? 'checked="checked"' : '';
		$showposts_no	= (FM_USERSTOP_SHOWPOSTS === FALSE) ? 'checked="checked"' : '';
		include('admin/all_header.tpl');
		include('admin/nav_bar.tpl');
		include('modules/userstop/admintemplates/index.tpl');
		include('admin/footer.tpl');
}
?>
