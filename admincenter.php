<?php
/****************************************************************************
 * ExBB v.1.9                                                                *
 * Copyright (c) 2002-20õõ by Alexander Subhankulov aka Warlock                *
 *                                                                            *
 * http://www.exbb.net                                                        *
 * email: admin@exbb.net                                                    *
 *                                                                            *
 ****************************************************************************/
/****************************************************************************
 *                                                                            *
 *   This program is free software; you can redistribute it and/or modify    *
 *   it under the terms of the GNU General Public License as published by    *
 *   the Free Software Foundation; either version 2 of the License, or        *
 *   (at your option) any later version.                                    *
 *                                                                            *
 ****************************************************************************/
define('IN_ADMIN', true);
define('IN_EXBB', true);

include( './include/common.php' );
$fm->_GetVars(true);
$fm->_BOARDSTATS();
$fm->_LoadLang('admincenter', true);

$boarddays = ( time() - $fm->exbb['boardstart'] ) / 86400;
$posts_per_day = sprintf("%.2f", $fm->_Stats['totalposts'] / $boarddays);
$topics_per_day = sprintf("%.2f", $fm->_Stats['totalthreads'] / $boarddays);
$users_per_day = sprintf("%.2f", $fm->_Stats['totalmembers'] / $boarddays);
if ($users_per_day > intval($fm->_Stats['totalmembers'])) {
	$users_per_day = $fm->_Stats['totalmembers'];
}
$boardstart = date("d.m.Y - H:i", $fm->exbb['boardstart']);

$onlinedata = $fm->_Read(EXBB_DATA_MEMBERS_ONLINE);
$onlinedata = count($onlinedata);
$php_ver = phpversion();
$gzip = ( $fm->exbb['gzip_compress'] ) ? $fm->LANG['On'] : $fm->LANG['Off'];

// Unix-like server load averages
$server_load = '';
if (preg_match('/[c-z]:\\\.*/i', __FILE__)) {
	$server_load = $fm->LANG['ServerLoadsNo'];
}
elseif (@file_exists('/proc/loadavg')) {
	if ($fp = @fopen('/proc/loadavg', 'r')) {
		$data = @fread($fp, 6);
		@fclose($fp);
		$loaded = explode(" ", $data);
		$server_load = trim($loaded[0]);
	}
}
else {
	$loaded = @exec('uptime');
	if (preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/i', $loaded, $srv_load)) {
		$server_load = $srv_load[1] . ' ' . $srv_load[2] . ' ' . $srv_load[3];
	}
}

$uploads = 0;

if ($dir = opendir('uploads')) {
	while (false !== ( $file = readdir($dir) )) {
		$uploads += filesize('uploads/' . $file);
	}
	closedir($dir);
}
$uploads = round($uploads / 1024);
include( './admin/all_header.tpl' );
include( './admin/nav_bar.tpl' );
include( './admin/index_body.tpl' );
include( './admin/footer.tpl' );
include( 'page_tail.php' );
?>
