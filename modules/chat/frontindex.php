<?php

/*
	Chat for ExBB FM 1.0 RC2
	Copyright (c) 2008 - 2009 by Yuri Antonov aka yura3d
	http://www.exbb.org/
	ICQ: 313321962
*/

defined('IN_EXBB') or die;

require_once(EXBB_ROOT.'/modules/chat/common.php');

$fm->_LoadModuleLang('chat');

if (!$fm->user['id'])
	$fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ChatNeedLogin']);

// ѕроверка на наличие шаблонов в скине дл€ чата
$chat_skins = array(DEF_SKIN, $fm->exbb['default_style'], 'InvisionExBB');
foreach ($chat_skins as $skin)
	if (file_exists('templates/'.$skin.'/modules/chat/')) {
		define('CHAT_SKIN', $skin);
		break;
	}
if (!defined('CHAT_SKIN'))
	$fm->_Message($fm->LANG['ModuleTitle'], $fm->LANG['ChatNoSkin']);

switch ($fm->_String('do')) {
	default:			show_chat();
}

function show_chat() {
	global $fm;
	
	$config = $fm->_Read(EXBB_MODULE_CHAT_DATA_CONFIG);
	
	$smiles = array(
		':-)'	=> 'smile24.gif',
		';-)'	=> 'ironical1.gif',
		':-D'	=> 'biggrin24.gif',
		':))'	=> 'laugh24.gif',
		'8-)'	=> 'cool24.gif',
		':-P'	=> 'tongue24.gif',
		'8-|'	=> 'blink.gif',
		':-,'	=> 'dry.gif',
		':-0'	=> 'ohmy.gif',
		':-.'	=> 'odnako.gif',
		'9-)'	=> 'rolleyes24.gif',
		'8-.'	=> 'confused.gif',
		':-('	=> 'trouble.gif',
		':(('	=> 'mad24.gif'
	);
	
	$js_smiles = $show_smiles = '';
	foreach ($smiles as $code => $smile) {
		$js_smiles		.= "\t['".$code."', '".$smile."']".(($smile != end($smiles)) ? ', ' : '')."\n";
		$show_smiles	.= "\t\t\t\t\t\t\t\t\t\t<a href=\"#\" onClick=\"return pasteS('".$code."');\"><img src=\"im/emoticons/".$smile."\" border=\"0\"></a>\n";
	}

	$fm->_Title = ' :: '.$fm->LANG['ModuleTitle'];
	$fm->_Link .= "\n<script type=\"text/javascript\" language=\"JavaScript\">
<!--
var chat = {
	height:		{$config['height']},
	update:		{$config['update']},
	scroll:		{$config['scroll']}
};

var LANG = {
	Month:		{
					1:	'{$fm->LANG['ChatMonth1']}',
					2:	'{$fm->LANG['ChatMonth2']}',
					3:	'{$fm->LANG['ChatMonth3']}',
					4:	'{$fm->LANG['ChatMonth4']}',
					5:	'{$fm->LANG['ChatMonth5']}',
					6:	'{$fm->LANG['ChatMonth6']}',
					7:	'{$fm->LANG['ChatMonth7']}',
					8:	'{$fm->LANG['ChatMonth8']}',
					9:	'{$fm->LANG['ChatMonth9']}',
					10:	'{$fm->LANG['ChatMonth10']}',
					11:	'{$fm->LANG['ChatMonth11']}',
					12:	'{$fm->LANG['ChatMonth12']}'
			},
	ActLogin:	'{$fm->LANG['ChatActLogin']}',
	ActLogout:	'{$fm->LANG['ChatActLogout']}'
};

var smiles = [
{$js_smiles}
];
//-->
</script>";
	include('templates/'.CHAT_SKIN.'/all_header.tpl');
	include('templates/'.CHAT_SKIN.'/logos.tpl');
	include('templates/'.CHAT_SKIN.'/modules/chat/show_chat.tpl');
	include('templates/'.CHAT_SKIN.'/footer.tpl');
}

?>