<?php
error_reporting  (E_ALL);
if (get_magic_quotes_runtime() === 1) set_magic_quotes_runtime(0);

$_ForumRoot = str_replace('install/', '', str_replace('\\', '/', dirname(__FILE__)).'/');

define("FM_DATADIR",		$_ForumRoot."data/");
define("FM_LOGDIR",			$_ForumRoot."data/access_log/");
define("FM_ALLFORUMS",		$_ForumRoot."data/allforums.php");
define("FM_ALLFORUMS_BAK",	$_ForumRoot."data/allforums_bak.php");
define("FM_BADWORDS",		$_ForumRoot."data/badwords.php");
define("FM_BANLIST",		$_ForumRoot."data/banlist.php");
define("FM_BANNEDIP",		$_ForumRoot."data/bannedip.php");
define("FM_BANNERS",		$_ForumRoot."data/banners.php");
define("FM_BOARDINFO",		$_ForumRoot."data/boardinfo.php");
define("FM_BOARDINFO_BAK",	$_ForumRoot."data/boardinfo_bak.php");
define("FM_BOARDSTATS",		$_ForumRoot."data/boardstats.php");
define("FM_COUNTERS",		$_ForumRoot."data/counters.php");
define("FM_TITLES",			$_ForumRoot."data/membertitles.php");
define("FM_NEWS",			$_ForumRoot."data/news.php");
define("FM_ONLINE",			$_ForumRoot."data/onlinedata.php");
define("FM_SKIP_MAILS",		$_ForumRoot."data/skip_mails.php");
define("FM_SMILES",			$_ForumRoot."data/smiles.php");
define("FM_USERS",			$_ForumRoot."data/users.php");
define("FM_TEMPUSERS",		$_ForumRoot."data/users_temp.php");
define("FM_SEARCH_EXC",		$_ForumRoot."data/search_exc.php");

define("FM_VERSION",		"1.0 Final");
define('IN_EXBB', TRUE);
define('FM_SAFE_MODE', (ini_get('safe_mode') ? TRUE:FALSE));
require_once('../include/lib.php');
require_once('page_header.php');
require_once('language/russian/lang.php');

$fm->_GetVars();
$fm->_String('action');

