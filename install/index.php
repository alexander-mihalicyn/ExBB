<?php
error_reporting(E_ALL);
if (get_magic_quotes_runtime() === 1) {
	set_magic_quotes_runtime(0);
}

$_ForumRoot = str_replace('install/', '', str_replace('\\', '/', dirname(__FILE__)) . '/');

define("FM_DATADIR", $_ForumRoot . "data/");
define("FM_LOGDIR", $_ForumRoot . "data/access_log/");
define("FM_ALLFORUMS", $_ForumRoot . "data/allforums.php");
define("FM_ALLFORUMS_BAK", $_ForumRoot . "data/allforums_bak.php");
define("FM_BADWORDS", $_ForumRoot . "data/badwords.php");
define("FM_BANLIST", $_ForumRoot . "data/banlist.php");
define("FM_BANNEDIP", $_ForumRoot . "data/bannedip.php");
define("FM_BANNERS", $_ForumRoot . "data/banners.php");
define("FM_BOARDINFO", $_ForumRoot . "data/boardinfo.php");
define("FM_BOARDINFO_BAK", $_ForumRoot . "data/boardinfo_bak.php");
define("FM_BOARDSTATS", $_ForumRoot . "data/boardstats.php");
define("FM_COUNTERS", $_ForumRoot . "data/counters.php");
define("FM_TITLES", $_ForumRoot . "data/membertitles.php");
define("FM_NEWS", $_ForumRoot . "data/news.php");
define("FM_ONLINE", $_ForumRoot . "data/onlinedata.php");
define("FM_SKIP_MAILS", $_ForumRoot . "data/skip_mails.php");
define("FM_SMILES", $_ForumRoot . "data/smiles.php");
define("FM_USERS", $_ForumRoot . "data/users.php");
define("FM_TEMPUSERS", $_ForumRoot . "data/users_temp.php");
define("FM_SEARCH_EXC", $_ForumRoot . "data/search_exc.php");

define("FM_VERSION", "1.0 Final");
define('IN_EXBB', true);
define('FM_SAFE_MODE', ( ini_get('safe_mode') ? true : false ));
require_once( '../include/lib.php' );
require_once( 'page_header.php' );
require_once( 'language/russian/lang.php' );

$fm->_GetVars();
$fm->_String('action');

