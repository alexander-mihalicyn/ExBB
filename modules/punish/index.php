<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
$fm->_LoadModuleLang('punish');

if ($fm->_Boolean($fm->input,'dosave') === TRUE){
	if ($fm->_POST === FALSE) {
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
	}

	if ($fm->_Intval('pt3') === 0) {
		$fm->_Message($fm->LANG['ModuleTitle'],$fm->LANG['Pt3FieldEmpty'],'',1);
	}

	if ($fm->_Intval('pt4') === 0) {
		$fm->_Message($fm->LANG['ModuleTitle'],$fm->LANG['Pt4FieldEmpty'],'',1);
	}

	$moduleconfig 	= "<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

define(\"FM_PUNISH3\", {$fm->input['pt3']});
define(\"FM_PUNISH4\", {$fm->input['pt4']});
?>";
	$fm->_WriteText(EXBB_DATA_DIR_MODULES.'/punish/config.php', $moduleconfig);
    $fm->_Message($fm->LANG['ModuleTitle'],$fm->LANG['ModuleUpdateOk'], 'setmodule.php?module=punish', 1);
} else {
		include(EXBB_DATA_DIR_MODULES.'/punish/config.php');
        $pt3 = FM_PUNISH3;
		$pt4 = FM_PUNISH4;
		include('admin/all_header.tpl');
        include('admin/nav_bar.tpl');
        include('modules/punish/admintemplates/index.tpl');
        include('admin/footer.tpl');
}