if ($fm->input['action'] === 'updatedesc') {
	if (!isset($_SESSION['updatedesc']) || $_SESSION['updatedesc'] === FALSE) {
		header("Location: index.php?action=selectnext");
		exit();
	}
	$_SESSION['startupdate'] = TRUE;
	_header(FM_VERSION,$lang['UpdateTitle'],$lang['WelcomeUpdate']);
	echo '<div>'.$lang['UpdateDesc'].'</div>';
	_footer(FM_VERSION, 'startupdate', $lang['StartUpdate']);
} elseif ($fm->input['action'] === 'startupdate') {
		if (!isset($_SESSION['startupdate']) || $_SESSION['startupdate'] === FALSE) {
			header("Location: index.php?action=updatedesc");
			exit();
		}
		$checkarray = array("_data","_members","_messages");

		$d = dir($_ForumRoot);
		while (false !== ($file = $d->read())) {
			if (is_dir($_ForumRoot.$file) && preg_match("#^_forum\d+$#is",$file)) {
				array_push($checkarray, $file);
			}
		}
		$d->close();
		$table = '<br><table border="0" cellpadding="2" cellspacing="2" align="center" class="check" style="width: 80%;">
			<caption><b>'.$lang['CheckResults'].'</b></caption>
			<tr>
				<th>Имя папки</th>
				<th>Наличие старой</th>
				<th>Наличие новой</th>
				<th>Права на новую</th>
			</tr>';
		$errors = FALSE;
		foreach ($checkarray as $dirname) {
				$newdirname = str_replace("_","",$dirname);
				$exists 		= (file_exists($_ForumRoot.$dirname)) ? TRUE:FALSE;
				$new_exists		= (file_exists($_ForumRoot.$newdirname)) ? TRUE:FALSE;
				$new_writable	= ($new_exists === TRUE && is_writable($_ForumRoot.$newdirname)) ? TRUE:FALSE;

				if ($exists === FALSE || $new_exists === FALSE || $new_writable === FALSE) {
					$errors = TRUE;
				}
				$table .=  '			<tr align="center">
				<td align="left">'.$_ForumRoot.$newdirname.'</td>
				<td>'.(($exists === TRUE) ? '<span class="ok">да</span>':'<span class="warning">нет</span>').'</td>
				<td>'.(($new_exists === TRUE) ? '<span class="ok">да</span>':'<span class="warning">нет</span>').'</td>
				<td>'.(($new_writable === TRUE) ? '<span class="ok">да</span>':'<span class="warning">нет</span>').'</td>
			</tr>';
		}
		$table .=  '</table><br>';
		$hidden = ($errors === TRUE) ? '':'<br><br><table border="0" cellpadding="2" cellspacing="2" align="center" class="check">
				<caption><b>'.$lang['UpdateOptions'].'</b></caption>
			<tr>
				<td>Отметьте здесь, если Вы хотите добавить в новую базу старые смайлы</td>
				<td><input name="addsmile" type="checkbox" value="yes"></td>
			</tr>
			<tr>
				<td>Отметьте здесь, если пароли на вашем форуме хранятся в открытом виде</td>
				<td><input name="nohashed" type="checkbox" value="yes"></td>

			</tr>
			</table><br>';
		$warning	= ($errors === TRUE) ? '<div class="warning">'.$lang['Error'].$lang['UpdateCheckError'].'</div>':'<div class="ok">'.$lang['NoError'].$lang['CheckDirsOk'].'</div>';
		$ButtonName	= ($errors === TRUE) ? $lang['RePermsCheck']:$lang['ContinueUpdate'];
		$action		= ($errors === TRUE) ? 'startupdate':'updateconfig';
		$_SESSION['updateconfig'] = ($errors === TRUE) ? FALSE:TRUE;

		_header(FM_VERSION,$lang['UpdateTitle'],$lang['CheckDirsForUp']);
		echo $warning.$table;
    	_footer(FM_VERSION, $action, $ButtonName,$hidden);
} elseif ($fm->input['action'] === 'updateconfig') {
        include($_ForumRoot.'install/update/updateconfig.php');
		_header(FM_VERSION,$lang['UpdateTitle'],$lang['ConfUpdate']);
		echo $warning;
    	_footer(FM_VERSION, 'updateforums', $lang['ContinueUpdate']);
} elseif ($fm->input['action'] === 'updateforums') {
		include($_ForumRoot.'install/update/updateforums.php');
		_header(FM_VERSION,$lang['UpdateTitle'],$lang['ForumsUpdated']);
		echo $warning;
    	_footer(FM_VERSION, 'preupdateforum', $lang['ContinueUpdate']);
} elseif ($fm->input['action'] === 'preupdateforum') {
		include($_ForumRoot.'install/update/preupdateforum.php');
		_header(FM_VERSION,$lang['UpdateTitle'],$lang['ForumsPreUpdates']);
		echo $warning;
    	_footer(FM_VERSION, $action, $lang['ContinueUpdate']);
} elseif ($fm->input['action'] === 'updateforum') {
		if ($fm->_Boolean($fm->input,'first') === TRUE && file_exists($_ForumRoot.'install/temp/_allforums.php')) unlink($_ForumRoot.'install/temp/_allforums.php');
		include($_ForumRoot.'install/update/updateforum.php');
		_header(FM_VERSION,$lang['UpdateTitle'],$lang['ForumsUpdates']);
		echo $warning;
    	_footer(FM_VERSION, $action, $lang['ContinueUpdate']);
} elseif ($fm->input['action'] === 'updateusers') {
		include($_ForumRoot.'install/update/updateusers.php');
		_header(FM_VERSION,$lang['UpdateTitle'],$lang['UsersUpdates']);
		echo $warning;
    	_footer(FM_VERSION, $action, $lang['ContinueUpdate']);
} elseif ($fm->input['action'] === 'updatepm') {
		include($_ForumRoot.'install/update/updatepm.php');
		_header(FM_VERSION,$lang['UpdateTitle'],$lang['PMUpdates']);
		echo $warning;
    	_footer(FM_VERSION, $action, $lang['ContinueUpdate']);
} elseif ($fm->input['action'] === 'updatestat') {
		include($_ForumRoot.'install/update/updatestat.php');
		_header(FM_VERSION,$lang['UpdateTitle'],$lang['StatUpdates']);
		echo $warning;
    	_footer(FM_VERSION, $action, $lang['EndUpdate']);
} elseif ($fm->input['action'] === 'updateend') {
		header("Location:".$fm->exbb['boardurl']);
} else {
		header("Location: update.php?action=updatedesc");
		exit();
}
include('page_tail.php');