if ($fm->input['action'] === 'checkperms') {
	$_CheckFiles = array( "data/", "data/access_log/", "data/allforums.php", "data/badwords.php", "data/banlist.php", "data/bannedip.php", "data/boardinfo.php", "data/boardstats.php", "data/membertitles.php", "data/news.php", "data/onlinedata.php", "data/skip_mails.php", "data/smiles.php", "data/users.php", "data/users_temp.php", "im/avatars/personal/", "im/emoticons/temp/", "members/", "messages/", "search/db/", "search/temp/", "uploads/", "modules/birstday/data/", "modules/birstday/data/birstday_data.php", "modules/birstday/data/config.php", "modules/karma/data/", "modules/karma/data/karmalog.php", "modules/punish/data/", "modules/punish/data/config.php", "modules/threadstop/data/", "modules/threadstop/data/config.php", "modules/userstop/data/", "modules/userstop/data/config.php", "modules/userstop/data/userstop_data.php", "modules/statvisit/data/", "modules/statvisit/data/config.php", "modules/statvisit/data/today.php", "modules/reputation/data/", "modules/reputation/data/config.php" );
	$table = '<br><table border="0" cellpadding="2" cellspacing="2" align="center" class="check">
			<caption><b>' . $lang['CheckResults'] . '</b></caption>
			<tr>
				<th>' . $lang['FileName'] . '</th>
				<th>' . $lang['FileExists'] . '</th>
				<th>' . $lang['FilePerms'] . '</th>
			</tr>';
	$not_exists = 0;
	$not_writable = 0;
	foreach ($_CheckFiles as $filename) {
		if (file_exists($_ForumRoot . $filename)) {
			$exists = '<span class="ok">да</span>';
			if (is_writable($_ForumRoot . $filename)) {
				$writable = '<span class="ok">да</span>';
			}
			else {
				$not_writable++;
				$writable = '<span class="warning">нет</span>';
			}
		}
		else {
			$not_exists++;
			$exists = '<span class="warning">нет</span>';
			$writable = '<span class="warning">нет</span>';
		}
		$table .= '			<tr align="center">
				<td align="left">' . $filename . '</td>
				<td>' . $exists . '</td>
				<td>' . $writable . '</td>
			</tr>';
	}
	$table .= '</table><br>';
	if ($not_exists !== 0) {
		$warning = '<div class="warning">' . ( ( $not_writable !== 0 ) ? $lang['Error'] . $lang['NotExists'] . $lang['NoPerms'] : $lang['Error'] . $lang['NotExists'] ) . $lang['ForContinuePerms'] . '</div>';
		$action = 'checkperms';
		$ButtonName = $lang['RePermsCheck'];
		$_SESSION['checkperms'] = false;
	}
	elseif ($not_writable !== 0) {
		$warning = '<div class="warning">' . $lang['Error'] . $lang['NoPerms'] . $lang['ForContinuePerms'] . '</div>';
		$action = 'checkperms';
		$ButtonName = $lang['RePermsCheck'];
		$_SESSION['checkperms'] = false;
	}
	else {
		$warning = '<div class="ok">' . $lang['NoError'] . $lang['PermsOk'] . '</div>';
		$action = 'configenter';
		$ButtonName = $lang['ContinueInstall'];
		$_SESSION['checkperms'] = true;
	}

	_header(FM_VERSION, $lang['InstallTitle'], $lang['PermsCheck']);
	echo $warning . $table;
	_footer(FM_VERSION, $action, $ButtonName);
}
elseif ($fm->input['action'] === 'configenter') {
	if (!isset( $_SESSION['checkperms'] ) || $_SESSION['checkperms'] === false) {
		header("Location: index.php?action=checkperms");
		exit();
	}

	if ($fm->_Boolean($fm->input, 'dosave') === false) {
		_header(FM_VERSION, $lang['InstallTitle'], $lang['ConfigOptions']);
		$this_url = ( ( isset( $_SERVER['HTTPS'] ) ) ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . str_replace('/install', '', dirname($_SERVER['PHP_SELF']));
		$table = '<br>
            <input type="hidden" name="dosave" value="yes">
            <table border="0" cellpadding="0" cellspacing="0" align="center">
            	<tr>
					<td width="65%">
						<b>URL до скриптов</b>
						<br>
						URL (должен начинаться с http://) адрес, где находятся скрипты
						<br>
						<i>(например http://www.your_site.ru/forums)</i>
					</td>
					<td><input type="text" name="boardurl" value="' . $this_url . '" class="text"></td>
				</tr>
				<tr>
					<td><b>Права (CHMOD) на создаваемые папки</b></td>
					<td><input type="text" name="ch_dirs" value="0777" class="text" style="width: 50px;"></td>
				</tr>
				<tr>
					<td><b>Права (CHMOD) на создаваемые файлы</b></td>
					<td><input type="text" name="ch_files" value="0777" class="text" style="width: 50px;"></td>
				</tr>
				<tr>
					<td><b>Права (CHMOD) на загружаемые файлы</b></td>
					<td><input type="text" name="ch_upfiles" value="0644" class="text" style="width: 50px;"></td>
				</tr>
				<tr>
					<td><b>Название форума</b></td>
					<td><input type="text" name="boardname" value="" class="text"></td>
				</tr>
				<tr>
					<td><b>Описание форума</b></td>
					<td><input type="text" name="boarddesc" value="" class="text"></td>
				</tr>
				<tr>
					<td><b>E-mail форума</b></td>
					<td><input type="text" name="adminemail" value="" class="text"></td>
				</tr>
			</table><br><br>';
		_footer(FM_VERSION, 'configenter', $lang['ContinueInstall'], $table);
	}
	else {
		if ($fm->_String('boardurl') === '') {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "URL до скриптов" или неправильный формат URL!');
		}

		if ($fm->_String('ch_dirs') === '' || !preg_match("#^0\d{3}$#", $fm->input['ch_dirs'])) {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "Права (CHMOD) на создаваемые папки" или неправильный формат!');
		}

		if ($fm->_String('ch_files') === '' || !preg_match("#^0\d{3}$#", $fm->input['ch_files'])) {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "Права (CHMOD) на создаваемые файлы" или неправильный формат!');
		}

		if ($fm->_String('ch_upfiles') === '' || !preg_match("#^0\d{3}$#", $fm->input['ch_upfiles'])) {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "Права (CHMOD) на загружаемые файлы" или неправильный формат!');
		}

		if ($fm->_String('boardname') === '') {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "Название форума"!');
		}

		if ($fm->_String('boarddesc') === '' || !preg_match("#^0\d{3}$#", $fm->input['ch_upfiles'])) {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "Описание форума"!');
		}

		$board_config = "<?php
if (!defined('IN_EXBB')) die('Hack attempt!');";
		foreach ($fm->exbb as $key => $var) {
			switch ($key) {
				case 'boardurl':
				case 'ch_dirs':
				case 'ch_files':
				case 'ch_upfiles':
				case 'boardname':
				case 'boarddesc':
				case 'adminemail':
					$var = $fm->input[$key];
				break;
				case 'installed':
					$var = true;
				break;
				case 'boardstart':
					$var = $fm->_Nowtime;
				break;
				default:
				break;
			}

			switch (gettype($var)) {
				case 'string':
					switch ($key) {
						case 'ch_upfiles':
						case 'ch_files':
						case 'ch_dirs':
						break;
						default:
							$var = "'$var'";
						break;
					};
				break;
				case 'boolean':
					$var = ( $var === true ) ? 'TRUE' : 'FALSE';
				break;
			}
			$board_config .= "
\$this->exbb['$key'] = $var;";
		}
		$board_config .= "\n?>";

		$fm->_WriteText(FM_BOARDINFO, $board_config);
		$_SESSION['createadmin'] = true;
		header("Location: index.php?action=createadmin");
	}
}
elseif ($fm->input['action'] === 'createadmin') {
	if (!isset( $_SESSION['createadmin'] ) || $_SESSION['createadmin'] === false) {
		header("Location: index.php?action=configenter");
		exit();
	}

	if ($fm->_Boolean($fm->input, 'dosave') === false) {
		_header(FM_VERSION, $lang['InstallTitle'], $lang['CreateAdmin']);
		$table = '<br>
            <input type="hidden" name="dosave" value="yes">
            <table border="0" cellpadding="0" cellspacing="0" align="center" style="width: 60%;">
				<tr>
					<td><b>Логин</b></td>
					<td><input type="text" name="adminname" class="text"></td>
				</tr>
				<tr>
					<td><b>Пароль администратора</b></td>
					<td><input type="password" name="adminpass" class="text"></td>
				</tr>
				<tr>
					<td><b>Подтверждение пароля</b></td>
					<td><input type="password" name="readminpass" class="text"></td>
				</tr>
				<tr>
					<td><b>E-mail администратора</b></td>
					<td><input type="text" name="adminemail" class="text"></td>
				</tr>
			</table><br><br>';
		_footer(FM_VERSION, 'createadmin', $lang['ContinueInstall'], $table);
	}
	else {
		if ($fm->_String('adminname') === '' || preg_match("#[^A-Za-zА-Яа-я0-9-_\.\s]#", $fm->input['adminname'])) {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "Логин" или запрещенные символы в логине!');
		}

		if ($fm->_String('adminpass') === '') {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "Пароль администратора"!');
		}

		if ($fm->_String('readminpass') === '') {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "Подтверждение пароля"!');
		}

		if ($fm->input['adminpass'] !== $fm->input['readminpass']) {
			_error($lang['InstallTitle'], 'Ошибка!', 'Введенный пароль и подтверждение не совпадают!');
		}

		if ($fm->_String('adminemail') === '' || $fm->_Chek_Mail('adminemail') === false) {
			_error($lang['InstallTitle'], 'Ошибка!', 'Не заполнено поле "E-mail администратора" или неправильный формат e-mail!');
		}

		$users = array();
		$users[1]['n'] = $fm->_LowerCase($fm->input['adminname']);
		$users[1]['m'] = $fm->input['adminemail'];
		$users[1]['p'] = 0;
		$fm->_Read2Write($fp_users, FM_USERS);
		$fm->_Write($fp_users, $users);

		$user = array();
		$user['id'] = 1;
		$user['name'] = $fm->input['adminname'];
		$user['pass'] = md5($fm->input['adminpass']);
		$user['mail'] = $fm->input['adminemail'];
		$user['status'] = 'ad';
		$user['title'] = '';
		$user['posts'] = 0;
		$user['showemail'] = false;
		$user['www'] = '';
		$user['aim'] = '';
		$user['icq'] = '';
		$user['location'] = '';
		$user['joined'] = $fm->_Nowtime;
		$user['sig'] = '';
		$user['sig_on'] = true;
		$user['timedif'] = 0;
		$user['upload'] = true;
		$user['avatar'] = 'noavatar.gif';
		$user['last_visit'] = 0;
		$user['posted'] = array();
		$user['lastpost'] = array( 'date' => 0, 'link' => '', 'name' => '' );
		$user['lang'] = $fm->exbb['default_lang'];
		$user['skin'] = $fm->exbb['default_style'];
		$user['interests'] = '';
		$user['private'] = array();
		$user['new_pm'] = false;
		$user['sendnewpm'] = false;
		$user['visible'] = false;
		$user['posts2page'] = $fm->exbb['posts_per_page'];
		$user['topics2page'] = $fm->exbb['topics_per_page'];
		$fm->_Read2Write($fp_user, $_ForumRoot . 'members/1.php');
		$fm->_Write($fp_user, $user);

		$fm->_SAVE_STATS(array( 'max_online' => array( 1, 0 ), 'max_time' => array( $fm->_Nowtime, 0 ), 'lastreg' => array( $user['name'], 0 ), 'last_id' => array( 1, 0 ), 'totalmembers' => array( 1, 0 ), 'totalposts' => array( 0, 0 ), 'totalthreads' => array( 0, 0 ), ));

		$_SESSION['selectnext'] = true;
		header("Location: index.php?action=selectnext");
	}
}
elseif ($fm->input['action'] === 'selectnext') {
	if (!isset( $_SESSION['selectnext'] ) || $_SESSION['selectnext'] === false) {
		header("Location: index.php?action=createadmin");
		exit();
	}
	$_SESSION['updatedesc'] = true;
	_header(FM_VERSION, $lang['InstallTitle'], $lang['BoardInstalledOk']);
	echo '<div class="warning">' . $lang['OkInstalleddesc'] . '</div>';
	$secondbutton = ' &nbsp; <input type="button" value="' . $lang['StartUpdate'] . '" class="text" style="width: auto;" onClick="location.href=\'update.php?action=updatedesc\'">';
	_footer(FM_VERSION, 'installend', $lang['EndInstall'], '', $secondbutton);
}
elseif ($fm->input['action'] === 'installend') {
	header("Location: " . $fm->exbb['boardurl']);
	exit();
}
else {
	_header(FM_VERSION, $lang['InstallTitle'], $lang['Welcome']);
	echo $lang['InstallDesc'];
	_footer(FM_VERSION, 'checkperms', $lang['StartInstall']);
}
include( 'page_tail.php' );

/*
	functions
*/
function boolean($var) {
	settype($var, "boolean");

	return $var;
}

function pre_replace($val) {
	if ($val == '') {
		return '';
	}

	$val = preg_replace("/&#(0|)39;/", '\'', $val);
	$val = preg_replace("/&#(0|)36;/", '$', $val);
	$val = preg_replace("/&#(0|)92;/", '\\', $val);
	$val = preg_replace("/<p>/", '\n\n', $val);
	$val = preg_replace("/<br>/", "\n", $val);
	$val = preg_replace("/&#60;script/i", '<script', $val);
	$val = str_replace("&amp;", '&', $val);
	$val = str_replace("&#60;&#33;--", '<!--', $val);
	$val = str_replace("--&#62;", '-->', $val);
	$val = str_replace("&gt;", '>', $val);
	$val = str_replace("&lt;", '<', $val);
	$val = str_replace("&quot;", '"', $val);

	return $val;
}

function _header($version, $action, $title) {
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

function _error($action, $title, $errormessage) {
	_header(FM_VERSION, $action, $title);
	echo <<<ERROR
		<div><span class="warning">{$errormessage}</span></div><br><br>
		<div class="return"><a href="javascript:history.go(-1)"> << Вернуться назад</a></div><br>
		</div>
	</div>
</div>
<br />
ERROR;
	include( 'page_tail.php' );
}

function _footer($version, $action, $ButtonName, $hidden = '', $SecondButton = '') {
	echo <<<FOOTER
			<form action="index.php?action={$action}" method="POST">
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
