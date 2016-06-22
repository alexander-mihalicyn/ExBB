<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

$smilesUpdeated = '';
$_SESSION['nohashed'] = $fm->_Boolean($fm->input,'nohashed');
if ($fm->_Boolean($fm->input,'addsmile') === TRUE) {
	$old_smiles = $fm->_Read($_ForumRoot.'_data/smiles.php');
	$smiles		= $fm->_Read2Write($fp_smiles,$_ForumRoot.'data/smiles.php');

	$cat_id = (count($smiles['cats']) === 0) ? 1:max(array_keys($smiles['cats']))+1;
	$smiles['cats'][$cat_id] = 'Мои старые смайлы';

	$id = 1;
	if (sizeof($smiles['smiles']) > 1) {
		uasort($smiles['smiles'],'sort_by_id');
		end($smiles['smiles']);
		$id = $smiles['smiles'][key($smiles['smiles'])]['id']+1;
	}

	foreach ($old_smiles as $code => $smile) {
			if (isset($smiles['smiles'][$code])) continue;
			$smiles['smiles'][$code]['emt']	= $smile['emt'];
			$smiles['smiles'][$code]['img']	= $smile['img'];
			$smiles['smiles'][$code]['id']	= $id++;
			$smiles['smiles'][$code]['cat']	= $cat_id;
	}
	$fm->_Write($fp_smiles,$smiles);
	unset($old_smiles);
	$smilesUpdeated = $lang['SmilesUpdated'];
}
include($_ForumRoot.'_data/boardinfo.php');
$fm->exbb['boardstart'] = $exbb['boardstart'];
unset($exbb);

$fm->exbb['ch_upfiles']	= '0'.base_convert($fm->exbb['ch_upfiles'], 10, 8);
$fm->exbb['ch_files']	= '0'.base_convert($fm->exbb['ch_files'], 10, 8);
$fm->exbb['ch_dirs']	= '0'.base_convert($fm->exbb['ch_dirs'], 10, 8);

$board_config = "<?php
if (!defined('IN_EXBB')) die('Hack attempt!');";
foreach ($fm->exbb as $key=>$var) {
		switch (gettype($var)) {
			case 'string': 	switch($key) {
								case 'ch_upfiles':
								case 'ch_files':
								case 'ch_dirs':		break;
								default:			$var = "'$var'";break;
							};
							break;
			case 'boolean':	$var = ($var === TRUE) ? 'TRUE':'FALSE';
							break;
		}
$board_config .="
\$this->exbb['$key'] = $var;";
}
$board_config .="\n?>";

$fm->exbb['ch_upfiles']	= octdec($fm->exbb['ch_upfiles']);
$fm->exbb['ch_files']	= octdec($fm->exbb['ch_files']);
$fm->exbb['ch_dirs']	= octdec($fm->exbb['ch_dirs']);

$fm->_WriteText(FM_BOARDINFO, $board_config);

$old_news = $fm->_Read($_ForumRoot.'_data/news.php');
$new_news = array();

foreach ($old_news as $id => $info) {
		$new_news[$id]['t'] = htmlspecialchars(pre_replace($info['t']),ENT_QUOTES);
		$new_news[$id]['p'] = htmlspecialchars(pre_replace($info['p']),ENT_QUOTES);
		$new_news[$id]['h'] = FALSE;
}
krsort($new_news);
unset($old_news);

$fm->_Read2Write($fp_news,$_ForumRoot.'data/news.php');
$fm->_Write($fp_news,$new_news);

$warning = '<div class="ok">'.$lang['NoError'].$lang['ConfUpdateOk'].$smilesUpdeated.'</div>';

/*
	functions
*/
function sort_by_id($a, $b) {
		if ($a['id'] == $b['id']) {
			return 0;
		}
		return ($a['id'] < $b['id']) ? -1 : 1;
}
?>