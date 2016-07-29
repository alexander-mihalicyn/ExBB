<?php
defined('IN_EXBB') or die;

use ExBB\Helpers\FileSystemHelper;

ini_set("include_path", ini_get("include_path") . PATH_SEPARATOR . __DIR__);

define('EXBB_ROOT', dirname(__DIR__));
define('EXBB_BASE', EXBB_ROOT);

require __DIR__.'/paths.php';

define('FM_PATH', dirname(__DIR__) . '/');
define("FM_VERSION", "1.0 RC1");

require_once( 'lib.php' );
require_once( 'page_header.php' );

$fm->_Advertising();
$fm->_Authorization();

$fm->exbb['version'] = FM_VERSION;

// На домене установки форум будет доступен только по тому URL, который указан в админке
// Это предотвращает проблемы с работой сессий и куки на производных поддоменах типа 'www' и т п.
preg_match("#(www\.|)([[:alnum:]\.\-]+)(/([[:alnum:]\/\.\-]+)|)#is", $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'], $req_url);
preg_match("#http://(www\.|)([[:alnum:]\.\-]+)(/([[:alnum:]\/\-]+)|)#is", $fm->exbb['boardurl'], $set_url);
if (@$req_url[2] == @$set_url[2] && $req_url[1] != $set_url[1]) {
	header('Location: http://' . $set_url[2] . $req_url[3] . ( ( $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '' ) ));
}
$fm->exbb_domain = $set_url[2];
$fm->out_redir = 'rd.php?';
unset( $req_url, $set_url );

if ($fm->exbb['installed'] === false) {
	header("Location: ./install/index.php");
}
elseif (file_exists("./install/index.php") && !DEBUG) {
	FileSystemHelper::deleteDirectoryRecursive(dirname(__DIR__).'/install');
}

if ($fm->exbb['board_closed'] && !( defined('IS_LOGIN') || defined('IS_ADMIN') )) {
	$fm->_Message($fm->LANG['BoardClosed'], nl2br(strtr($fm->exbb['closed_mes'], array_flip(get_html_translation_table(HTML_SPECIALCHARS)))));
}

require( 'modules/mailer/_send.php' );
if ($fm->exbb['board_closed'] && !( defined('IS_LOGIN') || defined('IS_ADMIN') )) {
	$fm->_Message($fm->LANG['BoardClosed'], nl2br(strtr($fm->exbb['closed_mes'], array_flip(get_html_translation_table(HTML_SPECIALCHARS)))));
}
if (!defined('IS_LOGIN') && !defined('IS_REGISTER')) {
	$_SESSION['rd'] = '';
}