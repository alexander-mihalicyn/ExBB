<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$fm->_LoadModuleLang('birstday');

if ($fm->_Boolean($fm->input,'dosave') === TRUE){
	if ($fm->_POST === FALSE) {
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
	}

	$birst_pm		= ($fm->_Boolean($fm->input,'birst_pm') === TRUE) ? 'TRUE':'FALSE';
	$birst_em		= ($fm->_Boolean($fm->input,'birst_em') === TRUE) ? 'TRUE':'FALSE';

	$moduleconfig 	= "<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

define(\"FM_BIRSTPM\", {$birst_pm});
define(\"FM_BIRSTEMAIL\", {$birst_em});
?>";
	$fm->_WriteText(EXBB_DATA_DIR_MODULES.'/birthday/config.php', $moduleconfig);
    $fm->_Message($fm->LANG['ModuleTitle'],$fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=birstday', 1);
} else {
		include(EXBB_DATA_DIR_MODULES.'/birthday/config.php');

		$birst_pm_yes	= (FM_BIRSTPM === TRUE) ? 'checked="checked"' : '';
		$birst_pm_no	= (FM_BIRSTPM === FALSE) ? 'checked="checked"' : '';

		$birst_em_yes	= (FM_BIRSTEMAIL === TRUE) ? 'checked="checked"' : '';
		$birst_em_no	= (FM_BIRSTEMAIL === FALSE) ? 'checked="checked"' : '';
		include('admin/all_header.tpl');
        include('admin/nav_bar.tpl');
		include('modules/birstday/admintemplates/index.tpl');
		include('admin/footer.tpl');
}
