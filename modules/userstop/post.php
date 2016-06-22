<?php
if (!defined('IN_EXBB')) die('Hack attempt!');

if ($fm->exbb['userstop'] == TRUE){
	$today = mktime(0,0,0,date("m"),date("d"),date("Y"));
	$userkey = $fm->user['id']."::".$fm->user['name'];
	$userstop = $fm->_Read2Write($fp_raiting,'modules/userstop/data/userstop_data.php');
	$userstop[$today][$userkey] = (isset($userstop[$today]) && isset($userstop[$today][$userkey])) ? $userstop[$today][$userkey]+1:1;
	$fm->_Write($fp_raiting,$userstop);
	unset($userstop);
}
?>
