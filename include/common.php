<?php
if (!defined('IN_EXBB')) die('Hack attempt!');
error_reporting  (E_ALL);
#error_reporting  (E_ERROR | E_PARSE);

if (!defined("PATH_SEPARATOR")) { define("PATH_SEPARATOR", getenv("COMSPEC")? ";" : ":"); }
ini_set("include_path", ini_get("include_path").PATH_SEPARATOR.dirname(__FILE__));


if (get_magic_quotes_runtime() === 1) set_magic_quotes_runtime(0);

define('FM_PATH',			dirname(dirname(__FILE__)) . '/');
define("FM_LOGDIR",			"data/access_log/");
define("FM_ALLFORUMS",		"data/allforums.php");
define("FM_ALLFORUMS_BAK",	"data/allforums_bak.php");
define("FM_BADWORDS",		"data/badwords.php");
define("FM_BANLIST",		"data/banlist.php");
define("FM_BANNEDIP",		"data/bannedip.php");
define("FM_BANNERS",		"data/banners.php");
define("FM_BOARDINFO",		"data/boardinfo.php");
define("FM_BOARDINFO_BAK",	"data/boardinfo_bak.php");
define("FM_BOARDSTATS",		"data/boardstats.php");
define("FM_COUNTERS",		"data/counters.php");
define("FM_TITLES",			"data/membertitles.php");
define("FM_NEWS",			"data/news.php");
define("FM_ONLINE",			"data/onlinedata.php");
define("FM_SKIP_MAILS",		"data/skip_mails.php");
define("FM_SMILES",			"data/smiles.php");
define("FM_USERS",			"data/users.php");
define("FM_TEMPUSERS",		"data/users_temp.php");
define("FM_SEARCH_EXC",		"data/search_exc.php");
define("FM_VERSION",		"1.0 RC1");

require_once('lib.php');
require_once('page_header.php');

$fm->_Advertising();
$fm->_Authorization();

// Если сервер в заголовках принудительно ставит левую кодировку, пошлём его подальше и поставим свою ;)
header('Content-Type: text/html; charset='.$fm->LANG['ENCODING']);

$fm->exbb['version'] = FM_VERSION;

// На домене установки форум будет доступен только по тому URL, который указан в админке
 // Это предотвращает проблемы с работой сессий и куки на производных поддоменах типа 'www' и т п.
 preg_match("#(www\.|)([[:alnum:]\.\-]+)(/([[:alnum:]\/\.\-]+)|)#is", $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'], $req_url);
 preg_match("#http://(www\.|)([[:alnum:]\.\-]+)(/([[:alnum:]\/\-]+)|)#is", $fm->exbb['boardurl'], $set_url);
 if (@$req_url[2] == @$set_url[2] && $req_url[1] != $set_url[1])
 header('Location: http://'.$set_url[2].$req_url[3].(($_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '')));
 $fm->exbb_domain = $set_url[2];
 $fm->out_redir = 'rd.php?';
 unset($req_url, $set_url);

if ($fm->exbb['installed'] === FALSE) {
header("Location: ./install/index.php");
} elseif (file_exists("./install/index.php")) {
// Удаление папки "install" со всем содержимым
$dir = 'install';
_deldir($dir);
// ============= end ==============================
} elseif (file_exists("./install/index.php")) {
$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['DelleteInstallDir']);
}

if ($fm->exbb['board_closed'] && !(defined('IS_LOGIN') || defined('IS_ADMIN'))) {
$fm->_Message($fm->LANG['BoardClosed'],nl2br(strtr($fm->exbb['closed_mes'], array_flip(get_html_translation_table(HTML_SPECIALCHARS)))));
}
// Функция удаления папки со всем содержимым
function _deldir($dir) {
if ($objs = glob($dir.'/*')) {
foreach($objs as $obj) {
is_dir($obj) ? _deldir($obj) : unlink($obj);
}
}
rmdir($dir);
} 

if ($fm->exbb['installed'] === FALSE) {
	header("Location: ./install/index.php");
} elseif (file_exists("./install/index.php")) {
		$fm->_Message($fm->LANG['MainMsg'],$fm->LANG['DelleteInstallDir']);
}
require('modules/mailer/_send.php');
if ($fm->exbb['board_closed'] && !(defined('IS_LOGIN') || defined('IS_ADMIN'))) {
	$fm->_Message($fm->LANG['BoardClosed'],nl2br(strtr($fm->exbb['closed_mes'], array_flip(get_html_translation_table(HTML_SPECIALCHARS)))));
}
if (!defined('IS_LOGIN') && !defined('IS_REGISTER'))
$_SESSION['rd'] = '';
?>