/*
	functions
*/
function boolean($var) {
	settype($var, "boolean");
	return $var;
}

function pre_replace($val) {
		if ($val == '') return '';

		$val = preg_replace("/&#(0|)39;/"      	, '\''		, $val);
		$val = preg_replace("/&#(0|)36;/"      	, '$'		, $val);
		$val = preg_replace("/&#(0|)92;/"       , '\\'		, $val);
		$val = preg_replace("/<p>/"      		, '\n\n'	, $val);
		$val = preg_replace("/<br>/"       		, "\n"		, $val);
		$val = preg_replace("/&#60;script/i"	, '<script'	, $val);
		$val = str_replace("&amp;"        		, '&'		, $val);
		$val = str_replace("&#60;&#33;--" 		, '<!--'	, $val);
		$val = str_replace("--&#62;"      		, '-->'		, $val);
		$val = str_replace("&gt;"            	, '>'		, $val);
		$val = str_replace("&lt;"            	, '<'		, $val);
		$val = str_replace("&quot;"				, '"'		, $val);
		return $val;
}

function _header($version,$action,$title) {
		echo <<<PAGEHEADER
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>ExBB FM {$version} :: {$action}</title>
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<style type="text/css">
<!--
body {font: 8pt Verdana, Geneva, Arial, Helvetica, sans-serif;color: Black;text-align: center;}
a:link, a:active, a:active, a:visited {color: gray;text-decoration:none;}
a:hover {color:#dd6900;}
a.red:link, a.red:active, a.red:visited {color: red;text-decoration:none;}
a.red:hover {color:#dd6900;}
.main {border: 1px solid #CCCCCC;padding: 0px;width:80%;text-align: center;}
.inmain {margin: 3px;padding:5px;background-color: #ECECEC;text-align: left;}
.inmain h1, h3, h2{margin: 2px;padding: 0px;font-size: 12pt;}
.inmain h1 {text-align: left;}
.inmain h3 {color: red;text-align: right;}
.inmain h2 {padding: 15px;font-size: 10pt;text-align: center;}
.inmain .warning {color: red;}
.inmain .ok {color: green;}
span.ok, span.warning {font-weight: bolder;}
.inmain div {width: 80%;margin:0 auto;font-weight: normal;}
div.warning span {color: black;}
div.return {font-size: 10pt;text-align: center;}
.inmain div div.field {width: 100%;text-align: right;border-bottom: solid 1px black;padding: 5px 0px 5px 0px;vertical-align: bottom;}
table {width:80%;border-bottom: solid 1px black;}
table.check {width: 70%;}
table th {text-align: center;padding: 3px;border-top: solid 1px black;}
table td {border-top: solid 1px black;padding: 3px 0px 3px 0px;}
form {margin: 3px;text-align: center;}
form table {margin: 3px;text-align: left;}
input {margin: 3px;border:1px solid #999999;background-color: #EDEDED;vertical-align: middle;font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;cursor: pointer;}
input.text {font-family: "Courier New", Courier, monospace;cursor: auto;margin: 3px 0px 3px 0px;width: 100%;}
-->
</style>
</head>
<body>
<div align="center">
	<div class="main">
        <div class="inmain">
			<h1>ExBB FM {$version}</h1>
        	<h3>{$action}</h3>
		</div>
		<div class="inmain">
        	<h2>{$title}</h2>
PAGEHEADER;
}

function _error($action,$title,$errormessage) {
		_header(FM_VERSION,$action,$title);
		echo <<<ERROR
		<div><span class="warning">{$errormessage}</span></div><br><br>
		<div class="return"><a href="javascript:history.go(-1)"> << Вернуться назад</a></div><br>
		</div>
	</div>
</div>
<br />
ERROR;
include('page_tail.php');
}

function _footer($version,$action, $ButtonName, $hidden = '', $SecondButton = '') {
		echo <<<FOOTER
			<form action="update.php?action={$action}" method="POST">
				{$hidden}
				<input name="enter" type="submit" value="{$ButtonName}" class="text" style="width: auto;">{$SecondButton}
			</form>
		</div>
	</div>
</div>
<br /><br />
<center>
	<font size="1">
		<a href="http://exbb.info/community/" title="ExBB FM {$version}" class="red">ExBB FM {$version}</a>
	</font>
</center>
<br />
FOOTER;
}
?>