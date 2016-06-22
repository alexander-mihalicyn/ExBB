<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
$fm->_LoadModuleLang('threadstop');

if ($fm->_Boolean($fm->input,'dosave') === TRUE){
	if ($fm->_POST === FALSE) {
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['CorrectPost'],'',1);
	}
	if ($fm->_Intval('threadsnum') === 0) {
		$fm->_Message($fm->LANG['ModuleTitle'],$fm->LANG['TreadsnumFieldEmpty'],'',1);
	}

	$printval		= ($fm->_Boolean($fm->input,'printval') === TRUE) ? 'TRUE':'FALSE';
	$moduleconfig 	= "<?php\nif (!defined('IN_EXBB')) die('Hack attempt!');\n\ndefine(\"FM_SHOW_TOPICS\", {$fm->input['threadsnum']});\ndefine(\"FM_PRINTVAL\", {$printval});\n?>";
	$fm->_WriteText('modules/threadstop/data/config.php', $moduleconfig);
    $fm->_Message($fm->LANG['ModuleTitle'],$fm->LANG['ModuleUpdatedOk'], 'setmodule.php?module=threadstop', 1);
} else {
		include('modules/threadstop/data/config.php');
		$threadsnum		= FM_SHOW_TOPICS;
		$printval_yes	= (FM_PRINTVAL === TRUE) ? 'checked="checked"' : '';
		$printval_no	= (FM_PRINTVAL === FALSE) ? 'checked="checked"' : '';
		include('admin/all_header.tpl');
        include('admin/nav_bar.tpl');
		include('modules/threadstop/admintemplates/index.tpl');
		include('admin/footer.tpl');
}
?>
